<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['role_name' => 'admin', 'role_slug' => 'admin', 'status' => true],
            ['role_name' => 'vendor_owner', 'role_slug' => 'vendor-owner', 'status' => true],
            ['role_name' => 'vendor_staff', 'role_slug' => 'vendor-staff', 'status' => true],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['role_name' => $role['role_name']], $role);
        }
    }
}
