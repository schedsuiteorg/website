<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Models\auth\User;
use App\Models\auth\Vendor;
use App\Models\permissions\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // For REST API
    public function get(Request $request)
    {
        $data = User::with(['role.permissions', 'vendor.websiteSettings'])->get();
        if ($data->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'Data found successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
            ]);
        }
    }

    public function login(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ]);
        }

        // if ($user->status !== 'active') {
        //     return response()->json(['success' => false, 'message' => 'User is inactive.']);
        // }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6',
            'vendor_id' => 'nullable|exists:vendors,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'otp' => null,
            'role_id' =>  $request->role_id ?? null,
            'vendor_id' =>  $request->vendor_id ?? null,
            'status' => 'inactive',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['status' => true, 'message' => 'Logged out']);
    }

    public function detail(Request $request)
    {
        try {
            // Find the user by ID
            $user =  $request->user();

            if ($user) {
                return response()->json([
                    'status' => true,
                    'message' => 'User found successfully',
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request)
    {
        // Request validation to ensure role_id and user_id are provided and valid
        $validation = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Validate user exists
            'role_id' => 'required|exists:roles,id', // Validate role exists
        ]);

        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        // Fetch the user and role based on the provided IDs
        $user = User::with(['role', 'vendor'])->find($request->user_id);
        $role = Role::find($request->role_id);

        // Check if the user already has this role (optional)
        if ((int)$user->role_id == (int)$role->id) {
            return response()->json([
                'status' => false,
                'message' => 'User already has this role',
            ]);
        }

        // Assign the role to the user
        $user->role_id = $role->id; // Attach the role using the relationship

        if ($user->save()) {
            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Role assigned to user successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to assign role to user',
            ]);
        }
    }
}
