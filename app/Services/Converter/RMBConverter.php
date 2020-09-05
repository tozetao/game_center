<?php

namespace App\Services\Converter;

class RMBConverter
{
    public static function yuantojiao($val, $scale = 0)
    {
        return bcmul($val, 10, $scale);
    }

    public static function yuantofen($val, $scale = 0)
    {
        return bcmul($val, 100, $scale);
    }

    public static function fentoyuan($val, $scale = 0)
    {
        return bcdiv($val, 100, $scale);
    }
}