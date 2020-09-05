<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Authenticate
{
    /**
     * @var array 忽略验证的控制器名
     */
    protected $exceptController = [
        'login',
    ];

    /**
     * 验证账户是否登陆，是否授权
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $action = $this->parseAction();
        $authorization = $action['class'] . '.' . $action['method'];

        if (in_array($action['class'], $this->exceptController)) {
            return $next($request);
        }

        // 登陆认证
        $guard = Auth::guard('backend');
        if (!$guard->check()) {
            return redirect()->route('login');
        }

        // 授权判断
        if ($this->authorized($authorization)) {
            return $next($request);
        }

        abort('403', 'unauthed.');
    }

    private function parseAction()
    {
        $action = Route::currentRouteAction();
        list($class, $method) = explode('@', $action);
        $class = str_replace('controller', '', strtolower(basename($class))) ;

        return [
            'class' => $class,
            'method' => $method
        ];
    }

    /**
     * 授权判断
     *
     * 授权判断只针对需要验证的动作，即privilege.php文件配置的动作。
     * 比如goods.edit放行，goods.update验证。
     *
     * @param $authorization
     * @return bool
     */
    private function authorized($authorization)
    {
        $privileges = config('privilege');
        $user = Auth::guard('backend')->user();

        if (isset($privileges[$authorization])
            && !$user->role->hasPrivilege($privileges[$authorization])) {
            return false;
        }

        return true;
    }
}
