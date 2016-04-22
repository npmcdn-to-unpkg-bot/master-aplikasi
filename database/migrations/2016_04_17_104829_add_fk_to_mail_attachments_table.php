<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkToMailAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }
}
