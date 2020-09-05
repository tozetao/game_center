<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropOrder extends Migration
{
    public $table = 'prop_order';

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
            $table->string('number', 30)->nullable(false);
            $table->tinyInteger('status')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('created_at')->nullable(false);
            $table->integer('pay_at')->nullable(false);
            $table->tinyInteger('payment_type')->nullable(false);

            $table->index('number');
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
