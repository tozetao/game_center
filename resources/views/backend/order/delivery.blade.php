@extends('layouts/backend')

@section('title', '订单发货')

@section('content')
    <input type="hidden" id="dependent" value="order">

    <div class="layui-card">

        <div class="layui-card-body">
            <div>
                @include('backend.layouts.message')
            </div>
            <form class="layui-form" action="{{ route('order.delivery') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                <table class="layui-table">
                    <colgroup>
                        <col width="150">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <td>订单号</td>
                        <td>
                            {{ $order->order_number }}
                        </td>
                    </tr>
                    <tr>
                        <td>商品</td>
                        <td>
                            {{ $order->title }}
                        </td>
                    </tr>
                    <tr>
                        <td>折扣价</td>
                        <td>{{ $order->translateDiscountPrice() }} 元</td>
                    </tr>
                    <tr>
                        <td>积分</td>
                        <td>{{ $order->points }}</td>
                    </tr>
                    <tr>
                        <td>是否积分兑换</td>
                        <td>{{ $order->translateIsExchange() }}</td>
                    </tr>
                    <tr>
                        <td>购买数量</td>
                        <td>
                            {{ $order->quantity }}
                        </td>
                    </tr>
                    @foreach($order->orderSpecifications as $spec)
                    <tr>
                        <td>{{ $spec->spec_name }}</td>
                        <td>{{ $spec->attr_val }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            收件人
                        </td>
                        <td>{{ $order->orderAddr->addressee }}</td>
                    </tr>
                    <tr>
                        <td>手机</td>
                        <td>{{ $order->orderAddr->phone }}</td>
                    </tr>
                    <tr>
                        <td>收件地址</td>
                        <td>{{ $order->orderAddr->province }} {{ $order->orderAddr->city }} {{ $order->orderAddr->area }}
                            {{ $order->orderAddr->info }}</td>
                    </tr>
                    <tr>
                        <td>快递公司</td>
                        <td>
                            <div class="width250">
                                <select name="express_name">
                                    <option value="">请选择快递公司</option>
                                    @foreach($expresses as $express)
                                        <option value="{{ $express->express_name }}" @if($order->express_name == $express->express_name) selected @endif>{{ $express->express_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>快递单号</td>
                        <td>
                            <div class="width250">
                                <input type="text" name="express_no" class="layui-input" value="{{ $order->express_number }}">
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button class="layui-btn" style="margin-left: 165px;">提交</button>
            </form>
        </div>
    </div>
@endsection