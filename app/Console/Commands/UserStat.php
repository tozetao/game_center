<?php

namespace App\Console\Commands;

use App\Models\Api\User;
use Illuminate\Console\Command;
use App\Models\Console\UserStat as UserStatModel;

class UserStat extends Command
{
    const ONE_DAY = 86400;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For user statistics';

    protected $time;

    protected $yesterday;

    public function __construct()
    {
        parent::__construct();

        $this->time = time();

        $this->yesterday = mktime(0, 0, 0,
            date('m', $this->time),date('d', $this->time) - 1, date('Y', $this->time));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = new UserStatModel();
        $model->yesterday_keep = $this->yesterdayKeep();
        $model->three_day_keep = $this->threeDayKeep();
        $model->seven_day_keep = $this->sevenDayKeep();
        $model->active_num = $this->activeNum();
        $model->register_num = $this->registerNum();
        $model->time = strtotime(date("Y-m-d"), time());

        $model->save();
    }

    protected function activeNum()
    {
//        return User::whereBetween('logged_at', [$this->yesterday, $this->yesterday + 86399])
//            ->count();

        return User::where('logged_at', '>=', $this->yesterday)
            ->where('logged_at', '<', $this->yesterday + self::ONE_DAY)
            ->count();
    }

    protected function registerNum()
    {
//        return User::whereBetween('created_at', [$this->yesterday, $this->yesterday + 86399])
//            ->count();
        return User::where('created_at', '>=', $this->yesterday)
            ->where('created_at', '<', $this->yesterday + self::ONE_DAY)
            ->count();
    }

    // 次日留存
    protected function yesterdayKeep()
    {
        // 计算昨日留存，需要等待今天过去，因此从前天开始计算

        $start = mktime(0, 0, 0,
            date('m', $this->time), date('d', $this->time) - 2, date('Y', $this->time));

        $end   = $start + self::ONE_DAY;

        return User::where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->where('logged_at', '>=', $this->yesterday)
            ->where('logged_at', '<', $this->yesterday + self::ONE_DAY)
            ->count();
    }

    // 3日留存
    protected function threeDayKeep()
    {
        $start = mktime(0, 0, 0,
            date('m', $this->time), date('d', $this->time) - 3, date('Y', $this->time));

        $end   = $start + self::ONE_DAY;

        return User::where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->where('logged_at', '>=', $this->yesterday)
            ->where('logged_at', '<', $this->yesterday + self::ONE_DAY)
            ->count();
    }

    // 7日留存
    protected function sevenDayKeep()
    {
        $start = mktime(0, 0, 0,
            date('m', $this->time), date('d', $this->time) - 7, date('Y', $this->time));

        $end   = $start + self::ONE_DAY;

        return User::where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->where('logged_at', '>=', $this->yesterday)
            ->where('logged_at', '<', $this->yesterday + self::ONE_DAY)
            ->count();
    }
}
