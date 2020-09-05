<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStat extends Migration
{
    protected $table = 'user_stat';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->integer('yesterday_keep')->comment('昨日留存');
            $table->integer('three_day_keep')->comment('3日留存');
            $table->integer('seven_day_keep')->comment('7日留存');
            $table->integer('register_num')->comment('昨日注册人数');
            $table->integer('active_num')->comment('昨日活跃人数');
            $table->integer('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
