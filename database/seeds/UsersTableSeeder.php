<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

use App\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        \App\user::create([
        	'name' => 'Simon',
        	'email' => 'nvhien129@gmail.com',
        	'password' => Hash::make('123456')
    	]);
    }
}
