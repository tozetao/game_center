<?php

namespace App\Models\Api\Search;

use App\Models\Api\Goods;

class GoodsSearch extends QueryFilter
{
    protected $orderParams = [
        'on_sale'
    ];

    public function search($conditions, $page)
    {
        $pageSize = 5;
        $query = $this->apply(Goods::query(), $conditions);
        $query = $this->paginate($query, $page, $pageSize);
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function total($conditions)
    {
        $query = $this->apply(Goods::query(), $conditions);
        return $query->count();
    }

    public function onSale($value)
    {
        $this->builder->where('on_sale', $value);
    }

}