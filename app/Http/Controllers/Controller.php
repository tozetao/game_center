<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 为分页器附加查询参数
     */
    public function applyPaginateParams(&$paginator)
    {
        foreach (request()->all() as $key => $value) {
            if ($value != null) {
                $paginator->appends($key, $value);
            }
        }
    }
}
