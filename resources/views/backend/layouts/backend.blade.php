<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理 - @yield('title')</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('layuiadmin/layui/css/layui.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('layuiadmin/style/admin.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/admin.css?time=') . time() }}" media="all">
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">@yield('content')</div>
    </div>
</div>
</body>
</html>
<script src="{{ asset('layuiadmin/layui/layui.js') }}"></script>
<script src="{{ asset('layuiadmin/dependent.js?time=' . time()) }}"></script>