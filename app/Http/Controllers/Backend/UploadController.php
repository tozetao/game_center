<?php

namespace App\Http\Controllers\Backend;

use App\Services\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    protected $allowType;

    protected $sizeLimit;

    protected $savePath;

    protected $disk;

    public function __construct()
    {
        $this->sizeLimit = 2097152;
        $this->allowType = ['jpg', 'jpeg', 'png', 'gif'];
        $this->savePath  = '/images/goods';
        $this->disk      = 'public';
    }

    public function goods(Request $request)
    {
        return response()->json([
            'path' => $this->store($request)
        ]);
    }

    protected function store(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return ApiResponse::failed(ApiResponse::INVALID_FILE);
        }

        $file = $request->file('file');

        if ($this->checkSize($file->getClientSize())) {
            return ApiResponse::failed(ApiResponse::FILE_SIZE_LIMIT);
        }

        if ($this->checkExtension($file->extension())) {
            return ApiResponse::failed(ApiResponse::FORBIDDEN_FILE_TYPE);
        }

        return $file->store($this->savePath, $this->disk);
    }

    /**
     * @param int $limit 文件大小限制，默认2M
     */
    protected function checkSize($clientSize)
    {
        if ($clientSize > $this->sizeLimit) {
            return true;
        }

        return false;
    }

    protected function checkExtension($type)
    {
        if (!in_array($type, $this->allowType)) {
            return true;
        }

        return false;
    }
}
