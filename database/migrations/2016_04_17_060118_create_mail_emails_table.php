<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_emails', function (Blueprint $table) {
            $table->increments('id');
			$table->string('recipient',255)->nullable();
			$table->string('sender',255)->nullable();
			$table->string('from',255)->nullable();
			$table->string('subject',255)->nullable();
			$table->longText('body_plain')->nullable();
			$table->longText('stripped_text')->nullable();
			$table->string('stripped_signature',255)->nullable();
			$table->longText('body_html')->nullable();
			$table->longText('stripped_html')->nullable();
			$table->integer('attachment_count')->nullable();
			$table->string('attachment_x',255)->nullable();
			$table->integer('timestamp')->nullable();
			$table->string('signature',255)->nullable();
			$table->longText('message_headers')->nullable();
			$table->string('content_id_map',255)->nullable();
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
        Schema::drop('mail_emails');
    }
}
