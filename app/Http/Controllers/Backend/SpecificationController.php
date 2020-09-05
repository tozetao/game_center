<?php

namespace App\Http\Controllers\Backend;

use App\Models\Backend\SpecificationAttr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecificationController extends Controller
{
    // 查询类目的规格属性
    public function index(Request $request)
    {
        $specs = SpecificationAttr::allByCategory($request->input('category_id'));
        return response()->json($specs);
    }
}
