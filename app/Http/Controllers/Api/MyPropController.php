<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\MyProp;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class MyPropController extends Controller
{
    public function index()
    {
        $uid = Session::get('user')->uid;

        return MyProp::where('uid', $uid)->get();
    }
}
