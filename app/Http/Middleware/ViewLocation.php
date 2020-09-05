<?php

namespace App\Http\Middleware;

use Closure;

class ViewLocation
{
    /**
     * 更改后台视图的默认存储路径。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $finder = app('view')->getFinder();
        $finder->prependLocation(resource_path('views/backend'));
        return $next($request);
    }
}
