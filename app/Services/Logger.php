<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;

class Logger
{
    public static function info(\Exception $e)
    {
        $log = sprintf('code: %s, message: %s, file: %s, line: %s',
            $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
        Log::info($log);
    }
}