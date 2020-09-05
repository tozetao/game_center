<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Backend\Role;
use App\Models\Backend\RolePrivilege;
use App\Models\Backend\Search\RoleSearch;
use App\Rules\PrivilegeSet;
use App\Widgets\PrivilegeBox;
use App\Services\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    private $roleSearch;

    public function __construct(RoleSearch $roleSearch)
    {
        $this->roleSearch = $roleSearch;
    }

    public function index(Request $request)
    {
        $roles = $this->roleSearch->subRoles(Auth::guard('backend')->id(), $request->all());
        return view('role/index', compact('roles'));
    }

    // 显示创建角色页面
    public function create()
    {
        $user = Auth::guard('backend')->user();
        $privileges = $user->role->getPrivileges();

        $privilegeBox = new PrivilegeBox();
        $privilegeTree = $privilegeBox->generate($privileges);

        return view('role/create', compact('privilegeTree'));
    }

    // 创建角色，post：/roles
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $name = $request->input('name');
        $privileges = $request->input('privileges');

        if (!$role->createRole(Auth::guard('backend')->id(), $name, $privileges)) {
            Flash::failed('操作失败!');
            return redirect()->back()->withInput($request->input());
        }
        Flash::success('操作成功!');
        return redirect()->route('role.create');
    }

    public function edit($roleId)
    {
        $role = Role::findOrFail($roleId);
        $user = Auth::guard('backend')->user();

        // 查询当前账户的角色的权限
        $privileges = $user->role->getPrivileges();

        // 查询编辑角色的权限
        $checkedPrivileges = $role->getPrivileges();

        // 生成界面
        $privilegeBox = new PrivilegeBox();
        $privilegeTree = $privilegeBox->generate($privileges, $checkedPrivileges);
        return view('role/edit', compact('role', 'privilegeTree'));
    }

    public function update(RoleRequest $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        $name = $request->input('name');
        $privileges = $request->input('privileges');

        if (!$role->updateRole($name, $privileges)) {
            Flash::failed('操作失败!');
            return redirect()->back()->withInput($request->input());
        }
        Flash::success('操作成功!');
        return redirect()->route('role.edit', ['role_id' => $role->id]);
    }
}
