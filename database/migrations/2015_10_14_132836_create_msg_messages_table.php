<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsgMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msg_messages', function (Blueprint $table) {
            $table->increments('id');
			$table->string('address',50)->nullable();
			$table->dateTime('date')->nullable();
			$table->longText('body')->nullable();
			$table->tinyInteger('type')->nullable();
			$table->dateTime('date_sent')->nullable();
			$table->tinyInteger('read')->nullable();
			$table->dateTime('readable_date')->nullable();
			$table->string('phone',50)->nullable();
			$table->tinyInteger('archived')->nullable();
			$table->integer('idUser')->nullable();
            $table->nullableTimestamps();
        });
		Schema::table('msg_messages', function (Blueprint $table) {
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
		Schema::table('msg_messages', function (Blueprint $table) {
            $table->dropForeign('msg_messages_iduser_foreign');
        });
        Schema::drop('msg_messages');
    }
}
