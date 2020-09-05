@extends('layouts.backend')

@section('title', '商品列表')

@section('content')
    <input type="hidden" id="dependent" value="goods">

    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <form action="{{ route('goods.index') }}" method="get">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">标题</label>
                        <div class="layui-input-inline">
                            <input type="text" name="title" value="{{ old('title') }}"
                                   placeholder="请输入商品关键字" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">上架状态</label>
                        <div class="layui-input-inline">
                            <select name="on_sale">
                                <option value="">请选择上架状态</option>
                                @foreach(config('type.sale') as $value)
                                    <option value="{{ $value }}"
                                            @if(old('on_sale') === (string)$value) selected @endif>{{ $value == 0 ? '下架' : '上架' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">商品类型</label>
                        <div class="layui-input-inline">
                            <select name="is_exchange">
                                <option value="">请选择商品类型</option>
                                @foreach(config('type.is_exchange') as $value)
                                    <option value="{{ $value }}"
                                            @if(old('is_exchange') === (string)$value) selected @endif>{{ $value == 1 ? '可积分兑换' : '不可积分兑换' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button class="layui-btn layuiadmin-btn-list" lay-submit lay-filter="LAY-app-contlist-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>

                        <a class="layui-btn" lay-href="{{ route('goods.create') }}">新建商品</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="layui-card-body">
            <table class="layui-table">
                <thead>
                <tr>
                    <th width="40">编号</th>
                    <th width="60">商品主图</th>
                    <th>商品标题</th>
                    <th width="80">价格</th>
                    <th width="60">积分兑换</th>
                    <th width="50">积分</th>
                    <th width="150">创建时间</th>
                    <th width="50">状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($goods as $model)
                    <tr>
                        <th>{{ $model->goods_number }}</th>
                        <th>
                            <img src="/{{ $model->image }}" alt="商品主图" width="50" height="50">
                        </th>
                        <td>{{ $model->title }}</td>
                        <td>{{ $model->translatePrice() }}元</td>
                        <td>{{ $model->translateIsConvertible() }}</td>
                        <td>{{ $model->points }}</td>
                        <td>{{ $model->created_at }}</td>
                        <td>{{ $model->translateOnSale() }}</td>
                        <td>
                            <a class="layui-btn layui-btn-normal layui-btn-xs" lay-href="{{ route('goods.edit', ['id' => $model->id]) }}">
                                <i class="layui-icon layui-icon-edit"></i>编辑
                            </a>
                        </td>
                    </tr>
                </tbody>
                @endforeach
            </table>
            {{ $goods->links() }}
        </div>
    </div>
@endsection