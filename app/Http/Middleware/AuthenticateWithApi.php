<?php

namespace App\Http\Middleware;

use App\Services\ApiResponse;
use Closure;
use Illuminate\Support\Facades\Session;

class AuthenticateWithApi
{
    /**
     * 针对API的用户认证
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Session::get('user');

        if (empty($user)) {
            return ApiResponse::failed(ApiResponse::UNAUTH);
        }

        return $next($request);
    }
}
