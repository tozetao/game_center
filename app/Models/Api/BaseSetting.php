<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class BaseSetting extends Model
{
    public $table = 'base_setting';

    public $timestamps = false;

    public $fillable = [
        'archive_id',
        'my_name',
        'other_name',
        'hair',
        'clothes',
        'sex'
    ];


    public static function findByAidAndMid($archiveId, $masterId)
    {
        return BaseSetting::where([
            'archive_id' => $archiveId,
            'master_id'  => $masterId
        ])->first();
    }
}
