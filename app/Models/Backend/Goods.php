<?php

namespace App\Models\Backend;

use App\Filters\GoodsFilter;
use App\Services\Converter\RMBConverter;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    public $table = 'goods';

    protected $dateFormat = 'U';

    public $fillable = [
        'title', 'desc', 'price', 'discount_price', 'stock', 'category_id', 'goods_number'
    ];

    public function translateOnSale()
    {
        return $this->on_sale == config('type.sale.on') ? '上架' : '下架';
    }

    public function translateIsConvertible()
    {
        return $this->is_exchange == config('type.is_exchange.on') ? '可兑换' : '不可兑换';
    }

    public function translatePrice()
    {
        return RMBConverter::fentoyuan($this->price, 2);
    }

    public function translateDiscountPrice()
    {
        return RMBConverter::fentoyuan($this->discount_price, 2);
    }

    // 屏蔽插入updated_at字段
    public function setUpdatedAt($value)
    {
        return $this;
    }

    // 屏蔽更新updated_at字段
    public function getUpdatedAtColumn() {
        return null;
    }

    public function scopeFilter($query, GoodsFilter $filter)
    {
        return $filter->apply($query);
    }
}
