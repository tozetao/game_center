<?php

$config = config('privilege');

return [
    [
        'name' => '主页',
        'icon' => 'layui-icon-home',
        'children' => [
            [
                'name' => '控制台',
                'privilege' => $config['home.console'],
                'route' => 'console',
            ]
        ]
    ],
    [
        'name' => '系统管理',
        'icon' => 'layui-icon-set',
        'children' => [
            [
                'name' => '角色管理',
                'privilege' => $config['role.index'],
                'route' => 'role.index',
            ],
            [
                'name' => '账号管理',
                'privilege' => $config['administrator.index'],
                'route' => 'admin.index',
            ]
        ]
    ],
    [
        'name' => '商品管理',
        'icon' => 'layui-icon-cart-simple',
        'privilege' => $config['goods.index'],
        'route' => 'goods.index'
    ],

    [
        'name' => '商品分类管理',
        'icon' => 'layui-icon-list',
        'privilege' => $config['category.index'],
        'route' => 'category.index'
    ],

    [
        'name' => '订单管理',
        'icon' => 'layui-icon-list',
        'children' => [
            [
                'name' => '商品订单',
                'privilege' => $config['order.index'],
                'route' => 'order.index',
            ],
            [
                'name' => '游戏道具',
                'privilege' => $config['order.virtual'],
                'route' => 'order.virtual',
            ]
        ]
    ],

    [
        'name' => '数据分析',
        'icon' => 'layui-icon-list',
        'children' => [
            [
                'name' => '用户数分析',
                'privilege' => $config['userstat.number'],
                'route' => 'user_stat.number',
            ],
            [
                'name' => '留存统计',
                'privilege' => $config['userstat.keep'],
                'route' => 'user_stat.keep',
            ]
        ]
    ],


    /*[
        'name' => '应用',
        'icon' => 'layui-icon-app',
        'children' => [
            [
                'name' => '内容系统',
                'children' => [
                    [
                        'name' => '文章列表',
                        'privilege' => 'wenzhang',
                        'route' => '/wenzhang'
                    ],
                    [
                        'name' => '分类列表',
                        'privilege' => 'fenlei',
                        'route' => '/fenlei'
                    ],
                    [
                        'name' => '评论管理',
                        'privilege' => 'pinglun',
                        'route' => '/pinglun'
                    ]
                ],
            ],
            [
                'name' => '消息中心',
                'privilege' => 'xiaoxi',
                'route' => '/xiaoxi'
            ]
        ]
    ]*/
];