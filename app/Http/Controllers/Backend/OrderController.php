<?php

namespace App\Http\Controllers\Backend;

use App\Filters\OrderFilter;
use App\Models\Backend\Express;
use App\Models\Backend\Order;
use App\Services\ApiResponse;
use App\Services\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    // 订单页
    public function index(Request $request, OrderFilter $filter)
    {
        $order = new Order();

        $orders = $order->getEntity($filter);
        $this->applyPaginateParams($orders);

        Session::flashInput($request->all());

        return view('order/index', compact('orders'));
    }

    // 显示发货页面
    public function showDelivery(Request $request)
    {
        $order = Order::findOrFail($request->get('order_id'));

        $expresses = Express::all();

        return view('order/delivery', compact('order', 'expresses'));
    }

    // 发货
    public function delivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'express_no' => 'required|string|max:30',
            'express_name' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            Flash::failed($validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        $order = Order::findOrFail($request->post('order_id'));

        $expressName = $request->post('express_name');
        $expressNo   = $request->post('express_no');

        if (!$order->deliver($expressName, $expressNo)) {
            Flash::failed('操作失败.');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('操作成功.');
        return redirect()->route('order.showDelivery', ['order_id' => $order->order_id]);
    }

    // 虚拟物品订单
    public function virtual(Request $request, OrderFilter $filter)
    {
        $order = new Order();

        $orders = $order->getVirtual($filter);
        $this->applyPaginateParams($orders);

        Session::flashInput($request->all());

        return view('order/props', compact('orders'));
    }
}
