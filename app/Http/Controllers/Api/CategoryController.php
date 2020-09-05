<?php

namespace App\Http\Controllers\Api;

use App\Models\Backend\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }
}
