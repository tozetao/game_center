<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    protected $orderParams = null;

    protected $builder;

    protected $pageSize;

    public function __construct()
    {
        $this->pageSize = $pageSize = config('app.page_size');
    }

    protected function apply(Builder $builder, $conditions)
    {
        $this->builder = $builder;

        foreach ($this->orderParams as $field) {
            if (isset($conditions[$field])) {
                $this->callback($field, $conditions[$field]);
            }
        }

        return $builder;
    }

    protected function paginate(Builder $builder, $page, $pageSize = 10)
    {
        $offset = ($page -1) * $pageSize;
        $this->builder->offset($offset)->limit($pageSize);

        return $builder;
    }

    private function callback($key, $value) {
        $method = $this->toCamelCase($key);

        if ($value !== null && method_exists($this, $method)) {
            call_user_func_array([$this, $method], [$value]);
        }
    }

    // 将匈牙利命名转换成驼峰命名
    private function toCamelCase($field)
    {
        $segments = explode('_', $field);

        $length = count($segments);

        $converted = $segments[0];

        for($i = 1; $length > 1 && $i < $length; $i++) {
            $converted .= ucfirst($segments[$i]);
        }

        return $converted;
    }

}

/*

一般的，查询接口需要用户提交搜索条件，后端根据前端的搜索条件来生成查询的SQL语句。


*/