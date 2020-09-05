<?php
/**
 * 权限复选框，在添加、编辑角色页面，勾选角色的权限时会使用到。
 */

$config = config('privilege');

return [
    [
        'name' => '主页',
        'privilege_list' => [
            [
                'name' => '控制台',
                'privilege' => $config['home.console']
            ]
        ]
    ],
    [
        'name' => '系统管理',
        'children' => [
            [
                'name' => '角色管理',
                'privilege_list' => [
                    [
                        'name' => '角色列表',
                        'privilege' => $config['role.index']
                    ],
                    [
                        'name' => '新建角色',
                        'privilege' => $config['role.store']
                    ],
                    [
                        'name' => '编辑角色',
                        'privilege' => $config['role.update']
                    ],
                    [
                        'name' => '删除角色',
                        'privilege' => $config['role.delete']
                    ]
                ]
            ],
            [
                'name' => '账号管理',
                'privilege_list' => [
                    [
                        'name' => '账号列表',
                        'privilege' => $config['administrator.index']
                    ],
                    [
                        'name' => '新建账号',
                        'privilege' => $config['administrator.store']
                    ],
                    [
                        'name' => '编辑账号',
                        'privilege' => $config['administrator.update']
                    ],
                    [
                        'name' => '删除账号',
                        'privilege' => $config['administrator.delete']
                    ]
                ]
            ]
        ]
    ],

    [
        'name' => '商品管理',
        'privilege_list' => [
            [
                'name' => '商品列表',
                'privilege' => $config['goods.index']
            ],
            [
                'name' => '新建商品',
                'privilege' => $config['goods.store'],
            ],
            [
                'name' => '编辑商品',
                'privilege' => $config['goods.update'],
            ],
            [
                'name' => '删除商品',
                'privilege' => $config['goods.delete'],
            ]
        ]
    ],

    [
        'name' => '商品类目管理',
        'privilege_list' => [
            [
                'name' => '分类列表',
                'privilege' => $config['category.index'],
            ],
            [
                'name' => '新建类目',
                'privilege' => $config['category.store'],
            ],
            [
                'name' => '编辑类目',
                'privilege' => $config['category.update'],
            ]
        ]
    ],

    [
        'name' => '订单管理',
        'privilege_list' => [
            [
                'name' => '商品订单管理',
                'privilege' => $config['order.index']
            ],
            [
                'name' => '游戏道具订单管理',
                'privilege' => $config['order.virtual']
            ],
            [
                'name' => '商品发货',
                'privilege' => $config['order.delivery']
            ]
        ]
    ],

    [
        'name' => '数据分析',
        'privilege_list' => [
            [
                'name' => '玩家数分析',
                'privilege' => $config['userstat.number']
            ],
            [
                'name' => '玩家留存分析',
                'privilege' => $config['userstat.keep']
            ],
        ]
    ]

];

/*
    [
        'name' => '应用',
        'children' => [
            [
                'name' => '内容系统',
                'children' => [
                    [
                        'name' => '文章',
                        'privilege_list' => [
                            [
                                'name' => '文章列表',
                                'privilege' => 'wenzhang',
                            ],
                            [
                                'name' => '新增文章',
                                'privilege' => 'add_wenzhang',
                            ],
                            [
                                'name' => '编辑文章',
                                'privilege' => 'edit_wenzhang',
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
*/