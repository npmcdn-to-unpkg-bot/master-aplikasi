<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('msg_messages');
    }
}
