<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_attachments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('email_id');
			$table->string('public_id',255)->nullable();
			$table->string('version',255)->nullable();
			$table->string('signature',255)->nullable();
			$table->string('resource_type',255)->nullable();
			$table->string('bytes',255)->nullable();
			$table->string('type',255)->nullable();
			$table->string('etag',255)->nullable();
			$table->string('url',255)->nullable();
			$table->string('secure_url',255)->nullable();
			$table->string('original_filename',255)->nullable();
			$table->integer('idUser');
            $table->nullableTimestamps();
        });
		Schema::table('mail_attachments', function (Blueprint $table) {
            $table->integer('idUser')->unsigned()->change();
            $table->foreign('idUser')
      		->references('id')->on('users')
      		->onDelete('cascade')->onUpdate('cascade');
			$table->integer('email_id')->unsigned()->change();
            $table->foreign('email_id')
      		->references('id')->on('mail_emails')
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
		Schema::table('mail_attachments', function (Blueprint $table) {
            $table->dropForeign('mail_attachments_iduser_foreign');
			$table->dropForeign('mail_attachments_email_id_foreign');
        });
        Schema::drop('mail_attachments');
    }
}
