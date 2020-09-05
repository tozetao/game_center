<?php

namespace App\Services\Encode;

class Base64
{
    public static function safeBase64Encode($str)
    {
        $str = base64_encode($str);
        $str = str_replace(['+', '/', '='], ['-', '_', ''], $str);
        return $str;
    }

    public static function safeBase64Decode($str)
    {
        $str = str_replace(['-', '_'], ['+', '/'], $str);
        $mod4 = strlen($str) % 4;
        if($mod4){
            $str .= substr('====', $mod4);
        }
        return base64_decode($str);
    }

}