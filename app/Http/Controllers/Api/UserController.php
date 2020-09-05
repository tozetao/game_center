<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Api\MyProp;
use App\Models\Api\User;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // 查询玩家购买的道具
    public function props()
    {
        $uid = Session::get('user')->uid;

        $props = MyProp::where('uid', $uid)->get();

        return $props;
    }

    public function info()
    {
        $uid = Session::get('user')->uid;
        return new UserResource(User::find($uid));
    }
}
