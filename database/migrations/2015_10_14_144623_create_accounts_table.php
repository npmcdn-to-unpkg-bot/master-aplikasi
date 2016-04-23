<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msg_accounts', function (Blueprint $table) {
            $table->increments('id');
			$table->string('service',50)->nullable();
			$table->string('api_key',100)->nullable();
			$table->string('api_secret',100)->nullable();
			$table->string('phone',50)->nullable();
			$table->tinyInteger('voice')->nullable();
			$table->integer('idUser')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('msg_accounts');
    }
}
