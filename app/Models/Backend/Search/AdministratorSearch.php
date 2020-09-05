<?php

namespace App\Models\Backend\Search;

use App\Models\Backend\Administrator;
use App\Models\QueryFilter;

class AdministratorSearch extends QueryFilter
{
    protected $orderParams = [
        'account'
    ];

    public function account($value)
    {
        $this->builder->where('account', 'like', '%' . $value . '%');
    }

    // 查询子账号
    public function subAccount($creatorId, $conditions = null)
    {
        $builder = Administrator::where('creator_id', $creatorId);
        return $this->apply($builder, $conditions)->paginate();
    }

    // 查询创建的所有子孙账号
    public function descendantAccount($creatorId, $conditions = null)
    {
        $ids = $this->allSubAccountId(Administrator::get(), $creatorId);
        $builder = Administrator::whereIn('id', $ids);
        return $this->apply($builder, $conditions)->paginate();
    }

    // 查询所有子孙id
    private function allSubAccountId($collection, $creatorId)
    {
        $ids = [];

        foreach ($collection as $model) {
            if ($model->creator_id == $creatorId) {
                $ids[] = $model->id;
                $ids = array_merge($ids, $this->allSubAccountId($collection, $model->id));
            }
        }

        return $ids;
    }
}