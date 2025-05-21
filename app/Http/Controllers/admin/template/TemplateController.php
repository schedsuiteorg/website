<?php

namespace App\Http\Controllers\admin\template;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\template\Template;
use App\Models\template\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{

    // For REST API
    public function get(Request $request)
    {
        $data = Template::with('category')->get();
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

    public function getGroupByCat(Request $request)
    {
        $templates = Template::with('category')->get();

        if ($templates->isNotEmpty()) {
            $grouped = $templates->groupBy(function ($template) {
                return $template->category->name ?? 'Uncategorized';
            });

            // Format the grouped data
            $formatted = $grouped->map(function ($items, $categoryName) {
                return [
                    'category' => $categoryName,
                    'templates' => $items->values(), // Reset the keys
                ];
            })->values(); // Reset outer keys

            return response()->json([
                'status' => true,
                'message' => 'Templates grouped by category',
                'data' => $formatted,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No templates found',
            ]);
        }
    }

    public function getTemplatesByCategory(Request $request)
    {
        $category = TemplateCategory::where('slug', $request->slug)->firstOrFail();
        $templates = $category->templates()->where('status', true)->get();
        if ($templates->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'Data found successfully',
                'data' => $templates,
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

        $data = Template::where('id', $request->id)->first();
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
            'template_category_id' => 'required|exists:template_categories,id',
            'folder_name' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'default_content' => 'nullable|string',
            'default_styles' => 'nullable|string',
            'status' => 'nullable',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        // Handle File Upload for Thumbnail (if exists)
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = FileUploadHelper::singleUpload($request->file('thumbnail'), 'thumbnails');
        }

        // Create the template
        $data = new Template();
        $data->template_category_id = $request->template_category_id;
        $data->name = $request->name;
        $data->folder_name = $request->folder_name;
        $data->thumbnail = $thumbnailPath;
        $data->default_content = $request->default_content;
        $data->default_styles = $request->default_styles;
        $data->status = (bool)$request->status ?? false;

        // Save the template and check if it's successful
        if ($data->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Template created successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data creation failed',
            ]);
        }
    }

    public function update(Request $request)
    {

        // Request Validation
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:templates,id',
            'template_category_id' => 'required|exists:template_categories,id',
            'folder_name' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'default_content' => 'nullable|string',
            'default_styles' => 'nullable|string',
            'status' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $data = Template::where('id', $request->id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found!!',
            ]);
        }

        // Delete the existing thumbnail if it exists and is different from the new file
        if ($request->hasFile('thumbnail') && $data->thumbnail) {
            FileUploadHelper::delete($data->thumbnail); // Delete old thumbnail
        }

        // Handle File Upload for Thumbnail (if exists)
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = FileUploadHelper::singleUpload($request->file('thumbnail'), 'thumbnails'); // Upload the new file
        }


        // Update the template
        $data->template_category_id = $request->template_category_id;
        $data->name = $request->name;
        $data->folder_name = $request->folder_name;
        $data->thumbnail = $thumbnailPath ?? $data->thumbnail;
        $data->default_content = $request->default_content;
        $data->default_styles = $request->default_styles;
        $data->status = (bool)$request->status ?? false;

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
            'id' => 'required|exists:templates,id',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }
        $data = Template::where('id', $request->id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Not Found!!',
            ]);
        }

        if ($data->thumbnail) {
            FileUploadHelper::delete($data->thumbnail);
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
