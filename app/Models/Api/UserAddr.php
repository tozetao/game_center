<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class UserAddr extends Model
{
    public $fillable   = [
        'uid',
        'province',
        'city',
        'area',
        'province_id',
        'city_id',
        'area_id',
        'info',
        'addressee',
        'phone'
    ];

    public $table      = 'user_addr';

    public $timestamps = false;

    public static function findMoreByUid($uid, $page, $pageSize = 10)
    {
        return self::where('uid', $uid)
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->get();
    }

    public static function findOne($id, $uid)
    {
        return self::where([
            'id' => $id,
            'uid' => $uid
        ])->first();
    }
}