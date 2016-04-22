<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
		Model::unguard();
		
        //$this->call(UserTableSeeder::class);
		
		DB::table('users')->truncate();
		DB::table('users')->insert([
            	'name' => 'Budi',
            	'email' => 'aku@budi.my.id',
            	'password' => bcrypt('budi123'),
				'active' => 1,
        	]);
		
        Model::reguard();
    }
}
