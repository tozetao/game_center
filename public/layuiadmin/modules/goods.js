"use strict";

layui.define(['jquery', 'form', 'upload', 'string'], function(exports) {
    var upload = layui.upload;
    var form   = layui.form;
    var string = layui.string;
    var $      = layui.jquery;

    /********** goods.create.blade.php **********/
    var block = '<div class="layui-form-item"><label class="layui-form-label">%s</label><div class="layui-input-inline">%s</div>%s</div>';
    var selectTemplate = '<select name="%s" lay-filter="spec">%s</select>';
    var optionTemplate = '<option value="%s" %s>%s</option>';
    var customTemplate = '<div class="layui-input-inline"><input type="text" name="%s" value="%s" placeholder="自定义属性" class="layui-input"></div>';

    // 商品规格会根据商品类目而变化
    form.on('select(category_id)', function(data) {
        var params = {
            category_id: data.value
        };

        // 查询该类目的销售规格，然后根据商品原有的规格属性值来展示商品的销售规格
        $.get('/specification', params, function(data) {
            var buildHTML = '', selectHTML, optionHTML, customHTML;
            var oldSpec = $('#oldSpec').val() != '' ? JSON.parse($('#oldSpec').val()) : null;

            for (var i in data) {
                var item = data[i];
                var attrValues = JSON.parse(item.attr_values);

                // 已选择的销售规格的属性值
                var oldSpecAttr = (oldSpec != null && oldSpec[item.spec_id] != null) ? oldSpec[item.spec_id] : '';

                selectHTML = customHTML = '';
                optionHTML = string.sprintf('<option value="">请选择%s</option>', item.spec_name);

                for (var j in attrValues) {
                    if (oldSpec != null && oldSpecAttr == attrValues[j]) {
                        optionHTML += string.sprintf(optionTemplate, attrValues[j], ' selected="true"', attrValues[j]);
                    } else {
                        optionHTML += string.sprintf(optionTemplate, attrValues[j], '', attrValues[j]);
                    }
                }

                // 销售属性的字段：specification[1] = x, specification[2] = x,
                selectHTML = string.sprintf(selectTemplate, 'spec[' + item.spec_id + ']', optionHTML);
                customHTML = string.sprintf(customTemplate, 'spec[' + item.spec_id + ']', oldSpecAttr);
                buildHTML += string.sprintf(block, item.spec_name, selectHTML, customHTML);
            }

            $('#specificationBlock').empty().append(buildHTML);

            form.render(null, 'spec');
            changeSpecification();
        });
    });

    // 获取category select选中的值，然后主动触发事件。
    if ($('select[name="category_id"]').length != 0) {
        var categoryId = $('select[name="category_id"]').val();

        $('select[name="category_id"]')
            .next()
            .find('.layui-anim')
            .children(string.sprintf('dd[lay-value="%s"]', categoryId))
            .click();
    }

    // 销售属性值改变触发的事件
    function changeSpecification() {
        form.on('select(spec)', function (data) {
            $('input[name="' + data.elem.name + '"]').val(data.value);
        });
    }

    // 上传图片
    var imgIDs = ['#goods_img1', '#goods_img2', '#goods_img3', '#goods_img0'];

    for (var index in imgIDs) {
        upload.render({
            elem: imgIDs[index],

            url: '/upload/goods',

            data: {
                '_token': $('input[name="_token"]').val()
            },

            // 上传完毕处理
            done: function(data) {
                // 将商品的图片路径存储到hidden中
                var id  = this.elem.selector;
                var key = id.charAt(id.length - 1);

                var imageList = $('#image_list').val();

                if (imageList == '') {
                    imageList = {};
                } else {
                    imageList = JSON.parse(imageList);
                }

                imageList[key] = data.path;
                $('#image_list').val(JSON.stringify(imageList));

                // 显示图片
                $(id).attr('src', '/' + data.path);
            },

            // 请求异常处理
            error: function(err) {
                console.log(err);
            }
        });
    }

    exports('goods', {});
});


// 主动触发事件，然后去读取界面存放的数据，改变生成下拉框的值。
//$('select[name="category_id"]').next().find('.layui-select-title input').click();

//$('select[name="category_id"]').next().find('.layui-anim').children('dd[lay-value="1"]').click();