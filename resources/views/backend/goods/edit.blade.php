@extends('layouts/backend')

@section('title', '新建商品')

@section('content')
    <input type="hidden" id="dependent" value="goods">

    <div class="layui-card">
        <div class="layui-card-header">
            新建商品
        </div>

        <div class="layui-card-body">
            <div style="margin-left: 110px;">
                @include('backend.layouts.message')
            </div>
            <form class="layui-form" action="{{ route('goods.update', ['goods_id' => $goods->id]) }}" method="post">
                {{ csrf_field() }}

                <input type="hidden" name="_method" value="PUT">

                {{-- 存储销售属性的值--}}
                <input type="hidden" id="oldSpec" value="{{ old('spec') ? json_encode(old('spec')) : $specAttrs }}">

                <div class="layui-row">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="goods_number" value="{{ old('goods_number') ? old('goods_number') : $goods->goods_number }}"
                                       placeholder="请输入商品编号" autocomplete="off" class="layui-input">
                                @if ($errors->has('goods_number'))
                                    <span class="adm-input-error">
                                        {{ $errors->first('goods_number') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-row">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{{ old('title') ? old('title') : $goods->title }}"
                                       placeholder="请输入标题" autocomplete="off" class="layui-input">
                                @if ($errors->has('title'))
                                    <span class="adm-input-error">
                                        {{ $errors->first('title') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-row">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品描述</label>
                            <div class="layui-input-block">
                                <textarea name="desc" placeholder="请输入描述" class="layui-textarea" class="layui-input">{{ old('desc') ? old('desc') : $goods->desc }}</textarea>
                                @if ($errors->has('desc'))
                                    <span class="adm-input-error">
                                        {{ $errors->first('desc') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">商品主图</label>
                    <div class="layui-input-block">
                        @for($i = 0; $i < 4; $i++)
                            <div class="goods-image">
                                <img src="@if(isset($imageList[$i]))/{{ $imageList[$i] }}@else /images/goods-default.png @endif" alt="默认商品主图" id="goods_img{{ $i }}">
                            </div>
                        @endfor
                        <div class="clear">
                            @if ($errors->has('image_list'))
                                <span class="adm-input-error">{{ $errors->first('image_list') }}</span>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="image_list" id="image_list"
                           value="{{ old('image_list') ? old('image_list') : json_encode($imageList)}}">
                </div>

                <div class="layui-row">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">选择类目</label>
                            <div class="layui-input-block">
                                <select name="category_id" lay-filter="category_id">
                                    <option value="">请选择类目</option>
                                    @php($categoryId = old('category_id') ? old('category_id') : $goods->category_id)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                @if($categoryId == $category->id) selected="true"@endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category_id'))
                                    <span class="adm-input-error">{{ $errors->first('category_id') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 用于生成销售属性 --}}
                <div id="specificationBlock" class="layui-form" lay-filter="spec"></div>

                <div class="layui-row" style="margin: -10px 0 10px 110px;">
                    @if ($errors->has('spec'))<span class="adm-input-error">{{ $errors->first('spec') }}</span>@endif
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">商品原价</label>
                    <div class="layui-input-inline">
                        <input type="text" name="price" placeholder="请输入密码" class="layui-input"
                               value="{{ old('price') ? old('price') : $goods->translatePrice() }}">
                        @if ($errors->has('price'))
                            <span class="adm-input-error">{{ $errors->first('price') }}</span>
                        @endif
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">商品折扣价</label>
                    <div class="layui-input-inline">
                        <input type="text" name="discount_price" placeholder="请输入商品折扣价" class="layui-input"
                               value="{{ old('discount_price') ? old('discount_price') : $goods->translateDiscountPrice() }}">
                        @if ($errors->has('discount_price'))
                            <span class="adm-input-error">{{ $errors->first('discount_price') }}</span>
                        @endif
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">库存</label>
                    <div class="layui-input-inline">
                        <input type="text" name="stock" placeholder="请输入商品库存" class="layui-input"
                               value="{{ old('stock') ? old('stock') : $goods->stock }}">
                        @if ($errors->has('stock'))
                            <span class="adm-input-error">{{ $errors->first('stock') }}</span>
                        @endif
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">积分兑换</label>
                    <div class="layui-input-inline">
                        @php($isConvertible = old('is_exchange') ? old('is_exchange') : $goods->is_exchange)
                        <input type="checkbox" name="is_exchange" value="1" title="可兑换"
                               @if($isConvertible)checked="true"@endif>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">商品积分</label>
                    <div class="layui-input-inline">
                        <input type="text" name="points" placeholder="请输入商品积分" class="layui-input"
                               value="{{ old('points') ? old('points') : $goods->points }}">
                        @if ($errors->has('points'))
                            <span class="adm-input-error">{{ $errors->first('points') }}</span>
                        @endif
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">上架状态</label>
                    <div class="layui-input-block">
                        @php($onSale = old('on_sale') ? old('on_sale') : $goods->on_sale)
                        <input type="checkbox" title="上架" name="on_sale" value="1" @if($onSale)checked="true"@endif>
                        <div>
                            @if ($errors->has('on_sale'))
                                <span class="adm-input-error">{{ $errors->first('on_sale') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <div class="layui-footer">
                            <button class="layui-btn">提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection