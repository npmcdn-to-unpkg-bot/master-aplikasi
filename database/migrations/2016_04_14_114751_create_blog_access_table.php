<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_access', function (Blueprint $table) {
            $table->increments('id');
			$table->string('account',255)->nullable();
			$table->string('access_token',255)->nullable();
			$table->integer('idUser')->nullable();
            $table->nullableTimestamps();
        });
		Schema::table('blog_access', function (Blueprint $table) {
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
		Schema::table('blog_access', function (Blueprint $table) {
            $table->dropForeign('blog_access_iduser_foreign');
        });
        Schema::drop('blog_access');
    }
}
