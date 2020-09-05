<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BaseSettingRequest;
use App\Http\Resources\UserSettingResource;
use App\Models\Api\BaseSetting;
use App\Models\Api\Checkpoint;
use App\Models\Api\IconSetting;
use App\Models\Api\MySubordinate;
use App\Models\Api\User;
use App\Services\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserSettingStoreRequest;
use App\Http\Requests\CheckpointRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserSettingController extends Controller
{
    public function baseSetting(BaseSettingRequest $request)
    {
        $model = $this->findBaseSetting($request->post('archive_id'));

        if (!$request->performUpdate($model)) {
            return ApiResponse::failed(ApiResponse::FAILED);
        }

        return ApiResponse::success();
    }

    private function findBaseSetting($archiveId)
    {
        $model = BaseSetting::findByAidAndMid($archiveId, Session::get('user')->uid);
        return $model ? $model : new BaseSetting();
    }

    // 设置关卡信息
    public function checkpoint(CheckpointRequest $request)
    {
        $model = $this->findCheckpoint($request);

        $data  = array_merge($request->all(),
            ['master_id' => Session::get('user')->uid]);

        $number = $request->post('envelope_no', '');

        if ($number) {
            $data['red_envelope_no'] = $number;
        }

        Log::info($data);

        if ($model->createOne($data)) {
            return ApiResponse::success();
        }

        return ApiResponse::failed(ApiResponse::FAILED);
    }

    private function findCheckpoint($request)
    {
        $condition = [
            'archive_id' => $request->post('archive_id'),
            'master_id' => Session::get('user')->uid,
            'number' => $request->post('number')
        ];

        $model = Checkpoint::where($condition)->first();

        return $model ? $model : new Checkpoint();
    }

    // 设置各种状态下的头像
    public function iconSetting(Request $request)
    {
        $this->validateIcon($request);

        $model = $this->findIconSetting($request);
        $data  = array_merge($request->all(), ['master_id' => Session::get('user')->uid]);
        $model->fill($data);

        if ($model->save()) {
            return ApiResponse::success();
        }

        return ApiResponse::failed(ApiResponse::FAILED);
    }

    private function findIconSetting(Request $request)
    {
        $condition = [
            'archive_id' => $request->post('archive_id'),
            'master_id' => Session::get('user')->uid,
            'type' => $request->post('type')
        ];

        $model = IconSetting::where($condition)->first();

        return $model ? $model : new IconSetting();
    }

    private function validateIcon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archive_id' => 'required|integer|exists:archives,id',
            'type' => 'required|in:1,2,3,4,5,6',
            'icon' => 'required'
        ]);

        if ($validator->fails()) {
            ApiResponse::throwValidationException(
                $validator,
                ApiResponse::CHECK_ERROR,
                $validator->errors()->first()
            );
        }
    }


    // 查询我的设置
    public function mySetting(Request $request)
    {
        $archiveId = $request->get('archive_id');
        $uid = Session::get('user')->uid;
        return $this->findSetting($archiveId, $uid);
    }

    private function findSetting($archiveId, $uid)
    {
        $baseSetting = BaseSetting::findByAidAndMid($archiveId, $uid);
        $icons = IconSetting::findByAidAndMid($archiveId, $uid);
        $checkpoints = Checkpoint::findByAidAndMid($archiveId, $uid);

        return [
            'base_setting' => $baseSetting,
            'icons' => $icons,
            'checkpoints' => $checkpoints
        ];
    }


    // 查询追求者的设置
    public function suitor(Request $request)
    {
        $masterId = $request->get('from_id');
        $slaveId = Session::get('user')->uid;
        $archiveId = $request->get('archive_id');

        // 查询关联的id
        $subordinate = MySubordinate::findRelation($masterId, $slaveId);

        if (!$subordinate) {
            return ApiResponse::failed(ApiResponse::FAILED, '尚未建立关联关系.');
        }

        return $this->findSetting($archiveId, $masterId);
    }


    // 建立关联关系
    public function related(Request $request)
    {
        $curUser = Session::get('user');

        if ($curUser->uid == $request->post('from_id')) {
            return ApiResponse::failed(ApiResponse::CHECK_ERROR, '不能与自己建立关联关系.');
        }

        // 检查是否已建立关系
        if (MySubordinate::exists($request->post('from_id'), $curUser->uid)) {
            return ApiResponse::failed(ApiResponse::CHECK_ERROR, '已经建立关联关系.');
        }

        $model = new MySubordinate();
        $model->master_id = $request->post('from_id');
        $model->slave_id = $curUser->uid;

        if ($model->save()) {
            return ApiResponse::success();
        }

        return ApiResponse::failed(ApiResponse::FAILED);
    }

    public function relation(Request $request)
    {
        $masterId = $request->get('from_id');
        $slaveId  = Session::get('user')->uid;

        $model = MySubordinate::findRelation($masterId, $slaveId);

        return $model ? ['from_id' => $model->master_id, 'uid' => $slaveId] : [];
    }


    // 查询我的复活次数
    public function resurrections()
    {
        $user = User::find(Session::get('user')->uid);
        return ['times' => $user->revive_times];
    }


    // 增加下属的复活次数
    public function resurrection(Request $request)
    {
        $this->validateResurrection($request);

        return config('type.incr') == $request->input('type') ?
            $this->incrReviveTimes($request) : $this->decrReviveTimes($request);
    }

    private function validateResurrection(Request $request)
    {
        $rules = [];
        $type  = $request->post('type');

        if (config('type.incr') == $type) {
            $rules = [
                'type' => 'required|in:1,2',
                'from_id' => 'integer'
            ];
        } else {
            $rules = ['type' => 'required|in:1,2'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            ApiResponse::throwValidationException($validator, ApiResponse::CHECK_ERROR);
        }
    }

    private function incrReviveTimes(Request $request)
    {
        // 必须是他的下属
        $user     = Session::get('user');
        $slaveId  = $request->post('from_id');

        if (!MySubordinate::exists($user->uid, $slaveId)) {
            return ApiResponse::failed(ApiResponse::FAILED, '该玩家不是你的下属.');
        }

        // 增加复活次数
        if (User::revive($slaveId)) {
            return ApiResponse::success();
        }

        return ApiResponse::failed(ApiResponse::CHECK_ERROR);
    }

    private function decrReviveTimes(Request $request)
    {
        $uid = Session::get('user')->uid;

        if (User::death($uid)) {
            return ApiResponse::success();
        }

        return ApiResponse::failed(ApiResponse::CHECK_ERROR);
    }
}