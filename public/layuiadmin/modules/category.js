"use strict";

layui.define(['jquery', 'form', 'string'], function(exports) {
    var $ = layui.jquery;
    var string = layui.string;

    var clicks = $('#clicks').length == 0 ? 0 : $('#clicks').val();
    var specTemplate = '<div class="layui-form-item"><label class="layui-form-label">销售规格名</label><div class="layui-input-inline"><input type="text" name="spec[%d][name]" placeholder="请输入销售规格名" autocomplete="off" class="layui-input"></div><label class="layui-form-label">规格属性值</label><div class="layui-input-inline"><input type="text" name="spec[%d][attr_values]" placeholder="请输入规格属性值" autocomplete="off" class="layui-input"></div><div><button type="button" class="layui-btn" data-remove>移除</button></div></div>';

    console.log(clicks);

    $('#newSpec').on('click', function() {
        clicks++;
        $('#tail').prepend(string.sprintf(specTemplate, clicks, clicks));
    });

    $('#categoryForm').on('click', 'button[data-remove]', function(){
        clicks--;
        $(this).parent().parent().remove();
    })

    exports('category', {})
});