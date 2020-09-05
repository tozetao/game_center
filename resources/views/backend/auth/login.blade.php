<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登入 - layuiAdmin</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('layuiadmin/layui/css/layui.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('layuiadmin/style/admin.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('layuiadmin/style/login.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" media="all">
</head>
<body>

<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>layuiAdmin</h2>
        </div>

        <div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <form action="{{ route('login') }}" method="post">
            {{ csrf_field() }}

            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                    <input type="text" name="account" id="LAY-user-login-username" placeholder="用户名" class="layui-input"
                        value="{{ old('account') }}">
                    @if($errors->has('account'))
                        <div class="adm-input-error">
                            {{ $errors->first('account') }}
                        </div>
                    @endif
                </div>

                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                    <input type="password" name="password" id="LAY-user-login-password" placeholder="密码" class="layui-input">
                    @if($errors->has('password'))
                        <div class="adm-input-error">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid">登 入</button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>
<script src="{{ asset('layuiadmin/layui/layui.js') }}"></script>