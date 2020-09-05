/**
 * 定义页面与模块的依赖关系。
 * 我们会在页面定义一个input hidden，通过该input的值来找到依赖关系。
 * 例如：<input type="hidden" id="dependent" value="role">
 */

"use strict";

(function(){
    var dependent = {
        home: 'index',
        role: ['index', 'form', 'privilege'],
        administrator: ['index', 'form'],
        goods: ['index', 'form', 'upload', 'string', 'goods'],
        category: ['index', 'form', 'category'],
        order: ['index', 'form', 'order'],
        user_stat: ['index', 'form', 'laydate', 'user_stat'],
    };

    var ele = document.getElementById('dependent');

    if (ele == null) {
        console.log('No configuration element.');
        return;
    }

    var key = ele.value;

    if (!dependent.hasOwnProperty(key)) {
        console.log('No configuration dependencies.');
        return;
    }

    layui.config({
        version: true,
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(dependent[key]);
})();