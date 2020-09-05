<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_setting', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->integer('master_id')->nullable(false);
            $table->string('my_name', 30)->default('');
            $table->string('other_name', 200)->default('');
            $table->integer('hair')->default(0);
            $table->integer('clothes')->default(0);
            $table->tinyInteger('sex')->default(0);
            $table->integer('archive_id')->nullable(false);

            $table->index('archive_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('base_setting');
    }
}
