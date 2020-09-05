@extends('layouts.backend')

@section('title', '角色列表')

@section('content')
    <input type="hidden" id="dependent" value="role">

    <div class="layui-card">
    <div class="layui-card-header">
        角色列表
    </div>
    <div class="layui-card-body">
        <form action="{{ route('role.index') }}" class="layui-form" method="get">
            <div style="margin-bottom: 10px;">
                搜索角色：
                <div class="layui-inline">
                    <input class="layui-input" name="name" autocomplete="off" value="{{ request()->get('name') }}">
                </div>
                <button class="layui-btn">搜索</button>
                <a class="layui-btn adm-float-right" lay-href="{{ route('role.create') }}">新建角色</a>
            </div>
        </form>

        <table class="layui-table">
            <colgroup>
                <col width="150">
                <col width="200">
                <col width="200">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>ID</th>
                <th>角色名</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <th>{{ $role->id }}</th>
                    <td>{{ $role->name }}</td>
                    <td>{{ date('Y-m-d h:i:s', $role->created_at) }}</td>
                    <td>
                        <a class="layui-btn layui-btn-normal layui-btn-xs"
                           lay-href="{{ route('role.edit', ['role_id' => $role->id]) }}">
                            <i class="layui-icon layui-icon-edit"></i>编辑
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $roles->links() }}
        {{--
        <div>
            <div class="layui-box layui-laypage layui-laypage-default">
                <a href="javascript:;" class="layui-laypage-prev">
                    <i class="layui-icon"></i></a>
                <a href="javascript:;" class="layui-laypage-first">1</a>
                <span class="layui-laypage-spr">…</span><a href="javascript:;" data-page="33">33</a>
                <span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>34</em></span>
                <a href="javascript:;" data-page="35">35</a><span class="layui-laypage-spr">…</span>
                <a href="javascript:;" class="layui-laypage-last">100</a>
                <a href="javascript:;" class="layui-laypage-next"><i class="layui-icon"></i></a>
            </div>
        </div>
        --}}
    </div>
</div>
@endsection