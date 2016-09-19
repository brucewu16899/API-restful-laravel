<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class OAuthClientTableSeeder extends Seeder
{
    public function run()
    {
        //create website client secret

        \App\OAuthClient::create([
            'id' => 'g3b259fde3ed9ff3843839b',
            'secret' => '3d7f5f8f793d59c25502c0ae8c4a95b',
            'name' => 'Android'
    	]);

        \App\OAuthClient::create([
        	'id' => 'f3d259ddd3ed8ff3843839b',
        	'secret' => '4c7f6f8fa93d59c45502c0ae8c4a95b',
        	'name' => 'website'
    	]);
    }
}
