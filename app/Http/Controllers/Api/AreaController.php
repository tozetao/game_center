<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function province()
    {
        return Area::where('type', 1)->get();
    }

    public function city(Request $request)
    {
        return Area::where('pid', $request->get('pid'))->get();
    }

    public function area(Request $request)
    {
        return Area::where('pid', $request->get('pid'))->get();
    }
}

