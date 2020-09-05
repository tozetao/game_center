<?php

namespace App\MOdels\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\SpecificationAttr;

class GoodsSpecification extends Model
{
    public static function allByGoodsId($goodsId)
    {
        return self::where('goods_id', $goodsId)->get();
    }

    // 销售属性 对应的 销售规格
    public function specAttr()
    {
        return $this->belongsTo(SpecificationAttr::class, 'spec_id', 'spec_id');
    }
}
