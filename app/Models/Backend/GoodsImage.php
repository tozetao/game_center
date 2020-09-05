<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class GoodsImage extends Model
{
    public static function allByGoodsId($goodsId)
    {
        return self::where('goods_id', $goodsId)->get();
    }
}
