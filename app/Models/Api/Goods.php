<?php

namespace App\Models\Api;

use App\Filters\GoodsFilter;
use App\Models\Backend\GoodsImage;
use App\MOdels\Backend\GoodsSpecification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goods extends Model
{
    public $table = 'goods';

    protected $dateFormat = 'U';

    public function search(GoodsFilter $filter, $page, $pageSize = 10)
    {
        $query = $filter->apply($this->newQuery());

        $query->with(['images', 'goodsSpecs.specAttr'])
            ->where('on_sale', 1)
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize);

        return $query->get();
    }

    public function total(GoodsFilter $filter)
    {
        $query = $filter->apply($this->newQuery());

        return $query->count();
    }

    public function images()
    {
        return $this->hasMany(GoodsImage::class, 'goods_id');
    }

    public function goodsSpecs()
    {
        return $this->hasMany(GoodsSpecification::class, 'goods_id');
    }

    public static function findById($goodsId)
    {
        return self::with('goodsSpecs.specAttr')->where('id', $goodsId)->first();
    }

    public static function deductStock($goodsId, $quantity)
    {
        $affectedRows = DB::table('goods')
            ->where('id', $goodsId)
            ->where('stock', '>=', $quantity)
            ->decrement('stock', $quantity);

        // 若影响行数为0将视为失败
        return $affectedRows !== 0;
    }
}
