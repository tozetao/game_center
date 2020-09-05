<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserStatController extends Controller
{
    public function keep()
    {
        return 'keep';
    }

    public function number()
    {
        return view('user_stat.number');
    }
}
