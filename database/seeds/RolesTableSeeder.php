<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

use App\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');

        $owner = new Role();
        $owner->name = "owner";
        $owner->display_name = "Owner Project";
        $owner->description = "user is Owner";
        $owner->save();

        $owner = new Role();
        $owner->name = "admin";
        $owner->display_name = "Admin User";
        $owner->description = "user is Admin";
        $owner->save();

    }
}
