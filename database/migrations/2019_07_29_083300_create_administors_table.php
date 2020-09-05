<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //id, name, role_id, created_at, logged_in.
        Schema::create('administrators', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->engine = 'innodb';

            $table->increments('id');
            $table->string('account', 20);
            $table->integer('role_id');
            $table->string('password', 255)->nullable(false);
            $table->integer('created_at')->default(0);
            $table->integer('creator_id')->default(0);
            $table->integer('logged_in')->default(0);
            $table->string('remember_token', 255);

            $table->index('account');
        });

        DB::statement("alter table administrators AUTO_INCREMENT = 1000");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrators');
    }
}
