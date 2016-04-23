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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mail_attachments');
    }
}
