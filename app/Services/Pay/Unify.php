<?php

namespace App\Services\Pay;

interface Unify
{
    /**
     * 返回订单金额
     *
     * @return mixed 订单金额
     */
    public function getTotalFee();

    /**
     * 返回订单描述
     *
     * @return mixed
     */
    public function getBody();

    /**
     * 返回订单编号
     *
     * @return mixed
     */
    public function getNumber();
}