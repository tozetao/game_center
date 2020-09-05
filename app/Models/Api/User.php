<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Overtrue\Socialite\User as WechatUser;

class User extends Model
{
    public $primaryKey = 'uid';

    public $timestamps = false;

    public $fillable = ['nickname', 'sex'];

    // 创建测试用户
    public static function createTestUser($uid)
    {
        $model = self::find($uid);

        if (!empty($model)) {
            return $model;
        }

        $model = new User();

        $model->uid = $uid;
        $model->openid = str_shuffle('abcdefghijklmb-1230');
        $model->nickname = 'test' . substr(str_shuffle('abcdefghijklmb-1230'), 0, 5);
        $model->icon = '';
        $model->last_online_time = time();
        $model->sex = 0;
        $model->points = 1000000;
        $model->save();

        return $model;
    }

    // 查询一个用户的数据或者根据openid是否存在创建一个用户
    public static function findOrCreate(WechatUser $user)
    {
        $model = self::where('openid', $user->id)->first();

        // 不存在则创建
        if (!$model) {
            $model = new User();
            $model->openid = $user->id;
            $model->nickname = $user->nickname;
            $model->icon = $user->avatar;
            $model->last_online_time = time();
            $model->created_at = time();
            $model->points = 0;
            $model->sex = 0;
            $model->revive_times = 0;
        } else {
            $model->logged_at = time();
        }

        $model->save();
        return $model;
    }

    public function IconSettings()
    {
        return $this->hasMany(IconSetting::class, 'master_id', 'uid');
    }

    public static function incrPoints($uid, $point)
    {
        $affectedRows = DB::table('users')
            ->where('uid', $uid)
            ->increment('points', $point);

        return $affectedRows !== 0;
    }
    
    // 扣除积分
    public static function deductPoints($uid, $point)
    {
        $affectedRows = DB::table('users')
            ->where('uid', $uid)
            ->where('points', '>=', $point)
            ->decrement('points', $point);

        // 若影响行数为0将视为失败
        return $affectedRows !== 0;
    }

    // 复活
    public static function revive($uid)
    {
        return self::where('uid', $uid)->update(['revive_times' => 1]);
    }
    
    // 死亡
    public static function death($uid)
    {
        return self::where('uid', $uid)->update(['revive_times' => 0]);
    }
    
    // 下面这俩个方法暂时没有使用到
    
    // 增加复活次数
    public static function incrReviveTimes($uid, $times)
    {
        $affectedRows = DB::table('users')
            ->where('uid', $uid)
            ->increment('revive_times', $times);

        return $affectedRows !== 0;
    }
    
    // 减少复活次数
    public static function deductReviveTimes($uid, $times)
    {
        $affectedRows = DB::table('users')
            ->where('uid', $uid)
            ->decrement('revive_times', $times);

        return $affectedRows !== 0;
    }
}
