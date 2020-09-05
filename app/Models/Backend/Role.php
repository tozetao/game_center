<?php

namespace App\Models\Backend;

use App\Services\Logger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Role extends Model
{
    protected $table = 'roles';
    protected $relationTable = 'role_privilege';

    public $timestamps = false;

    // 角色的权限
    private $privileges = null;

    public function createRole($creatorId, $name, $privileges)
    {
        try {
            DB::beginTransaction();

            // 创建角色
            $roleId = DB::table($this->table)->insertGetId([
                'name' => $name,
                'created_at' => time(),
                'creator_id' => $creatorId
            ]);

            DB::table($this->relationTable)
                ->insert($this->buildRolePrivilege($roleId, $privileges));
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function updateRole($name, $privileges)
    {
        try {
            DB::beginTransaction();

            $this->name = $name;
            $this->save();

            $collection = RolePrivilege::where('role_id', $this->id)->get();

            // 移除多余的权限
            $this->removeExtra($collection, $privileges);

            $this->insertNews($collection, $privileges);

            $this->handleChildrenPrivilege($this->id, $privileges);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Logger::info($e);
            return false;
        }
    }

    // 移除取消的权限
    private function removeExtra($original, $postPrivileges)
    {
        // 以privilege字段的指作为Key
        $rows = $original->keyBy('privilege')->toArray();

        // 原有权限集合
        $originalPrivileges = array_keys($rows);

        // 多余的权限
        $removedPrivileges = array_diff($originalPrivileges, $postPrivileges);

        $removedId = [];

        foreach ($removedPrivileges as $privilege) {
            $removedId[] = $rows[$privilege]['id'];
        }

        if ($removedId) {
            RolePrivilege::whereIn('id', $removedId)->delete();
        }
    }

    // 新增新的权限
    private function insertNews($original, $postPrivileges)
    {
        $originalPrivileges = $original->pluck('privilege')->toArray();

        $newPrivileges = array_diff($postPrivileges, $originalPrivileges);

        DB::table($this->relationTable)
            ->insert($this->buildRolePrivilege($this->id, $newPrivileges));
    }

    /**
     * 1. 找出该角色下的所有账号
     * 2. 再找出这些账号创建的子角色，移除这些角色不该有的权限。
     * 以此作为循环，直到该角色下的所有子孙角色处理完毕。
     * @param $roleId        更新的角色id
     * @param $privileges    客户端提交的权限列表
     */
    private function handleChildrenPrivilege($roleId, $privileges)
    {
        // 找出$roleId的账号列表
        $userIds = Administrator::where('role_id', $roleId)->pluck('id')->toArray();

        if (empty($userIds)) {
            return;
        }

        // 找出这些账号创建的角色
        $roles = self::whereIn('creator_id', $userIds)->get();

        // 移除这些角色不该有的权限
        foreach ($roles as $role) {
            $collection = RolePrivilege::where('role_id', $role->id)->get();
            $this->removeExtra($collection, $privileges);
            $this->handleChildrenPrivilege($role->id, $privileges);
        }
    }

    private function buildRolePrivilege($roleId, $privileges) {

        // 更新角色的权限
        $set = [];

        foreach ($privileges as $privilege) {
            $set[] = [
                'role_id' => $roleId,
                'privilege' => $privilege
            ];
        }

        return $set;
    }

    public function hasPrivilege($val)
    {
        if (!$this->privileges) {
            $this->privileges = $this->getPrivileges();
        }

        return in_array($val, $this->privileges);
    }

    public function getPrivileges()
    {
        return RolePrivilege::where('role_id', $this->id)
            ->get()
            ->pluck('privilege')
            ->toArray();
    }
}
