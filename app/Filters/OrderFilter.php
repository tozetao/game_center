<?php

namespace App\Filters;

class OrderFilter extends BaseFilter
{
    public function uid($val)
    {
        $this->builder->where('uid', $val);
    }

    public function paymentType($val)
    {
        $this->builder->where('payment_type', $val);
    }

    public function orderNumber($val)
    {
        $this->builder->where('order_number', $val);
    }

    public function delivery($val)
    {
        $this->builder->where('delivery_status', $val);
    }

    public function payStatus($val)
    {
        $this->builder->where('status', $val);
    }
}