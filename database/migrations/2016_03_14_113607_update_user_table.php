<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
			$table->string('confirmation_code')->after('remember_token')->nullable();
			$table->smallInteger('level')->after('confirmation_code')->default(100);
            $table->smallInteger('active')->after('level')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('confirmation_code');
			$table->dropColumn('level');
			$table->dropColumn('active');
        });
    }
}
