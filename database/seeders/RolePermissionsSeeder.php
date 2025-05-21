<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RolePermissionsSeeder extends Seeder
{
    public function run()
    {
        // Example: admin role gets all permissions
        $adminRoleId = DB::table('roles')->where('role_name', 'admin')->value('id');
        $allPermissionIds = DB::table('permissions')->pluck('id')->toArray();

        foreach ($allPermissionIds as $permissionId) {
            DB::table('role_permissions')->updateOrInsert([
                'role_id' => $adminRoleId,
                'permission_id' => $permissionId,
            ]);
        }

        // Example: vendor_owner gets limited permissions
        $vendorOwnerRoleId = DB::table('roles')->where('role_name', 'vendor_owner')->value('id');
        $vendorOwnerPermissions = DB::table('permissions')->whereIn('name', [
            'view_vendors',
            'manage_bookings',
        ])->pluck('id')->toArray();

        foreach ($vendorOwnerPermissions as $permissionId) {
            DB::table('role_permissions')->updateOrInsert([
                'role_id' => $vendorOwnerRoleId,
                'permission_id' => $permissionId,
            ]);
        }

        // vendor_staff can have even fewer or different permissions - add if needed
    }
}
