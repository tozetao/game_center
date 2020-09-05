<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyPropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_props', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->integer('uid')->nullable(false);
            $table->integer('prop_id')->nullable(false);

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
        Schema::dropIfExists('my_props');
    }
}
