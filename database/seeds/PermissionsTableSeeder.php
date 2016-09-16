<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');

        $object = new Permission();
        $object->name = 'create-invoice';
        $object->display_name = 'Create Invoices';
        $object->description = 'Create new invoices';

        $object = new Permission();
        $object->name = 'edit-invoice';
        $object->display_name = 'Edit Invoices';
        $object->description = 'Edit existing invoices';

        $object = new Permission();
        $object->name = 'delete-invoice';
        $object->display_name = 'Delete Invoices';
        $object->description = 'Delete existing invoices';



    }
}
