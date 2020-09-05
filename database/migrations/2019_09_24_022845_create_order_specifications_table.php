<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_specifications', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->integer('order_id')->nullable(false);
            $table->integer('spec_id')->nullable(false);
            $table->string('spec_name', 10)->nullable(false);
            $table->integer('attr_id')->nullable(false);
            $table->string('attr_val', 10)->nullable(false);

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
        Schema::dropIfExists('order_specifications');
    }
}
