<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->increments('id');
			$table->string('judul',255)->nullable();
			$table->string('slug',255)->nullable();
			$table->string('tipe_post',10)->default('post');
			$table->string('tipe_konten',10)->default('text');
			//$table->enum('tipe_post', ['post', 'page'])->default('post');
			//$table->enum('tipe_konten', ['text', 'gallery'])->default('text');
			$table->dateTime('tanggal')->nullable();
			$table->longText('konten')->nullable();
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
        Schema::drop('blog_posts');
    }
}
