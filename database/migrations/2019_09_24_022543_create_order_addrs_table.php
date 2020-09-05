<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAddrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_addrs', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->integer('order_id')->nullable(false);
            $table->string('province', 15);
            $table->string('city', 15);
            $table->string('area', 15);
            $table->string('info', 150);
            $table->string('addressee', 12);
            $table->string('phone', 12);

            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_addrs');
    }
}
