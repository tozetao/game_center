<?php

use Faker\Generator as Faker;
use App\Models\Backend\Goods;

$factory->define(Goods::class, function (Faker $faker) {

    $categoryId = random_int(1, 3);

    $images = [
        1 => ['t1', 't2'],
        2 => ['s1', 's2'],
        3 => ['x1', 'x2']
    ];

    $isConvertible = random_int(0, 1);

    $points = $isConvertible == 0 ? 0 : random_int(1000, 2000);

    return [
        'title' => '测试商品' . $faker->name,
        'desc' => $faker->address,
        'image' => 'images/goods/' . $images[$categoryId][random_int(0, 1)] . '.jpg',
        'price' => random_int(500, 1000),
        'discount_price' => random_int(100, 500),
        'is_convertible' => $isConvertible,
        'points' => $points,
        'on_sale' => 1,
        'category_id' => $categoryId,
        'stock' => random_int(1000, 2000),
        'created_at' => time(),
        'goods_number' => str_shuffle('abcdefghijklmn123')
    ];
});
