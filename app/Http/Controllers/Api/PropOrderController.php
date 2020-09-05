<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\PropOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropOrderRequest;
use App\Models\Api\Prop;
use App\Services\ApiResponse;
use App\Services\Pay\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PropOrderController extends Controller
{
    protected $order;

    // 创建道具订单
    public function store(PropOrderRequest $request)
    {
        $prop_id = explode(',', $request->input('prop_id'));
        $props = Prop::whereIn('prop_id', $prop_id)->get();

        if (empty($props)) {
            return ApiResponse::failed(ApiResponse::CHECK_ERROR);
        }

        $order = $request->performCreate($props);

        if (empty($order)) {
            return ApiResponse::failed(ApiResponse::ORDER_CREATE_FAIL);
        }

        return ['order_no' => $order->number];
    }

    // 进行支付
    // sess_id, order_no, payment_type
    public function pay(Request $request)
    {
        $this->validatePay($request->all());

        $order = $this->getOrder($request->input('order_no'));
        $paymentType = config('type.pay_channel.wechat_jssdk');

        $payment = new Payment();
        return $payment->execute($order, $paymentType);
    }

    private function validatePay($params)
    {
        $validator = Validator::make($params, [
            'order_no' => 'required',
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
            $this->order = PropOrder::findByNo($orderNo);
        }

        return $this->order;
    }

    public function status($orderNo)
    {
        if (!$order = PropOrder::findByNo($orderNo)) {
            return ApiResponse::failed(ApiResponse::NOT_FOUND);
        }

        return ['status' => $order->status];
    }
}
