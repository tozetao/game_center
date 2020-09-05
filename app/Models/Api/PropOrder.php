<?php

namespace App\Models\Api;

use App\Services\Pay\Unify;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PropOrder extends Model implements Unify
{
    public $timestamps = false;

    public $table = 'prop_order';

    public $fillable = ['user_id', 'payment_type', 'status', 'number'];

    public function createOrder($data)
    {
        $this->fill($data);
        $this->created_at = time();
        $this->pay_at = 0;
        return $this->save();
    }

    public static function findByNo($number)
    {
        return self::where('number', $number)->first();
    }

    public function pay()
    {
        $this->status = config('type.order.paymented');
        $this->pay_at = time();
        $this->handleMyProps();
        return $this->save();
    }

    // 处理我的道具
    protected function handleMyProps()
    {
        $rows = [];

        foreach ($this->props as $prop) {
            $rows[] = [
                'uid' => $this->user_id,
                'prop_id' => $prop->prop_id
            ];
        }

        DB::table('my_props')->insert($rows);
    }

    public function failed()
    {
        $this->status = config('type.order.failed');
        $this->pay_at = time();
        return $this->save();
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getTotalFee()
    {
        $total = 0;

        if ($this->props) {
            foreach ($this->props as $prop) {
                $total += $prop->price;
            }
        }

        if (!$total) {
            throw new \LogicException('The total price can not be 0.');
        }

        return $total;
    }

    public function getBody()
    {
        return '游戏道具';
    }

    // 该订单对应的道具列表
    public function props()
    {
        return $this->hasMany(PropOrderBody::class, 'prop_order_id');
    }
}
