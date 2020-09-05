<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'users';

        Schema::create($table, function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('uid');
            $table->string('nickname', 50);
            $table->string('openid', 50)->nullable(false);
            $table->string('icon', 300)->nullable(false);
            $table->integer('last_online_time')->nullable(false);
            $table->integer('created_at')->default(0);
            $table->integer('logged_at')->default(0);
            $table->integer('points')->default(0);
            $table->integer('revive_times')->default(0);
            $table->tinyInteger('sex')->nullable(false)->default(0);

            $table->index('openid');
        });

        DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 10000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
