<?php


namespace App\Services\Converter;


class AppConverter
{
    // 金额（分）转换成积分
    public static function fentopoint($value, $scale = 2)
    {
        return bcdiv($value, 100, $scale);
    }
}