<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\PropOrder;
use App\Models\Api\RedEnvelope;
use App\Models\Api\Order;
use App\Models\Api\User;
use App\Services\Converter\AppConverter;
use App\Services\Lock\RedisLock;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{
    // 回调处理
    public function notify()
    {
        $app = resolve('EasyWeChat\Payment\Application');

        return $app->handlePaidNotify(function($message, $fail) {
            Log::info($message);

            // 通信状态判断
            if ('SUCCESS' !== $message['return_code']) {
                return $fail('通信失败，请稍后再通知我');
            }

            $orderNo = isset($message['out_trade_no']) ? $message['out_trade_no']: '';
            $attach  = isset($message['attach']) ? json_decode($message['attach'], true): '';
            $objType = isset($attach['type']) ? $attach['type'] : 0;

            switch ($objType)
            {
                case config('type.obj.order'):
                    return $this->handleOrder($orderNo, $message['result_code']);

                case config('type.obj.red_envelope'):
                    return $this->handleRedEnvelope($orderNo, $message['result_code']);

                case config('type.obj.prop_order'):
                    return $this->handlePropOrder($orderNo, $message['result_code']);
            }

            // 如果支付回调没有正常处理，将会记录问题
            Log::info('wechat notify error.');
            Log::info($message);
            return false;
        });
    }

    // 商品订单处理
    private function handleOrder($orderNo, $resultCode)
    {
        $callback = function () use($orderNo, $resultCode) {
            $order = Order::findByOrderNo($orderNo);

            // 订单状态判断
            if (!$order || config('type.order.unpaid') !== $order->status) {
                return false;
            }

            // 支付结果判断
            if ('SUCCESS' === $resultCode) {
                $order->pay(config('type.pay_channel.wechat_jssdk'));

                $point = AppConverter::fentopoint($order->getTotalFee());
                User::incrPoints($order->uid, $point);
                return true;
            } else {
                $order->failed();
                return false;
            }
        };

        $locker = new RedisLock();
        return $locker->callback($orderNo, $callback);
    }

    // 红包订单处理
    private function handleRedEnvelope($orderNo, $resultCode)
    {
        $callback = function() use($orderNo, $resultCode) {

            $unpaid = config('type.red_envelope.unpaid');
            $model  = RedEnvelope::findByNo($orderNo);

            if (!$model || $unpaid !== $model->status) {
                return false;
            }

            if ('SUCCESS' == $resultCode) {
                $model->pay();

                $point = AppConverter::fentopoint($model->getTotalFee());
                User::incrPoints($model->user_id, $point);
                return true;
            } else {
                $model->failed();
                return false;
            }
        };

        $locker = new RedisLock();
        return $locker->callback($orderNo, $callback);
    }

    private function handlePropOrder($orderNo, $resultCode)
    {
        $callback = function() use($orderNo, $resultCode) {
            $order = PropOrder::findByNo($orderNo);

            // 订单状态判断
            if (!$order || config('type.order.unpaid') !== $order->status) {
                return false;
            }

            // 支付结果判断
            if ('SUCCESS' === $resultCode) {
                $order->pay();

                $point = AppConverter::fentopoint($order->getTotalFee());
                User::incrPoints($model->user_id, $point);

                return true;
            } else {
                $order->failed();
                return false;
            }
        };

        $locker = new RedisLock();
        return $locker->callback($orderNo, $callback);
    }
}
