<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('props', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('prop_id');
            $table->integer('type')->nullable(false);
            $table->integer('price')->nullable(false);
            $table->tinyInteger('tag')->nullable(false);
            $table->string('prop_name', 50)->nullable(false);
            $table->string('remarks', 50)->nullable(false);
            $table->string('image', 200)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('props');
    }
}
