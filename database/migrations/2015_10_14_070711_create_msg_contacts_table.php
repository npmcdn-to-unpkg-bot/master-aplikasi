<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsgContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msg_contacts', function (Blueprint $table) {
            $table->increments('id');
			$table->string('nama', 100);
			$table->string('phone', 100);
			$table->integer('idUser');
            $table->nullableTimestamps();
        });
		Schema::table('msg_contacts', function (Blueprint $table) {
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
		Schema::table('msg_contacts', function (Blueprint $table) {
            $table->dropForeign('msg_contacts_iduser_foreign');
        });
        Schema::drop('msg_contacts');
    }
}
