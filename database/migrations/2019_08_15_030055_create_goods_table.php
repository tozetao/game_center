<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->string('title', 60)->nullable(false);
            $table->string('desc', 200)->nullable(false);
            $table->string('image', 200)->nullable(false);
            $table->integer('price')->nullable(false);
            $table->integer('discount_price')->nullable(false);
            $table->tinyInteger('is_exchange')->nullable(false);
            $table->integer('points')->nullable(false);
            $table->tinyInteger('on_sale')->nullable(false);
            $table->integer('category_id')->nullable(false);
            $table->integer('stock')->nullable(false);
            $table->integer('created_at')->nullable(false);
            $table->string('goods_number')->nullable(false);
            $table->integer('postage_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
