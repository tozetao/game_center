<?php

namespace App\Filters;

use App\Models\Api\Goods;

class GoodsFilter extends BaseFilter
{
    // 商品分类处理
    public function categoryId($value)
    {
        $this->builder->where('category_id', $value);
    }

    // 支付类型的处理
    public function isExchange($value)
    {
        $this->builder->where('is_exchange', $value);
    }

    // 商品关键字搜索
    public function title($value)
    {
        $this->builder->where('title', 'like', "%{$value}%");
    }

    public function onSale($value)
    {
        $this->builder->where('on_sale', $value);
    }
}