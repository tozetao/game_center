"use strict";

/**
 * 选中一个节点会发生俩种情况：
 *
 * 如果该节点是一个父节点，需要改变所有孙子节点的状态；
 *
 * 该节点的所有兄弟节点的状态一致时，需要改变其父节点的状态。
 * 接着需要判断父节点的所有兄弟节点的状态，以此改变父节点的父节点状态。以此类推，直到碰到表单节点。
 */

layui.define(['form', 'jquery'], function(exports) {
    var form = layui.form;
    var $ = layui.jquery;

    var LEGEND = 'LEGEND';
    var FIELDSET = 'FIELDSET';
    var FORM = 'FORM';

    // 更改trigger的checkbox子节点状态
    function changeChildren(trigger, flag) {
        var eleList = [];

        $(trigger).parent().nextAll().each(function() {
            eleList.push(this);
        });

        while(eleList.length != 0) {
            var ele = eleList.pop();

            if (ele.tagName == FIELDSET) {
                // 如果是fieldset元素，改变第一个子节点包含的checkbox的状态，再将所有checkbox子节点元素加入到队列中。
                $(ele).children().first().children(':input').prop('checked', flag);

                $(ele).children(':not(legend)').each(function() {
                    eleList.push(this);
                });
            } else {
                // 如果不是fieldset元素，默认是div元素，即叶子节点，那么将改变div中所有checkbox的状态
                $(ele).children(':input').prop('checked', flag);
            }
        }

    }

    // 联级动态父节点的状态
    function changeParents(ele) {
        var trigger = $(ele);

        var flag;
        var parent;

        // trigger触发事件的元素，即checkbox。
        while (trigger.length == 1) {
            parent = trigger.parent();
            flag   = trigger.prop('checked');

            if (parent.get(0).tagName == LEGEND) {
                // fieldset节点
                parent = parent.parent()

                // 遍历所有fieldset节点首个checkbox的值
                var loopFieldset = function(eles) {
                    eles.each(function() {
                        if (!$(this).children(':first').children(':input').prop('checked')) {
                            flag = false;
                            return false;
                        }
                    });
                };

                loopFieldset(parent.prevAll(':not(legend)'));
                loopFieldset(parent.nextAll());

                // 指向父节点的checkbox节点，fieldset元素的checkbox节点
                trigger = parent.parent().children(':first').children(':input');
                trigger.prop('checked', flag);
            } else {
                var loopDiv = function(eles) {
                    eles.each(function() {
                        if (!this.checked) {
                            flag = false;
                            return false;
                        }
                    });
                };

                loopDiv(trigger.prevAll(':input'));
                loopDiv(trigger.nextAll(':input'));

                // 使trigger指向父节点的checkbox节点
                trigger = parent.prev().children(':input');

                // 改变父节点checkbox节点的状态
                trigger.prop('checked', flag);
            }
        }
    }

    // 绑定表单的checkbox事件
    form.on('checkbox', function(data) {
        changeChildren(data.elem, data.elem.checked);
        changeParents(data.elem);
        form.render('checkbox');
    });

    exports('privilege', {})
});