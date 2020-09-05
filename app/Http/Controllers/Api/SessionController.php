<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Api\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    // 登陆测试接口
    public function test(Request $request)
    {
        $uid = $request->input('uid');

        $user = User::createTestUser($uid);

        Session::put('user', $user);

        return new UserResource($user);
    }

    // 必备参数：code，否则会抛出异常
    public function login(Request $request)
    {
        $app = resolve('EasyWeChant');

        $user = User::findOrCreate($app->oauth->user());

        Session::put('user', $user);

        return new UserResource($user);
    }

    public function demo()
    {
        return 'demo';
    }
}
