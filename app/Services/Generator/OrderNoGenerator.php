<?php

namespace App\Services\Generator;

use Illuminate\Support\Facades\Redis;

class OrderNoGenerator
{
    const COUNT_BITS  = 12;

    const TTL = 10;

    // 年月日 - reverse(小时分钟秒) - 序列号
    public function generate()
    {
        $timestamp = time();

        $date = date('ymd', $timestamp);
        $time = strrev(date('His', $timestamp));
        $serial = sprintf('%04s', $this->count($timestamp));

        return $date . $time . $serial;
    }

    /**
     * 在一秒中内，递增的生成0-1023的序号
     * @time    时间戳
     */
    public function count($time)
    {
        $key  = env('APP_NAME', '') . ':order_seq:' . $time;

        Redis::set($key, mt_rand(1, 9), 'EX', self::TTL, 'NX');

        $count = Redis::incr($key);

        if ($count > $this->max(self::COUNT_BITS)) {
            throw new \InvalidArgumentException('the count value exceeds 1024');
        }

        return $count;
    }

    // 返回指定若干个位(bit)的最大数值(int型)
    private function max($bits)
    {
        return (-1 ^ (-1 << $bits));
    }
}