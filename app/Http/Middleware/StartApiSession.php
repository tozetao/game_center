<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Session\SessionManager;

class StartApiSession
{
    protected $manager;

    public function __construct(SessionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 实例化session并初始化session数据
        $session = $this->manager->driver();
        $session->setId($request->input('sess_id'));
        $session->start();

        // 将session对象注入到$request对象中
        $request->setLaravelSession($session);

        // session gc
        $this->collectGarbage($session);

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $this->manager->driver()->save();
    }

    // session gc
    protected function collectGarbage(Session $session)
    {
        $config = $this->manager->getSessionConfig();

        if ($this->configHitsLottery($config)) {
            $session->getHandler()->gc($this->getSessionLifetimeInSeconds());
        }
    }

    protected function configHitsLottery(array $config)
    {
        $in = [];

        for($i = 1; $i <= $config['lottery'][0]; $i++) {
            $in[] = $i;
        }

        $random = random_int(1, $config['lottery'][1]);

        if (in_array($random, $in)) {
            return true;
        }

        return false;
    }

    protected function getSessionLifetimeInSeconds()
    {
        return ($this->manager->getSessionConfig()['lifetime'] ?? null) * 60;
    }
}
