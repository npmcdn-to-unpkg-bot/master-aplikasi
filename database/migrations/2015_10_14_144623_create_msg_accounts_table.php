<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsgAccountsTable extends Migration
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
			$table->string('phone',50)->unique();
			$table->tinyInteger('voice')->nullable();
			$table->integer('idUser')->nullable();
            $table->nullableTimestamps();
        });
		Schema::table('msg_accounts', function (Blueprint $table) {
            $table->integer('idUser')->unsigned()->change();
            $table->foreign('idUser')
      		->references('id')->on('users')
      		->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('msg_accounts', function (Blueprint $table) {
            $table->dropUnique('msg_accounts_phone_unique');
        });
		Schema::table('msg_accounts', function (Blueprint $table) {
            $table->dropForeign('msg_accounts_iduser_foreign');
        });
        Schema::drop('msg_accounts');
    }
}
