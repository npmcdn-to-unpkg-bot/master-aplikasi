<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsgSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msg_settings', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name',50)->nullable();
			$table->string('value',50)->nullable();
			$table->integer('idUser')->nullable();
            $table->nullableTimestamps();
        });
		Schema::table('msg_settings', function (Blueprint $table) {
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
		Schema::table('msg_settings', function (Blueprint $table) {
            $table->dropForeign('msg_settings_iduser_foreign');
        });
        Schema::drop('msg_settings');
    }
}
