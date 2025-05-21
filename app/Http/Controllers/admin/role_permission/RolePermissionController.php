<?php

namespace App\Http\Controllers\admin\role_permission;

use App\Http\Controllers\Controller;
use App\Models\permissions\Role;
use App\Models\permissions\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    // Get all roles with their permissions
    public function get()
    {
        // Fetch all roles with their related permissions (using eager loading)
        $roles = Role::with('permissions')->get();

        if ($roles->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'Roles and permissions fetched successfully',
                'data' => $roles,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No roles found',
            ]);
        }
    }

    // Get details of a specific role permission
    public function detail(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'status' => false,
                'message' => 'Permission id is required',
            ]);
        }
        $data = Role::with('permissions')->find($request->id);
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data Found!',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found!',
            ]);
        }
    }

    // Create a new role and assign permissions
    public function create(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'role_name' => 'required|string',
            'role_slug' => 'required|string|unique:roles,role_slug',
            'status' => 'required|boolean',
            'permissions' => 'required|array', // Array of permission ids
            'permissions.*' => 'exists:permissions,id', // Validate each permission id exists
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        // Create the new role
        $role = new Role();
        $role->role_name = $request->role_name;
        $role->role_slug = $request->role_slug;
        $role->status = $request->status;

        if ($role->save()) {
            // Attach the selected permissions to the new role
            foreach ($request->permissions as $permission_id) {
                RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }

            // Return the created role with its permissions
            return response()->json([
                'status' => true,
                'message' => 'Role created and permissions assigned successfully',
                'data' => $role->load('permissions'),  // Eager load the permissions relationship
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create role',
            ]);
        }
    }

    // Update a role and its permissions
    public function update(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'role_name' => 'required|string',
            'role_slug' => 'required|string|unique:roles,role_slug,' . $request->role_id,
            'status' => 'required|boolean',
            'permissions' => 'required|array', // Array of permission ids
            'permissions.*' => 'exists:permissions,id', // Validate each permission id exists
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        // Find the role to update
        $role = Role::find($request->role_id);
        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found',
            ]);
        }

        // Update the role details
        $role->role_name = $request->role_name;
        $role->role_slug = $request->role_slug;
        $role->status = $request->status;

        if ($role->save()) {
            // Remove existing permissions for this role
            RolePermission::where('role_id', $role->id)->delete();

            // Attach the new permissions
            foreach ($request->permissions as $permission_id) {
                RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }

            // Return the updated role with its permissions
            return response()->json([
                'status' => true,
                'message' => 'Role updated and permissions assigned successfully',
                'data' => $role->load('permissions'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update role',
            ]);
        }
    }

    // Delete a role and its associated permissions
    public function destroy(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        // Find and delete the role
        $role = Role::find($request->role_id);
        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found',
            ]);
        }

        // Delete the associated permissions
        RolePermission::where('role_id', $role->id)->delete();

        // Delete the role
        if ($role->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Role and associated permissions deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete role',
            ]);
        }
    }
}
