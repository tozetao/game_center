<?php

namespace App\Models\Backend;

use App\Filters\OrderFilter;
use App\Models\Api\OrderAddr;
use App\Models\Api\OrderSpecification;
use App\Services\Converter\RMBConverter;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $dateFormat = 'U';
    public $primaryKey = 'order_id';

    // 商品订单
    public function getEntity(OrderFilter $filter)
    {
        $query = $this->newQuery();

        $query->with(['orderSpecifications', 'orderAddr'])
            ->orderBy($this->primaryKey, 'desc');

        $query = $filter->apply($query);

        return $query->paginate(config('app.page_size'));
    }

    // 游戏道具订单查询
    public function getVirtual(OrderFilter $filter)
    {
        $query = $this->newQuery();

        $query->orderBy($this->primaryKey, 'desc');

        $filter->apply($query);

        return $query->paginate(config('app.page_size'));
    }

    // 发货
    public function deliver($expressName, $expressNo)
    {
        $this->express_name   = $expressName;
        $this->express_number = $expressNo;
        $this->delivery_status = config('type.delivery.on');
        return $this->save();
    }

    public function scopeFilter($query, OrderFilter $filter)
    {
        return $filter->apply($query);
    }

    // 关联的规格属性
    public function orderSpecifications()
    {
        return $this->hasMany(OrderSpecification::class, 'order_id');
    }

    // 关联的订单地址
    public function orderAddr()
    {
        return $this->hasOne(OrderAddr::class, 'order_id');
    }

    public function translateDiscountPrice()
    {
        return RMBConverter::fentoyuan($this->discount_price, 2);
    }

    // 翻译支付渠道
    public function translatePaymentType()
    {
        $map = config('typemapping.pay_channel');

        if (isset($map[$this->payment_type])) {
            return $map[$this->payment_type];
        }

        return 'undefine';
    }

    // 订单订单状态
    public function translateStatus()
    {
        $map = config('typemapping.pay_status');

        if (isset($map[$this->status])) {
            return $map[$this->status];
        }

        return 'undefine';
    }

    // 发货状态
    public function translteDeliveryStatus()
    {
        $map = config('typemapping.delivery');

        if (isset($map[$this->delivery_status])) {
            return $map[$this->delivery_status];
        }

        return 'undefine';
    }

    public function translateIsExchange()
    {
        return $this->is_exchange == config('type.is_exchange.on') ? '是' : '否';
    }

    public function translateSpecifications()
    {
        $text = '';

        foreach ($this->orderSpecifications as $model) {
            $text .= $model->spec_name . '：' . $model->attr_val . '<br/>';
        }

        return $text;
    }
}
