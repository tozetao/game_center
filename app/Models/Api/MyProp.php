<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class MyProp extends Model
{
    public $table = 'my_props';
    public $timestamps = false;

    public static function exists($uid, $propId)
    {
        return self::where(['uid' => $uid, 'prop_id' => $propId])->count();
    }
}
