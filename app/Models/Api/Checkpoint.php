<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    public $timestamps = false;

    public $table = 'checkpoint_setting';

    public $fillable = [
        'archive_id', 'success_msg', 'fail_msg', 'image', 'number', 'master_id', 'red_envelope_no'
    ];

    public function createOne($data)
    {
        $this->fill($data);

        if (empty($this->image)) {
            $this->image = '';
        }

        return $this->save();
    }

    // 获取关卡数量
    public static function getNumberOfLevels($masterId)
    {
        return self::where('master_id', $masterId)->count();
    }

    public static function findByAidAndMid($archiveId, $masterId)
    {
        return self::where([
            'archive_id' => $archiveId,
            'master_id' => $masterId
        ])->get();
    }
}
