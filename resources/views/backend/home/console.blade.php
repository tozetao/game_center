@extends('layouts.backend')

@section('title', '控制台')

@section('content')
    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="layui-card-header">欢迎，管理员!</div>
            <div class="layui-card-body layui-text">
                <table class="layui-table">
                    <colgroup>
                        <col width="150">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <td>当前版本</td>
                        <td>
                            1.0
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection