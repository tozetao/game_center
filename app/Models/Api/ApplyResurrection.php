<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ApplyResurrection extends Model
{
    public $table = 'apply_resurrection';

    public $timestamps = false;

    public static function findByMidAndSid($masterId, $slaveId)
    {
        return self::where(['master_id' => $masterId, 'slave_id' => $slaveId])->first();
    }
}
