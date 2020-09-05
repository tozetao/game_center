<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropOrderBody extends Migration
{
    public $table = 'prop_order_body';

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
            $table->integer('prop_id');
            $table->string('prop_name', 30)->nullable(false);

            $table->integer('prop_order_id')->nullable(false);
            $table->integer('price')->nullable(false);
            $table->string('image', 200);

            $table->index('prop_order_id');
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
