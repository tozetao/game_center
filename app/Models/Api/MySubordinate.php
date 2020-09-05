<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class MySubordinate extends Model
{
    public $table = 'my_subordinate';

    public $timestamps = false;

    public static function exists($masterId, $slaveId)
    {
        $n = self::where([
            'master_id' => $masterId,
            'slave_id' => $slaveId
        ])->count();

        return $n != 0;
    }

    // 查询关联信息
    public static function findRelation($masterId, $slaveId)
    {
        return self::where(['master_id' => $masterId, 'slave_id' => $slaveId])->first();
    }
}
