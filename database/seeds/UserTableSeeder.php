<?php namespace App\database\seeds;

use Illuminate\Database\Seeder;

use App\User;
use DB;
use Hash;

class UserTableSeeder extends Seeder {

	public function run()
	{
		
		DB::table('users')->delete();

		User::create([
			'name'		=> 'admin',
			'email' 	=> 'admin@whatthetag.com',
			'password'	=> Hash::make('whatthetag'),
			'role'		=> 'admin',
		]);
	}

}