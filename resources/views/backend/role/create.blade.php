@extends('layouts.backend')

@section('title', '新建角色')

@section('content')
    <input type="hidden" id="dependent" value="role">

    <div class="layui-card">
        <div class="layui-card-header">
            新建角色
        </div>

        <div class="layui-card-body">
            <div style="margin-left: 110px;">
                @include('backend.layouts.message')

                @if (count($errors) > 0)
                    <div class="adm-alert adm-alert-warning">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <form class="layui-form" action="{{ route('role.store') }}" method="post">
                {{ csrf_field() }}

                <div class="layui-form-item">
                    <label class="layui-form-label">角色名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" placeholder="请输入标题"
                               autocomplete="off" class="layui-input" value="{{ old('name') }}">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">权限选择</label>
                    <div class="layui-input-block">
                        {!! $privilegeTree !!}
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <div class="layui-footer" style="left: 0;">
                            <button class="layui-btn">提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
