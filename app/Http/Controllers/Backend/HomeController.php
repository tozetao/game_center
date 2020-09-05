<?php

namespace App\Http\Controllers\Backend;

use App\Widgets\PrivilegeBoxes;
use App\Widgets\Sidebar;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $sidebar;

    public function __construct(Sidebar $sidebar)
    {
        $this->sidebar = $sidebar;
    }

    public function index()
    {
        // 获取用户权限列表
        $user = Auth::guard('backend')->user();

        $sidebarText = $this->sidebar->generate($user->role->getPrivileges());
        return view('home.index', [
            'sidebarText' => $sidebarText,
            'name' => $user->account
        ]);
    }

    public function console()
    {
        return view('home.console');
    }

    /*
    public function sidebar()
    {
        $sidebar = new Sidebar();
        $s = ['kongzhi', 'xiaoxi', 'zhuye2', 'wenzhang'];
        $text = $sidebar->generate($s);

        return view('layouts.index', ['sidebar' => $text]);
    }

    public function role()
    {
        $obj = new PrivilegeBoxes();

        $t1 = ['kongzhi', 'add_zhuye2', 'zhuye2'];
        $t2 = ['wenzhang', 'add_wenzhang', 'edit_wenzhang', 'add_zhuye2', 'zhuye2'];
        $text = $obj->generate($t2);
        return view('role.privilege_boxes', ['tree' => $text]);
    }
    */
}
