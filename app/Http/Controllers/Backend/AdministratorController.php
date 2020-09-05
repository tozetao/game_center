<?php

namespace App\Http\Controllers\Backend;

use App\Models\Backend\Administrator;
use App\models\Backend\Role;
use App\Models\Backend\Search\AdministratorSearch;
use App\Models\Backend\Search\RoleSearch;
use App\Services\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminRequest;

class AdministratorController extends Controller
{
    const ALL_SUB_ACCOUNT = 2;

    private $adminSearch;
    private $roleSearch;

    public function __construct(AdministratorSearch $as, RoleSearch $rs)
    {
        $this->adminSearch = $as;
        $this->roleSearch = $rs;
    }

    public function index(Request $request)
    {
        $id = Auth::guard('backend')->id();
        $type = $request->get('type');

        $conditions = [
            'account' => $request->get('account')
        ];

        $model = new AdministratorSearch();

        if ($type == self::ALL_SUB_ACCOUNT) {
            $administrators = $model->descendantAccount($id, $conditions);
        } else {
            $administrators = $model->subAccount($id, $conditions);
        }

        return view('administrator/index', compact('administrators'));
    }

    public function create()
    {
        $id = Auth::guard('backend')->id();
        $roles = $this->roleSearch->allSubRoles($id);
        return view('administrator/create', compact('roles'));
    }

    public function store(AdminRequest $request)
    {
        $administrator = new Administrator();

        if (!$request->performCreate($administrator)) {
            Flash::failed('操作失败!');
            return redirect()->back()->withInput($request->all());
        }
        Flash::success('操作成功!');
        return redirect()->route('admin.create');
    }

    public function edit($id)
    {
        $admin = Administrator::findOrFail($id);

        $id = Auth::guard('backend')->id();
        $roles = $this->roleSearch->allSubRoles($id);

        return view('administrator/edit', compact('admin', 'roles'));
    }

    public function update(AdminRequest $request, $id)
    {
        $administrator = Administrator::findOrFail($id);

        $administrator->account = $request->input('account');
        $administrator->role_id = $request->input('role_id');

        if (!$administrator->save()) {
            Flash::failed('操作失败!');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('操作成功!');
        return redirect()->route('admin.edit', ['id' => $administrator->id]);
    }
}
