<?php

namespace App\Services\Lock;

use Illuminate\Support\Facades\Redis;

class RedisLock implements Lock
{
    private $timeout;

    private $uid;

    public function __construct($timeout = 3)
    {
        $this->timeout = $timeout;
    }

    public function callback($key, $closure)
    {
        if ($this->lock($key)) {
            $result = $closure();

            $this->release($key);

            return $result;
        }
    }

    /**
     * 加锁成功会返回用于解锁的uid，失败返回false。
     */
    public function lock($key)
    {
        $this->uid = uniqid();
        $key = $this->getKey($key);

        if (Redis::set($key, $this->uid, 'EX', $this->timeout, 'NX')) {
            return $this->uid;
        }

        return false;
    }

    // 老版本的实现
    public function oldLock($key)
    {
        // 多个命令之间不是原子性执行的，expire命令可能会被多次执行，只要确保key的过期时间不总是被更新，
        // 这样在setnx、expire俩个命令执行间隔期间程序崩溃，也能够达到让key自动过期而不阻塞程序。
        if (Redis::setnx($key, $uid) ) {
            Redis::expire($key);
        } else if (Redis::ttl($key) == -1) {
            Redis::expire($key, $this->getTimeout());
        }
    }

    public function release($key)
    {
        $key = $this->getKey($key);

        if (Redis::get($key) === $this->uid) {
            Redis::delete($key);
        }
    }

    public function setTimeout($t)
    {
        $this->timeout= $t;
    }

    protected function getKey($key)
    {
        return env('APP_NAME', '') . 'LOCK:REDIS:' . $key;
    }
}