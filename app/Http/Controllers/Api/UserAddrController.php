<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\UserAddr;
use App\Services\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserAddrController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }

    public function index(Request $request)
    {
        $uid = Session::get('user')->uid;
        $page = $request->input('page');
        return UserAddr::findMoreByUid($uid, $page);
    }

    public function create(Request $request)
    {
        $this->validateRequest($request);
        return $this->performSave(new UserAddr(), $request);
    }

    public function edit(Request $request, $id)
    {
        $this->validateRequest($request);

        $model = $this->getUserAddr($id);

        if (empty($model)) {
            return ApiResponse::failed(ApiResponse::NOT_FOUND);
        }

        return $this->performSave($model, $request);
    }

    private function getUserAddr($id)
    {
        $uid = Session::get('user')->uid;
        return UserAddr::findOne($id, $uid);
    }

    private function performSave(UserAddr $model, Request $request)
    {
        $model->fill($request->all());
        $model->uid = Session::get('user')->uid;
        $model->area = empty($request->input('area')) ? '' : $request->input('area');

        if (!$model->save()) {
            return ApiResponse::failed(ApiResponse::FAILED);
        }

        return ApiResponse::SUCCESS(ApiResponse::SUCCESS);
    }

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province_id' => 'required|integer',
            'city_id' => 'required|integer',
            'area_id' => 'integer',

            'province' => 'required|string|max:15',
            'city' => 'required|string|max:15',

            'info' => 'required|string|max:150',
            'phone' => 'required|numeric',
            'addressee' => 'required|string|max:12'
        ]);

        if ($validator->fails()) {
            ApiResponse::throwValidationException(
                $validator,
                ApiResponse::CHECK_ERROR,
                $validator->errors()->first()
            );
        }
    }
}
