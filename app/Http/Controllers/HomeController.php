<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    // 授权后跳转到游戏客户端
    public function index(Request $request, Response $response)
    {
        if ($request->get('code')) {
            $path = '/showlove/index.html?' . $request->getQueryString();
            return redirect(url($path));
        }

        // 回调授权
        $app = resolve('EasyWeChant');

        return $app->oauth
            ->with(['state' => $request->get('state')])
            ->scopes(['snsapi_userinfo'])
            ->redirect(route('entry'));
    }

}
