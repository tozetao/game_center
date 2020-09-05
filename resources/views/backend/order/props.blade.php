@extends('layouts.backend')

@section('title', '订单列表')

@section('content')
    <input type="hidden" id="dependent" value="order">

    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <form action="{{ route('order.virtual') }}" method="get">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">支付状态</label>
                        <div class="layui-input-inline">
                            <select name="pay_status">
                                <option value="">请选择支付状态</option>
                                @foreach(config('typemapping.pay_status') as $key => $value)
                                    <option value="{{ $key }}"
                                            @if(old('pay_status') === (string)$key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">发货状态</label>
                        <div class="layui-input-inline">
                            <select name="delivery">
                                <option value="">请选择发货状态</option>
                                @foreach(config('typemapping.delivery') as $key => $value)
                                    <option value="{{ $key }}"
                                            @if(old('delivery') === (string)$key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">订单编号</label>
                        <div class="layui-input-inline">
                            <input type="text" name="order_number" value="{{ old('order_number') }}"
                                   placeholder="请输入订单编号" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">玩家ID</label>
                        <div class="layui-input-inline">
                            <input type="text" name="uid" value="{{ old('uid') }}"
                                   placeholder="请输入玩家ID" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button class="layui-btn layuiadmin-btn-list" lay-submit lay-filter="LAY-app-contlist-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="layui-card-body">
            <table class="layui-table">
                <thead>
                <tr>
                    <th width="60">主图</th>
                    <th width="40">订单编号</th>
                    <th width="150">道具</th>
                    <th width="60">价格</th>
                    <th width="60">支付方式</th>
                    <th width="60">购买数量</th>
                    <th width="60">订单状态</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $model)
                    <tr>
                        <th><img src="/{{ $model->image }}" alt="商品主图" width="50" height="50"></th>
                        <th>{{ $model->order_number }}</th>
                        <td>{{ $model->title }}</td>
                        <td>{{ $model->discount_price }}元</td>
                        <td>{{ $model->translatePaymentType() }}</td>
                        <td>{{ $model->quantity }}</td>
                        <td>{{ $model->translateStatus() }}</td>
                    </tr>
                </tbody>
                @endforeach
            </table>
            {{ $orders->links() }}
        </div>
    </div>
@endsection