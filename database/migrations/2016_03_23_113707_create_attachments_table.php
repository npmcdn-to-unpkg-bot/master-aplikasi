<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/*
		[public_id] => sample
    	[version] => 1312461204
    	[width] => 864
    	[height] => 576
    	[format] => jpg
    	[bytes] => 120253
    	[url] => http://res.cloudinary.com/demo/image/upload/v1371281596/sample.jpg
    	[secure_url] => https://res.cloudinary.com/demo/image/upload/v1371281596/sample.jpg
		*/
        Schema::create('blog_attachments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('post_id');
			$table->string('public_id',255)->nullable();
			$table->string('version',255)->nullable();
			$table->string('signature',255)->nullable();
			$table->string('width',255)->nullable();
			$table->string('height',255)->nullable();
			$table->string('format',255)->nullable();
			$table->string('resource_type',255)->nullable();
			$table->string('bytes',255)->nullable();
			$table->string('type',255)->nullable();
			$table->string('etag',255)->nullable();
			$table->string('url',255)->nullable();
			$table->string('secure_url',255)->nullable();
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
        Schema::drop('blog_attachments');
    }
}
