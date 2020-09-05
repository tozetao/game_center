<?php

namespace App\Http\Controllers\Api;

use App\Filters\OrderFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Api\Goods;
use App\Models\Api\Order;
use App\Services\ApiResponse;
use App\Services\Pay\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $order;

    // sess_id, goods_id, quantity,  user_addr_id
    // 提交订单
    public function createGoodsOrder(OrderRequest $request)
    {
        $goods = Goods::findById($request->input('goods_id'));

        $order = $request->performCreate($goods);

        if (empty($order)) {
            return ApiResponse::failed(ApiResponse::ORDER_CREATE_FAIL);
        }

        return [
            'order_no' => $order->order_number
        ];
    }

    // 进行支付
    // sess_id, order_no, payment_type
    public function pay(Request $request)
    {
        $this->validatePay($request->all());

        $order = $this->getOrder($request->input('order_no'));
        $paymentType = $request->input('payment_type');

        $payment = new Payment();
        return $payment->execute($order, $paymentType);
    }

    private function validatePay($params)
    {
        $payRule = sprintf(
            'required|in:%d,%d',
            config('type.pay_channel.wechat_jssdk'),
            config('type.pay_channel.points')
        );

        $validator = Validator::make($params, [
            'order_no' => 'required',
            'payment_type' => $payRule
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
            $this->order = Order::findByOrderNo($orderNo);
        }

        return $this->order;
    }

    // 搜索玩家订单记录
    public function search(Request $request, OrderFilter $filter)
    {
        $filter->addCondition('uid', Session::get('user')->uid);

        // 订单基本信息、销售规格
        $order = new Order();
        $collection = $order->search($filter, $request->input('page'));

        return OrderResource::collection($collection);
    }

    // 订单信息
    public function view($orderNo)
    {
    }

    public function status($orderNo)
    {
        if (!$order = Order::findByOrderNo($orderNo)) {
            return ApiResponse::failed(ApiResponse::NOT_FOUND);
        }

        $orderAddr = $order->orderAddr;
        $address   = $orderAddr->province . $orderAddr->city . $orderAddr->area . $orderAddr->info;

        return [
            'status' => $order->status,
            'order_no' => $order->order_number,
            'paid_at'  => $order->paid_at,
            'address'  => $address,
            'phone'    => $orderAddr->phone,
            'addressee' => $orderAddr->addressee,
            'payment_type' => $order->payment_type,
        ];
//        return ['status' => $order->status];
    }
}
