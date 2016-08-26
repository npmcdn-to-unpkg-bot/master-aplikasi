<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_tmp', function (Blueprint $table) {
            $table->increments('id');
			$table->string('file',255);
			$table->string('key',255);
			$table->integer('idUser');
            $table->nullableTimestamps();
        });
		Schema::table('blog_tmp', function (Blueprint $table) {
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
		Schema::table('blog_tmp', function (Blueprint $table) {
            $table->dropForeign('blog_tmp_iduser_foreign');
        });
        Schema::drop('blog_tmp');
    }
}
