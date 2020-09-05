@extends('layouts.backend')

@section('title', '账号列表')

@section('content')
    <input type="hidden" id="dependent" value="administrator">

    <div class="layui-card">
        <div class="layui-card-header">
            账号列表
        </div>
        <div class="layui-card-body">
            <div>
                {{-- 分页 --}}
                {{ $administrators->links() }}
            </div>

            <form action="{{ route('admin.index') }}" method="get" class="layui-form">
                <div style="margin-bottom: 10px;">
                    搜索账号：
                    <div class="layui-inline">
                        <input class="layui-input" name="account" autocomplete="off" value="{{ request()->get('account') }}">
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label">账号类型</label>
                        <div class="layui-input-inline">
                            <select name="type" class="layui-select">
                                <option value="1" @if(request()->get('type') == 1) selected @endif>我创建的账号</option>
                                <option value="2" @if(request()->get('type') == 2) selected @endif>所有子账号</option>
                            </select>
                        </div>
                    </div>

                    <button class="layui-btn">搜索</button>
                    <a class="layui-btn adm-float-right" lay-href="{{ route('admin.create') }}">新建账号</a>
                </div>
            </form>

            <table class="layui-table">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>账号名</th>
                    <th>角色名</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($administrators as $model)
                <tr>
                    <th>{{ $model->id }}</th>
                    <td>{{ $model->account }}</td>
                    <td>角色名</td>
                    <td>{{ date('Y-m-d h:i:s', $model->created_at) }}</td>
                    <td>
                        @if($model->creator_id == Auth::guard('backend')->id())
                            <a class="layui-btn layui-btn-normal layui-btn-xs" lay-href="{{ route('admin.edit', ['id' => $model->id]) }}">
                                <i class="layui-icon layui-icon-edit"></i>编辑账号
                            </a>
                        @else
                            <a class="layui-btn layui-btn-normal layui-btn-xs" lay-href="{{ route('role.edit', ['role_id' => $model->role->id]) }}">
                                <i class="layui-icon layui-icon-edit"></i>编辑权限
                            </a>
                        @endif
                    </td>
                </tr>
                </tbody>
                @endforeach
            </table>
        </div>
    </div>
@endsection