<?php

namespace App\Models\Api;

use App\Filters\OrderFilter;
use App\Services\Pay\Unify;
use Illuminate\Database\Eloquent\Model;

class Order extends Model implements Unify
{
    protected $dateFormat = 'U';

    public $primaryKey = 'order_id';

    public $fillable   = [
        'uid',
        'goods_id',
        'order_number',
        'title',
        'image',
        'price',
        'discount_price',
        'points',
        'category_id',
        'quantity',
        'payment_type',
    ];

    // 订单总金额，单位分
    public function getTotalFee()
    {
        return bcmul($this->discount_price, $this->quantity, 0);
    }

    public function getBody()
    {
        return $this->title;
    }

    public function getNumber()
    {
        return $this->order_number;
    }

    public function createOrder($data)
    {
        $this->fill($data);
        $this->paid_at      = 0;
        $this->status       = config('type.order.unpaid');
        $this->express_name = '';
        $this->express_number  = '';
        $this->delivery_status = 0;
        return $this->save();
    }

    // 支付订单，更新支付渠道、支付时间、支付状态。
    public function pay($paymentType)
    {
        $this->payment_type = $paymentType;
        $this->paid_at = time();
        $this->status  = config('type.order.paymented');
        return $this->save();
    }

    // 支付失败
    public function failed()
    {
        $this->status = config('type.order.failed');
        return $this->save();
    }

    public function search(OrderFilter $filter, $page, $pageSize = 10)
    {
        $query = $filter->apply($this->newQuery());

        return $query->with('orderSpecs')
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->get();
    }

    public static function findByOrderNo($orderNumber)
    {
        return self::where('order_number', $orderNumber)->first();
    }

    // 关联地址
    public function orderAddr()
    {
        return $this->hasOne(OrderAddr::class, 'order_id', $this->primaryKey);
    }

    // 关联订单多个销售属性
    public function orderSpecs()
    {
        return $this->hasMany(OrderSpecification::class, 'order_id', 'order_id');
    }
}
