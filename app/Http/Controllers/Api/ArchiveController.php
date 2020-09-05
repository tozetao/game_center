<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ArchiveResource;
use App\Models\Api\Archive;
use App\Models\Api\BaseSetting;
use App\Models\Api\Checkpoint;
use App\Models\Api\IconSetting;
use App\Services\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ArchiveController extends Controller
{
    // 查询玩家的存档记录
    public function index()
    {
        $uid = Session::get('user')->uid;
        $archives = Archive::where('master_id', $uid)->with('baseSetting')->get();
        return ArchiveResource::collection($archives);
    }

    public function create()
    {
        $user = Session::get('user');

        if (Archive::exceedLimit($user->uid)) {
            return ApiResponse::failed(ApiResponse::ARCHIVE_LIMIT);
        }

        return Archive::create([
            'master_id' => $user->uid,
            'created_at' => time()
        ]);
    }

    public function delete($id)
    {
        $uid = Session::get('user')->uid;

        $conditions = [
            'archive_id' => $id,
            'master_id' => $uid
        ];

        // 删除存档记录
        Archive::where(['id' => $id, 'master_id' => $uid])->delete();

        // 删除基本配置
        BaseSetting::where($conditions)->delete();

        // 删除表情
        IconSetting::where($conditions)->delete();

        // 删除关卡
        Checkpoint::where($conditions)->delete();

        return ApiResponse::success();
    }
}
