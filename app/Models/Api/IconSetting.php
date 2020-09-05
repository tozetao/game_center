<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class IconSetting extends Model
{
    public $table = 'icon_setting';

    public $fillable = ['icon', 'type', 'master_id', 'archive_id'];

    public $timestamps = false;

    public static function findByAidAndMid($archiveId, $masterId)
    {
        return self::where([
            'archive_id' => $archiveId,
            'master_id' => $masterId
        ])->get();
    }
}
