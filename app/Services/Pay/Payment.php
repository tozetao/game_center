<?php

namespace App\Services\Pay;

use App\Models\Api\RedEnvelope;
use App\Models\Api\Goods;
use App\Models\Api\Order;
use App\Models\Api\PropOrder;
use App\Models\Api\User;
use App\Services\ApiResponse;
use App\Services\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Payment
{
    private $error;

    // 执行支付
    public function execute(Unify $order, $type)
    {
        switch ($type)
        {
            // 微信jssdk支付
            case config('type.pay_channel.wechat_jssdk'):
                return $this->jssdkPay($order);

            // 积分支付
            case config('type.pay_channel.points'):
                return $this->pointsPay($order);
        }

        return [];
    }


    // 微信jssdk支付
    protected function jssdkPay(Unify $order)
    {
        // 统一下单信息
        $params = [
            'openid' => Session::get('user')->openid,
            'trade_type' => 'JSAPI',
            'total_fee' => 1, // $order->getTotalFee()
            'body' => $order->getBody(),
            'out_trade_no' => $order->getNumber(),
            'notify_url' => route('notify.wechat.jsapi'),
            'attach' => $this->getAttach($order)
        ];

        $app = resolve('EasyWeChat\Payment\Application');
        $result = $app->order->unify($params);

        if (!$this->isValid($result)) {
            return ApiResponse::build(
                ApiResponse::UNIFY_ORDER_ERROR, $this->error);
        }

        return $app->jssdk->sdkConfig($result['prepay_id']);
    }

    protected function getAttach(Unify $obj)
    {
        $attach = '';

        if ($obj instanceof Order) {
            $attach = json_encode(['type' => config('type.obj.order')]);
        } else if ($obj instanceof RedEnvelope) {
            $attach = json_encode(['type' => config('type.obj.red_envelope')]);
        } else if ($obj instanceof PropOrder) {
            $attach = json_encode(['type' => config('type.obj.prop_order')]);
        }

        return $attach;
    }

    // 验证微信响应结果是否有效的
    protected function isValid(&$result)
    {
        if (empty($result)) {
            $this->error = '统一下单失败.';
            return false;
        }

        if (isset($result['return_code']) && $result['return_code'] == 'FAIL') {
            $this->error = isset($result['return_msg']) ? $result['return_msg'] : $result['retmsg'];
            return false;
        }

        if (isset($result['result_code']) && $result['result_code'] == 'FAIL') {
            $this->error = $result['err_code_des'];
            return false;
        }

        return true;
    }

    // 积分支付
    protected function pointsPay(Order $order)
    {
        if (!$order->points) {
            return ApiResponse::build(ApiResponse::ORDER_ILLEGAL);
        }

        try {
            DB::beginTransaction();

            // 1. 扣除用户积分
            $uid = Session::get('user')->uid;
            if (!User::deductPoints($uid, $order->points)) {
                throw new \LogicException('用户积分扣除失败.');
            }

            // 2. 改变用户订单状态
            $order->pay(config('type.pay_channel.points'));

            // 3. 减少库存数量
            if (!Goods::deductStock($order->goods_id, $order->quantity)) {
                throw new \LogicException('商品库存扣除失败.');
            }

            DB::commit();

            $orderAddr = $order->orderAddr;
            $address   = $orderAddr->province . $orderAddr->city . $orderAddr->area . $orderAddr->info;

            return [
                'order_no' => $order->order_number,
                'paid_at'  => $order->paid_at,
                'address'  => $address,
                'phone'    => $orderAddr->phone,
                'addressee' => $orderAddr->addressee,
                'payment_type' => $order->payment_type,
            ];
        } catch (\Exception $e) {
            Logger::info($e);
            DB::rollBack();
            return ApiResponse::build(ApiResponse::ORDER_PAY_FAIL, $e->getMessage());
        }
    }
}