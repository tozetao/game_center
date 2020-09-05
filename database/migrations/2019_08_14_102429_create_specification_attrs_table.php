<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecificationAttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specification_attrs', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('spec_id');
            $table->integer('category_id')->nullable(false);
            $table->string('spec_name', 10)->nullable(false);
            $table->string('attr_values', 200)->nullable(false);
            $table->tinyInteger('is_mulit')->default(0);
            $table->tinyInteger('is_custom')->default(0);
        });

        DB::statement('alter table specification_attrs AUTO_INCREMENT = 100');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specification_attrs');
    }
}