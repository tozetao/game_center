

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>等比例列表排列</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('layuiadmin/layui/css/layui.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('layuiadmin/style/admin.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    <div style="margin-left: 100px; margin-top: 50px; background: #FFFFFF;">
        <br>
        <form action="" class="layui-form">
            <fieldset class="adm-legend">
                <legend class="adm-legend-title">
                    <input type="checkbox" name="" title="应用" lay-skin="primary">
                </legend>

                <fieldset class="adm-legend" style="margin: 10px 20px;">
                    <legend class="adm-legend-title">
                        <input type="checkbox" name="" title="内容系统" lay-skin="primary">
                    </legend>

                    <fieldset class="adm-legend" style="margin: 10px 20px;">
                        <legend class="adm-legend-title">
                            <input type="checkbox" name="" title="文章模块" lay-skin="primary">
                        </legend>

                        <div style="margin: 10px 30px;">
                            <input type="checkbox" name="" title="文章列表" lay-skin="primary">
                            <input type="checkbox" name="" title="新增文章" lay-skin="primary">
                            <input type="checkbox" name="" title="编辑文章" lay-skin="primary">
                        </div>
                    </fieldset>

                    <fieldset class="adm-legend" style="margin: 10px 20px;">
                        <legend class="adm-legend-title">
                            <input type="checkbox" name="" title="文章模块" lay-skin="primary">
                        </legend>

                        <div style="margin: 10px 30px;">
                            <input type="checkbox" name="" title="文章列表" lay-skin="primary">
                            <input type="checkbox" name="" title="新增文章" lay-skin="primary">
                            <input type="checkbox" name="" title="编辑文章" lay-skin="primary">
                        </div>
                    </fieldset>
                </fieldset>
            </fieldset>

            <fieldset class="adm-legend">
                <legend class="adm-legend-title">
                    <input type="checkbox" name="" title="应用" lay-skin="primary">
                </legend>

                <div style="margin: 10px 30px;">
                    <input type="checkbox" name="" title="写作" lay-skin="primary">
                    <input type="checkbox" name="" title="发呆" lay-skin="primary">
                    <input type="checkbox" name="" title="禁用" lay-skin="primary">
                </div>
            </fieldset>

            <fieldset class="adm-legend">
                <legend class="adm-legend-title">
                    <input type="checkbox" name="" title="应用" lay-skin="primary">
                </legend>

                <div style="margin: 10px 30px;">
                    <input type="checkbox" name="" title="写作" lay-skin="primary">
                    <input type="checkbox" name="" title="发呆" lay-skin="primary">
                    <input type="checkbox" name="" title="禁用" lay-skin="primary">
                </div>
            </fieldset>

            <fieldset class="adm-legend">
                <legend class="adm-legend-title">
                    <input type="checkbox" name="" title="应用" lay-skin="primary">
                </legend>

                <fieldset class="adm-legend" style="margin: 10px 20px;">
                    <legend class="adm-legend-title">
                        <input type="checkbox" name="" title="内容系统" lay-skin="primary">
                    </legend>

                    <div style="margin: 10px 30px;">
                        <input type="checkbox" name="" title="文章列表" lay-skin="primary">
                        <input type="checkbox" name="" title="新增文章" lay-skin="primary">
                        <input type="checkbox" name="" title="编辑文章" lay-skin="primary">
                    </div>
                </fieldset>

                <fieldset class="adm-legend" style="margin: 10px 20px;">
                    <legend class="adm-legend-title">
                        <input type="checkbox" name="" title="内容系统" lay-skin="primary">
                    </legend>

                    <div style="margin: 10px 30px;">
                        <input type="checkbox" name="" title="文章列表" lay-skin="primary">
                        <input type="checkbox" name="" title="新增文章" lay-skin="primary">
                        <input type="checkbox" name="" title="编辑文章" lay-skin="primary">
                    </div>
                </fieldset>

                <fieldset class="adm-legend" style="margin: 10px 20px;">
                    <legend class="adm-legend-title">
                        <input type="checkbox" name="" title="内容系统" lay-skin="primary">
                    </legend>

                    <div style="margin: 10px 30px;">
                        <input type="checkbox" name="" title="文章列表" lay-skin="primary">
                        <input type="checkbox" name="" title="新增文章" lay-skin="primary">
                        <input type="checkbox" name="" title="编辑文章" lay-skin="primary">
                    </div>
                </fieldset>

                <fieldset class="adm-legend" style="margin: 10px 20px;">
                    <legend class="adm-legend-title">
                        <input type="checkbox" name="" title="内容系统" lay-skin="primary">
                    </legend>

                    <div style="margin: 10px 30px;">
                        <input type="checkbox" name="" title="文章列表" lay-skin="primary">
                        <input type="checkbox" name="" title="新增文章" lay-skin="primary">
                        <input type="checkbox" name="" title="编辑文章" lay-skin="primary">
                    </div>
                </fieldset>
            </fieldset>
        </form>
        <br>
    </div>

    <button id="test" type="button" class="layui-btn">按钮二</button>
</body>
</html>
<script src="{{ asset('layuiadmin/layui/layui.js') }}"></script>
<script>
    layui.config({
        base: '/layuiadmin/' //扩展layui模块的所在目录。
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'privilege']);
</script>