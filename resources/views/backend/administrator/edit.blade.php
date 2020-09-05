@extends('layouts/backend')

@section('title', '账号管理')

@section('content')
    <input type="hidden" id="dependent" value="administrator">

    <div class="layui-card">
        <div class="layui-card-header">
            新建账号
        </div>

        <div class="layui-card-body">
            <div style="margin-left: 110px;">
                @include('backend.layouts.message')
            </div>
            <form class="layui-form" action="{{ route('admin.update', ['id' => $admin->id]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">

                <div class="layui-row">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">账号名</label>
                            <div class="layui-input-block">
                                <input type="text" name="account" value="{{ $admin->account }}"
                                       placeholder="请输入账号名" autocomplete="off" class="layui-input">
                                @if ($errors->has('account'))
                                    <span class="adm-input-error">
                                        {{ $errors->first('account') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-row">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">角色选择</label>
                            <div class="layui-input-block">
                                <select name="role_id">
                                    <option value="">请选择一个角色</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @if($admin->role_id == $role->id) selected @endif>{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('role_id'))
                                    <span class="adm-input-error">
                                        {{ $errors->first('role_id') }}
                                    </span>
                                @endif
                            </div>
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