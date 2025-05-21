<?php

namespace App\Http\Controllers\admin\template;

use App\Http\Controllers\Controller;
use App\Models\template\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateCategoryController extends Controller
{

    // For REST API
    public function get(Request $request)
    {
        $data = TemplateCategory::get();
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

        $data = TemplateCategory::where('id', $request->id)->first();
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
            'slug' => 'required|string|max:255|unique:template_categories,slug',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $data = new TemplateCategory;
        $data->name = $request->name;
        $data->slug = $request->slug;
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
            'id' => 'required|exists:template_categories,id',
            'name' => 'required|string',
            'slug' => 'required|string|max:255',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $data = TemplateCategory::where('id', $request->id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found!!',
            ]);
        }


        if ($request->name) {
            $data->name = $request->name;
        }
        if ($request->slug) {
            $data->slug = $request->slug;
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
            'id' => 'required|exists:template_categories,id',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }
        $data = TemplateCategory::where('id', $request->id)->first();
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
