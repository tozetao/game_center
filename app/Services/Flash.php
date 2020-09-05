<?php

namespace App\Services;

/**
 * User: zetao
 * Date: 2019/8/9
 * Time: 16:42
 */
class Flash
{
    const SUCCESS = 'session.flash.success';
    const FAILED  = 'session.flash.failed';

    public static function success($message)
    {
        session()->flash(self::SUCCESS, $message);
    }

    public static function failed($message)
    {
        session()->flash(self::FAILED, $message);
    }
}