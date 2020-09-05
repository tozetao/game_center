<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyResurrection extends Migration
{
    protected $table = 'apply_resurrection';

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
            $table->integer('master_id')->nullable(false);
            $table->integer('slave_id')->nullable(false);
            $table->tinyInteger('status')->nullable(false);

            $table->index(['master_id', 'slave_id']);
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