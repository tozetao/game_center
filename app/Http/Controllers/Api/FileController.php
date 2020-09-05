<?php

namespace App\Http\Controllers\Api;

use App\Services\ApiResponse;
use App\Services\File\ImageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class FileController extends Controller
{
    // 上传图片
    public function uploadImage(Request $request, ImageService $service)
    {
        $encodeData = $request->input('image');

        // 解析编码数据
        $service->parseEncodeData($encodeData);

        // 验证
        if (!$service->checkSize(strlen($encodeData))) {
            return ApiResponse::failed(
                ApiResponse::CHECK_ERROR,
                $service->getError()
            );
        }

        $suffix = $service->getSuffix();

        if (!$service->checkAllowType($suffix)) {
            return ApiResponse::failed(
                ApiResponse::CHECK_ERROR,
                $service->getError()
            );
        }

        return response()->json(['file' => $service->store()]);
    }
}
