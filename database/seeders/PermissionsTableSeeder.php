<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'view_users', 'page' => 'Users', 'page_route' => '/users'],
            ['name' => 'edit_users', 'page' => 'Users', 'page_route' => '/users/edit'],
            ['name' => 'view_vendors', 'page' => 'Vendors', 'page_route' => '/vendors'],
            ['name' => 'manage_bookings', 'page' => 'Bookings', 'page_route' => '/bookings'],
            // Add more as needed
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(['name' => $permission['name']], $permission);
        }
    }
}
