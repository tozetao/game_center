<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckpointSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkpoint_setting', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->integer('master_id')->nullable(false);
            $table->string('success_msg', 200)->default('');
            $table->string('fail_msg', 200)->default('');
            $table->string('image', 200)->nullable(false);
            $table->tinyInteger('number')->nullable(false);
            $table->integer('archive_id')->nullable(false);

            $table->string('red_envelope_no', 30)->default('');

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
        Schema::dropIfExists('checkpoint_setting');
    }
}
