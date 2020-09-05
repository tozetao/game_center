<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\ApplyResurrection;
use App\Models\Api\MySubordinate;
use App\Services\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ResurrectionController extends Controller
{
    // 申请复活
    public function apply(Request $request)
    {
        $this->validateApply($request);

        $model = $this->findOne(
            $request->post('master_id'), $request->post('slave_id'));

        $model->status = $request->post('status');

        if ($model->save()) {
            return ApiResponse::success();
        }

        return ApiResponse::failed(ApiResponse::FAILED);
    }

    private function validateApply(Request $request)
    {
        $masterId = $request->post('master_id');
        $slaveId = $request->post('slave_id');
        $status = $request->post('status');

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,2,3,4'
        ]);

        if ($validator->fails()) {
            ApiResponse::throwValidationException(
                $validator, ApiResponse::FAILED, $validator->errors()->first());
        }

        if (!MySubordinate::exists($masterId, $slaveId)) {
            ApiResponse::throwValidationException(
                $validator, ApiResponse::FAILED, '该玩家不是你的下属.');
        }
    }

    private function findOne($masterId, $slaveId)
    {
        $model = ApplyResurrection::findByMidAndSid($masterId, $slaveId);

        if (!$model) {
            $model = new ApplyResurrection();
            $model->master_id = $masterId;
            $model->slave_id = $slaveId;
        }

        return $model;
    }

    // 查询申请操作状态
    public function status(Request $request)
    {
        $masterId = $request->get('master_id');
        $slaveId = $request->get('slave_id');

        $model = ApplyResurrection::findByMidAndSid($masterId, $slaveId);

        if ($model) {
            return [
                'master_id' => $model->master_id,
                'slave_id' => $model->slave_id,
                'status' => $model->status
            ];
        }

        return [];
    }

}
