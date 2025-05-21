<?php

namespace App\Http\Controllers\admin\role_permission;

use App\Http\Controllers\Controller;
use App\Models\auth\User;
use App\Models\auth\Vendor;
use App\Models\permissions\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    // For REST API
    public function get(Request $request)
    {
        $data = Permission::get();
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

    public function detail(Request $request)
    {

        if (!$request->id) {
            return response()->json([
                'status' => false,
                'message' => 'id is required',
            ]);
        }

        $data = Permission::where('id', $request->id)->first();
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data Found!!',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found!!',
            ]);
        }
    }

    public function create(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'page' => 'required|string',
            'page_route' => 'required|string|max:255|unique:permissions,page_route',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $data = new Permission;
        $data->name = $request->name;
        $data->page = $request->page;
        $data->page_route = $request->page_route;
        if ($data->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Data creation successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data creation fail',
            ]);
        }
    }

    public function update(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:permissions,id',
            'name' => 'required|string',
            'page' => 'required|string',
            'page_route' => 'required|string|max:255',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $data = Permission::where('id', $request->id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found!!',
            ]);
        }


        if ($request->name) {
            $data->name = $request->name;
        }
        if ($request->page) {
            $data->page = $request->page;
        }
        if ($request->page_route) {
            $data->page_route = $request->page_route;
        }

        if ($data->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Data Update Successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Update Fail',
            ]);
        }
    }

    public function destroy(Request $request)
    {

        // Request Validation
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:permissions,id',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }
        $data = Permission::where('id', $request->id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found!!',
            ]);
        }
        if ($data->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Data Delete Successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Delete Fail',
            ]);
        }
    }
}
