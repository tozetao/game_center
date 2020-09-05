<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class BaseFilter
{
    protected $builder;

    protected $conditions;

    protected $ignore;

    public function __construct(Request $request)
    {
        $this->ignore  = [];
        $this->conditions = $request->all();
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->conditions as $name => $value) {

            $name = $this->toCamelCase($name);

            if ($value !== null && method_exists($this, $name)) {
                call_user_func_array([$this, $name], [$value]);
            }
        }

        return $this->builder;
    }

    // 将下划线变量转换成驼峰变量
    public function toCamelCase($name)
    {
        $segments = explode('_', $name);

        if (count($segments) == 1) {
            return $name;
        }

        $name = $segments[0];

        for ($i = 1; $i < count($segments); $i++) {
            $name .= ucwords($segments[$i]);
        }

        return $name;
    }

    public function addCondition($key, $value)
    {
        $this->conditions[$key] = $value;
    }
}