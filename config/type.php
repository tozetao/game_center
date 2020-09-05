<?php

/**
 * 整个应用所需要使用的类型
 */

return [
    // 支付状态
    'order' => [
        'unpaid'    => 1,           //未支付
        'paymented' => 2,           //已支付
        'failed'    => 3            //支付失败
    ],

    // 发货状态
    'delivery' => [
        'on' => 1,
        'off' => 0
    ],

    // 支付渠道
    'pay_channel' => [
        'wechat_jssdk' => 1,        //微信jssdk支付
        'points'       => 2         //商城积分支付
    ],

    // 上架状态
    'sale' => [
        'on'  => 1,
        'off' => 0
    ],

    // 商品知否支持积分兑换
    'is_exchange' => [
        'on' => 1,
        'off' => 0
    ],

    // 玩家基本设置的类型
    'user_info' => [
        'hair'    => 1,
        'clothes' => 2,
        'sex'     => 3
    ],

    // 性别
    'man' => 1,
    'women' => 2,

    'incr' => 1,
    'decr' => 2,

    'red_envelope' => [
        'unpaid' => 1,
        'paymented' => 2,
        'sent' => 3,
        'failed' => 4
    ],

    // 对象类型
    'obj' => [
        'order' => 1,
        'red_envelope' => 2,
        'prop_order' => 3
    ]
];