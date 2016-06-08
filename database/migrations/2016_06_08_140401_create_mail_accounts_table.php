<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_accounts', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name',50)->nullable();
			$table->string('email',50)->unique();
			$table->integer('idUser')->nullable();
            $table->nullableTimestamps();
        });
		Schema::table('mail_accounts', function (Blueprint $table) {
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
        Schema::table('mail_accounts', function (Blueprint $table) {
            $table->dropUnique('mail_accounts_email_unique');
        });
		Schema::table('mail_accounts', function (Blueprint $table) {
            $table->dropForeign('mail_accounts_iduser_foreign');
        });
        Schema::drop('mail_accounts');
    }
}
