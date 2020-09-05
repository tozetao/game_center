<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    /**
     * 存档数量限制
     */
    const ARCHIVE_LIMIT = 5;

    public $timestamps = false;

    public $fillable   = ['master_id', 'created_at'];

    /**
     * 判断某个用户存储是否超出限制
     */
    public static function exceedLimit($uid)
    {
        $total = self::where('master_id', $uid)->count();
        return $total >= self::ARCHIVE_LIMIT;
    }

    public function baseSetting()
    {
        return $this->hasOne(BaseSetting::class, 'archive_id');
    }
}
