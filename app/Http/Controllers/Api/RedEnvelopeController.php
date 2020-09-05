<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\MySubordinate;
use App\Models\Api\RedEnvelope;
use App\Http\Controllers\Controller;
use App\Services\ApiResponse;
use App\Services\Converter\RMBConverter;
use App\Services\Generator\OrderNoGenerator;
use App\Services\Lock\RedisLock;
use App\Services\Pay\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RedEnvelopeController extends Controller
{
    protected $order;

    public function store(Request $request)
    {
        $this->validateStore($request);

        $generator = new OrderNoGenerator();
        $lock = new RedisLock();

        $number = $generator->generate();
        $val    = $request->post('val');

        $callback = function() use ($number, $val) {
            $user = Session::get('user');

            $model = new RedEnvelope();
            $model->val = RMBConverter::yuantofen($val);

            $rate = 1 + config('app.charge_ratio.red_packet');
            $model->real_val = RMBConverter::yuantofen(bcmul($val, $rate, 2));

            $model->status = config('type.red_envelope.unpaid');
            $model->number = $number;
            $model->user_id = $user->uid;
            $model->created_at = time();
            $model->sent_at = 0;
            $model->pay_at = 0;

            if (!$model->save()) {
                return ApiResponse::failed(ApiResponse::FAILED);
            }

            return [
                'no' => $model->number,
                'created_at' => $model->created_at,
                'status' => $model->status
            ];
        };

        return $lock->callback($number, $callback);
    }

    private function validateStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'val' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            ApiResponse::throwValidationException(
                $validator, ApiResponse::FAILED, $validator->errors()->first());
        }
    }

    public function status($no)
    {
        $model = RedEnvelope::findByNo($no);

        if (!$model) {
            return ApiResponse::failed(ApiResponse::NOT_FOUND);
        }

        return ['status' => $model->status];
    }

    public function pay(Request $request)
    {
        $this->validatePay($request->all());

        $order = $this->getOrder($request->input('order_no'));
//        $paymentType = $request->input('payment_type');
        $paymentType = config('type.pay_channel.wechat_jssdk');

        $payment = new Payment();
        return $payment->execute($order, $paymentType);
    }

    private function validatePay($params)
    {
//        $payRule = sprintf(
//            'required|in:%d,%d',
//            config('type.pay_channel.wechat_jssdk'),
//            config('type.pay_channel.points')
//        );

        $validator = Validator::make($params, [
            'order_no' => 'required',
//            'payment_type' => $payRule
        ]);

        if ($validator->fails()) {
            ApiResponse::throwValidationException(
                $validator, ApiResponse::CHECK_ERROR, $validator->errors()->first());
        }

        // 验证订单是否存在，支付状态
        $order = $this->getOrder($params['order_no']);

        if (!$order || intval(config('type.order.unpaid')) !== intval($order->status)) {
            ApiResponse::throwValidationException($validator, ApiResponse::ORDER_ILLEGAL);
        }
    }

    private function getOrder($orderNo)
    {
        if (!$this->order) {
            $this->order = RedEnvelope::findByNo($orderNo);
        }

        return $this->order;
    }

    // 发放红包
    public function send(Request $request)
    {
        $number = $request->get('number');

        $callback = function() use($number) {

            $order = RedEnvelope::findByNo($number);
            $user = Session::get('user');

            $this->validateSend($order, $user->uid);

            if (!$order->send()) {
                return ApiResponse::failed(ApiResponse::FAILED);
            }

            return $this->callRedpack($order, $user);
        };

        $locker = new RedisLock();
        return $locker->callback($number, $callback);
    }

    protected function validateSend(RedEnvelope $order, $userId)
    {
        if (empty($order)) {
            ApiResponse::throwValidationException(null, ApiResponse::CHECK_ERROR);
        }

        // 验证红包订单的玩家与当前玩家是否关联
        if (!MySubordinate::exists($order->user_id, $userId)) {
            ApiResponse::throwValidationException(null, ApiResponse::ORDER_ILLEGAL);
        }

        // 验证红包订单状态
        if ($order->status != config('type.red_envelope.paymented')) {
            ApiResponse::throwValidationException(null, ApiResponse::UNPAID_ORDER);
        }
    }

    protected function callRedpack($order, $user)
    {
        $success = 'SUCCESS';

        // 发送红包
        $redpackData = [
            'mch_billno'   => $order->number,
            'send_name'    => $user->nickname,
            're_openid'    => $user->openid,
            'total_num'    => 1,  //固定为1，可不传
            'total_amount' => $order->val,
            'wishing'      => '祝福语',
            'act_name'     => '测试活动',
            'remark'       => '测试备注',
        ];

        $app = resolve('EasyWeChat\Payment\Application');
        $result = $app->redpack->sendNormal($redpackData);

        Log::info($result);

        if ($result['return_code'] != $success) {
            return ApiResponse::failed(ApiResponse::FAILED, $result['return_msg']);
        }

        if ($result['result_code'] != $success) {
            return ApiResponse::failed(ApiResponse::FAILED, $result['err_code_des']);
        }

        return ApiResponse::success();
    }
}
