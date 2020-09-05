@extends('layouts.backend')

@section('title', '新建商品类目')

@section('content')
    <input type="hidden" id="dependent" value="category">

    <div class="layui-card">
        <div class="layui-card-header">
            新建商品类目
        </div>

        <div class="layui-card-body">
            <div style="margin-left: 110px;">
                @include('backend.layouts.message')
            </div>

            <form class="layui-form" action="{{ route('category.store') }}"
                  method="post" id="categoryForm">
                {{ csrf_field() }}

                <div class="layui-form-item">
                    <label class="layui-form-label">类目名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" placeholder="请输入类目名"
                               autocomplete="off" class="layui-input" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="adm-input-error">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                @if(old('spec'))
                    <input type="hidden" id="clicks" value="{{ count(old('spec')) }}">
                    @foreach(old('spec') as $index => $row)
                        <div class="layui-form-item">
                            <label class="layui-form-label">销售规格名</label>
                            <div class="layui-input-inline">
                                <input type="text" name="spec[{{ $index }}][name]" value="{{ old('spec')[$index]['name'] }}"
                                       placeholder="请输入销售规格名" autocomplete="off" class="layui-input">
                            </div>

                            <label class="layui-form-label">规格属性值</label>
                            <div class="layui-input-inline">
                                <input type="text" name="spec[{{ $index }}][attr_values]"  value="{{ old('spec')[$index]['attr_values'] }}"
                                       placeholder="请输入规格属性值" autocomplete="off" class="layui-input">
                            </div>
                            @if($index == 0)
                                <div><button type="button" class="layui-btn" id="newSpec">新增</button></div>
                            @else
                                <div><button type="button" class="layui-btn" data-remove>移除</button></div>
                            @endif
                        </div>
                    @endforeach
                    @if ($errors->has('spec'))
                        <div style="margin: -15px 0 10px 110px;">
                            <span class="adm-input-error">{{ $errors->first('spec') }}</span>
                        </div>
                    @endif
                @else
                    <div class="layui-form-item">
                        <label class="layui-form-label">销售规格名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="spec[0][name]"
                                   placeholder="请输入销售规格名" autocomplete="off" class="layui-input">
                        </div>

                        <label class="layui-form-label">规格属性值</label>
                        <div class="layui-input-inline">
                            <input type="text" name="spec[0][attr_values]"
                                   placeholder="请输入规格属性值" autocomplete="off" class="layui-input">
                        </div>
                        <div>
                            <button type="button" class="layui-btn" id="newSpec">新增</button>
                        </div>
                        @if ($errors->has('spec'))
                            <div style="margin-left: 110px;">
                                <span class="adm-input-error">{{ $errors->first('spec') }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="layui-form-item" id="tail">
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
