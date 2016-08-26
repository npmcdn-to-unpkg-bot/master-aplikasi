<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_settings', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name',50)->nullable();
			$table->longText('value')->nullable();
			$table->integer('idUser')->nullable();
            $table->timestamps();
        });
		
		Schema::table('blog_settings', function (Blueprint $table) {
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
		Schema::table('blog_settings', function (Blueprint $table) {
            $table->dropForeign('blog_settings_iduser_foreign');
        });
        Schema::drop('blog_settings');
		
    }
}
