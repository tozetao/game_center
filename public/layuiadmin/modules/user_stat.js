layui.define(['jquery', 'laydate', 'echarts'], function (exports) {
    var echarts = layui.echarts;
    var laydate = layui.laydate;
    var $ = layui.jquery;

    var insStart = laydate.render({
        elem: '#test-laydate-start'
    });

    //结束日期
    var insEnd = laydate.render({
        elem: '#test-laydate-end'
    });

    console.log($('#data'));
    console.log($);

    var myChart = echarts.init(document.getElementById('main'));

    // 指定图表的配置项和数据
    var option = {
        legend: {
            data:['销量', '数量']
        },
        xAxis: {
            data: ["2019-1-1", "2019-1-2", "2019-1-3", "2019-1-4", "2019-1-5", "2019-1-6"],
        },
        yAxis: {},
        series: [
            {
                name: '销量',
                type: 'bar',
                data: [5, 20, 36, 10, 10, 20]
            },
            {
                name: '数量',
                type: 'bar',
                data: [15, 120, 136, 110, 110, 120]
            },
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);

    exports('user_stat', {});
});