<?php

namespace App\Services\File;

class FileService
{
    protected $sizeLimit;

    protected $allowTypes;

    protected $root;

    protected $relativePath;

    protected $error;

    protected $suffix;

    public function __construct()
    {
        $this->root = public_path();
        $this->relativePath = '/';
        $this->sizeLimit = 1024 * 1024 * 2;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function setRelativePath($path)
    {
        $this->relativePath = $path;
    }

    public function setAllowType($types)
    {
        $this->allowTypes = $types;
    }

    protected function filename()
    {
        return uniqid() . '.' . $this->suffix;
    }

    protected function storePath()
    {
        return $this->root . $this->relativePath;
    }

    public function checkSize($size)
    {
        if ($size > $this->sizeLimit) {
            $this->error = '超过上传限制大小.';
            return false;
        }

        return true;
    }

    public function checkAllowType($type)
    {
        if (!in_array($type, $this->allowTypes)) {
            $this->error = '不允许上传的类型';
            return false;
        }

        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }
}