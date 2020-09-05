<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addr', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->integer('uid')->nullable(false);
            $table->integer('province_id')->nullable(false);
            $table->integer('city_id')->nullable(false);
            $table->integer('area_id')->nullable(false);
            $table->string('province', 15)->nullable(false);
            $table->string('city', 15)->nullable(false);
            $table->string('area', 15)->nullable(false);
            $table->string('info', 150)->nullable(false);

            $table->string('phone', 12)->nullable(false);
            $table->string('addressee', 12)->nullable(false);

            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addr');
    }
}
