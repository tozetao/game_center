<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'orders';

        Schema::create($table, function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('order_id');
            $table->integer('uid')->nullable(false);
            $table->string('order_number', 40)->nullable(false);
            $table->integer('goods_id')->nullable(false);
            $table->string('title', 40)->nullable(false);
            $table->string('image', 200)->nullable(false);
            $table->integer('price')->nullable(false);
            $table->integer('discount_price')->nullable(false);
            $table->integer('points')->nullable(false);
            $table->tinyInteger('payment_type')->nullable(false);
            $table->integer('category_id')->nullable(false);
            $table->integer('quantity')->nullable(false);
            $table->tinyInteger('status')->nullable(false);

            $table->integer('paid_at')->nullable(false);
            $table->integer('created_at')->nullable(false);
            $table->integer('updated_at')->nullable(false);
            $table->tinyInteger('delivery_status')->nullable(false);
            $table->string('express_number', 30)->nullable(false);
            $table->string('express_name', 20)->nullable(false);

            $table->index(['order_number', 'goods_id']);
            $table->index('uid');
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
        Schema::dropIfExists('orders');
    }
}
