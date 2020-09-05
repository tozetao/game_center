<?php

namespace App\Services\File;

use App\Services\Encode\Base64;
use Illuminate\Support\Facades\Log;

class ImageService extends FileService
{
    private $decodeData;

    public function __construct()
    {
        parent::__construct();
        $this->allowTypes = ['jpeg', 'gif', 'jpg', 'png'];
        $this->setRelativePath('/images/api');
    }

    // 解析编码后的图片数据
    public function parseEncodeData($encodeData)
    {
        // 将文件类型与编码内容切割
        $parts = explode(';', $encodeData);

        if (count($parts) != 2) {
            throw new \InvalidArgumentException('无效的编码数据.');
        }

        // 解析前缀
        $start  = false;
        $prefix = $parts[0];

        if (($start = strpos($prefix, '_')) === false) {
            throw new \InvalidArgumentException('无效的编码数据.');
        }

        $this->suffix = substr($prefix, $start + 1);

        // 转码
        $base64Content = $parts[1];

        if (($start = strpos($base64Content, ',')) === false) {
            throw new \InvalidArgumentException('无效的编码数据.');
        }

        $this->decodeData = Base64::safeBase64Decode(substr($base64Content, $start + 1));
    }

    public function store()
    {
        $storePath = $this->storePath();

        if (!is_dir($storePath)) {
            mkdir($storePath, 0777, true);
        }

        $tmp = $this->filename();
        $filename = $storePath . '/' . $tmp;

        if (file_put_contents($filename, $this->decodeData) === false) {
            $this->error = '图片存储失败.';
            return false;
        }

        return $this->relativePath . '/' . $tmp;
    }

}