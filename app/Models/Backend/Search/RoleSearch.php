<?php

namespace App\Models\Backend\Search;

use App\Models\Backend\Role;
use App\Models\QueryFilter;

class RoleSearch extends QueryFilter
{
    protected $orderParams = ['name'];

    public function name($value)
    {
        $this->builder->where('name', 'like', '%' . $value . '%');
    }

    public function subRoles($creatorId, $condition = [])
    {
        $pageSize = 1;
        $builder = Role::where('creator_id', $creatorId);
        return $this->apply($builder, $condition)->paginate($pageSize);
    }

    public function allSubRoles($creatorId, $condition = [])
    {
        $builder = Role::where('creator_id', $creatorId);
        return $this->apply($builder, $condition)->get();
    }

    // 查询所有子角色
    public function allDescendantRole($creatorId, $condition = [])
    {
        $builder = Role::where('creator_id', $creatorId);
        return $this->apply($builder, $condition)->get();
    }
}