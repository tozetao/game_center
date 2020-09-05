"use strict";

layui.define(['jquery', 'layer', 'string'], function(exports) {
    var $ = layui.jquery;
    var layer = layui.layer;

    var content = '';

    // 绑定发货事件
    $('a[data-order-id]').on('click', function() {
        var orderId = $(this).attr('data-order-id');

        layer.open({
            type: 1,

            title: orderId + '的订单',

            content: 'hi!<br/>fuck.',

            btn: ['yes', 'no'],

            yes: function() {
                console.log('yes');
            },

            cancel: function() {
                console.log('cancel');
            }
        });
    });

    exports('order', {})
});