<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('goods')->truncate();
        DB::table('goods_specifications')->truncate();
        DB::table('goods_images')->truncate();

        $this->createGoods();
        $this->createPointGoods();
    }

    protected function createGoods()
    {
        // 女士睡衣
        $data = [
            'title' => '女士睡衣',
            'desc' => '女士睡衣',
            'image' => 'images/goods/f10.jpg',
            'price' => 10000,
            'discount_price' => 8000,
            'is_exchange' => 1,
            'points' => 5000,
            'on_sale' => 1,
            'category_id' => 1,
            'stock' => random_int(1000, 2000),
            'created_at' => time(),
            'goods_number' => str_shuffle('abcdefghijklmn123')
        ];

        $goodsId = DB::table('goods')->insertGetId($data);
        $specs = [
            [
                'goods_id' => $goodsId,
                'attr_val' => 'S码',
                'spec_id'  => 1,
            ],
            [
                'goods_id' => $goodsId,
                'attr_val' => '红色',
                'spec_id'  => 2,
            ]
        ];
        DB::table('goods_specifications')->insert($specs);

        $goodsImage = [
            'goods_id' => $goodsId,
            'path' => 'images/goods/f11.jpg'
        ];
        DB::table('goods_images')->insert($goodsImage);

        // 鞋子
        $data = [
            'title' => '帆布鞋',
            'desc' => '帆布鞋',
            'image' => 'images/goods/x10.jpg',
            'price' => 5000,
            'discount_price' => 5000,
            'is_exchange' => 1,
            'points' => 0,
            'on_sale' => 1,
            'category_id' => 3,
            'stock' => random_int(1000, 2000),
            'created_at' => time(),
            'goods_number' => str_shuffle('abcdefghijklmn123')
        ];

        $goodsId = DB::table('goods')->insertGetId($data);
        $specs = [
            [
                'goods_id' => $goodsId,
                'attr_val' => '36码',
                'spec_id'  => 4,
            ]
        ];
        DB::table('goods_specifications')->insert($specs);

        $goodsImage = [
            'goods_id' => $goodsId,
            'path' => 'images/goods/x11.jpg'
        ];
        DB::table('goods_images')->insert($goodsImage);

        // 饰品
        $data = [
            'title' => '翡翠项链',
            'desc' => '翡翠项链',
            'image' => 'images/goods/s10.jpg',
            'price' => 8000,
            'discount_price' => 5000,
            'is_exchange' => 1,
            'points' => 1000,
            'on_sale' => 1,
            'category_id' => 2,
            'stock' => random_int(1000, 2000),
            'created_at' => time(),
            'goods_number' => str_shuffle('abcdefghijklmn123')
        ];

        $goodsId = DB::table('goods')->insertGetId($data);
        $specs = [
            [
                'goods_id' => $goodsId,
                'attr_val' => '银色',
                'spec_id'  => 3,
            ]
        ];
        DB::table('goods_specifications')->insert($specs);

        $goodsImage = [
            'goods_id' => $goodsId,
            'path' => 'images/goods/s11.jpg'
        ];
        DB::table('goods_images')->insert($goodsImage);
    }

    protected function createPointGoods()
    {
        // 女士睡衣
        $data = [
            'title' => '女士睡衣',
            'desc' => '女士睡衣',
            'image' => 'images/goods/f10.jpg',
            'price' => 10000,
            'discount_price' => 8000,
            'is_exchange' => 0,
            'points' => 5000,
            'on_sale' => 1,
            'category_id' => 1,
            'stock' => random_int(1000, 2000),
            'created_at' => time(),
            'goods_number' => str_shuffle('abcdefghijklmn123')
        ];

        $goodsId = DB::table('goods')->insertGetId($data);
        $specs = [
            [
                'goods_id' => $goodsId,
                'attr_val' => 'S码',
                'spec_id'  => 1,
            ],
            [
                'goods_id' => $goodsId,
                'attr_val' => '红色',
                'spec_id'  => 2,
            ]
        ];
        DB::table('goods_specifications')->insert($specs);

        $goodsImage = [
            'goods_id' => $goodsId,
            'path' => 'images/goods/f11.jpg'
        ];
        DB::table('goods_images')->insert($goodsImage);

        // 鞋子
        $data = [
            'title' => '帆布鞋',
            'desc' => '帆布鞋',
            'image' => 'images/goods/x10.jpg',
            'price' => 5000,
            'discount_price' => 5000,
            'is_exchange' => 0,
            'points' => 0,
            'on_sale' => 1,
            'category_id' => 3,
            'stock' => random_int(1000, 2000),
            'created_at' => time(),
            'goods_number' => str_shuffle('abcdefghijklmn123')
        ];

        $goodsId = DB::table('goods')->insertGetId($data);
        $specs = [
            [
                'goods_id' => $goodsId,
                'attr_val' => '36码',
                'spec_id'  => 4,
            ]
        ];
        DB::table('goods_specifications')->insert($specs);

        $goodsImage = [
            'goods_id' => $goodsId,
            'path' => 'images/goods/x11.jpg'
        ];
        DB::table('goods_images')->insert($goodsImage);

        // 饰品
        $data = [
            'title' => '翡翠项链',
            'desc' => '翡翠项链',
            'image' => 'images/goods/s10.jpg',
            'price' => 8000,
            'discount_price' => 5000,
            'is_exchange' => 0,
            'points' => 1000,
            'on_sale' => 1,
            'category_id' => 2,
            'stock' => random_int(1000, 2000),
            'created_at' => time(),
            'goods_number' => str_shuffle('abcdefghijklmn123')
        ];

        $goodsId = DB::table('goods')->insertGetId($data);
        $specs = [
            [
                'goods_id' => $goodsId,
                'attr_val' => '银色',
                'spec_id'  => 3,
            ]
        ];
        DB::table('goods_specifications')->insert($specs);

        $goodsImage = [
            'goods_id' => $goodsId,
            'path' => 'images/goods/s11.jpg'
        ];
        DB::table('goods_images')->insert($goodsImage);
    }
}

/*

-- select * from goods;
-- select * from goods_specifications;
-- select * from goods_images;

-- truncate goods;
-- truncate goods_images;
-- truncate goods_specifications;

truncate categories;
truncate specification_attrs;
truncate goods;
truncate goods_images;
truncate goods_specifications;

导入桌面数据内容

 */