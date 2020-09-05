@extends('layouts.backend')

@section('title', '玩家统计')

@section('content')
    <input type="hidden" id="dependent" value="user_stat">
    <input type="hidden" id="data">

    <div class="layui-card">
        <form action="">
            <div class="layui-card-body" pad15>
                <div class="layui-form" wid100>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">搜索日期</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" id="test-laydate-start" placeholder="开始日期">
                            </div>
                            <div class="layui-form-mid">
                                -
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" id="test-laydate-end" placeholder="结束日期">
                            </div>

                            <div class="layui-inline">
                                <button class="layui-btn layuiadmin-btn-list" lay-submit>
                                    <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{--<div style="text-align: center; height: 300px;">
            <h3>
                暂时没有数据
            </h3>
        </div>--}}

        <div id="main" style="height: 500px;"></div>
    </div>
@endsection

{{--
可以根据时间段来进行选择。
1. 柱状图显示玩家活跃数量
2. 柱状图显示玩家注册数量
 --}}