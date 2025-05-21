<?php

namespace App\Http\Controllers\admin\vendor;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\vendor\Vendor;
use App\Models\vendor\VendorWebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{

    // For REST API
    public function get(Request $request)
    {
        $data = Vendor::get();
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

        $data = Vendor::with(['websiteSettings', 'users'])->where('id', $request->id)->first();
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

    public function registerVendor(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'business_name' => 'required|string',
            'website_slug' => 'required|string',
            'category_type' => 'required|in:medical,driving_school,massage,other',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|unique:vendors,phone',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = FileUploadHelper::singleUpload($request->file('logo'), 'logo');
        }

        // Create the Vendor
        $data = new Vendor();
        $data->business_name = $request->business_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->category_type = $request->category_type;
        $data->website_slug = $request->website_slug;
        $data->logo = $logoPath;
        $data->status = false;

        // Save the Vendor and check if it's successful
        if ($data->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Vendor created successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data creation failed',
            ]);
        }
    }

    public function registerTemplate(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'template_id' => 'required|exists:templates,id',
            "logo" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "favicon" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "colors" => 'nullable|string',
            "custom_content" => 'nullable|string',
            "domain" => 'nullable|string',
            "subdomain" => 'nullable|string',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = FileUploadHelper::singleUpload($request->file('logo'), 'logo');
        }

        $faviconPath = null;
        if ($request->hasFile('favicon')) {
            $faviconPath = FileUploadHelper::singleUpload($request->file('favicon'), 'favicon');
        }

        $data = VendorWebsiteSetting::updateOrCreate(
            ['vendor_id' => $request->vendor_id],
            [
                'vendor_id' => $request->vendor_id,
                'template_id' => $request->template_id,
                'logo' => $logoPath ?? null,
                'faviconPath' => $faviconPath ?? null,
                'colors' => $request->colors ?? null,
                'custom_content' => $request->custom_content ?? null,
                'domain' => $request->domain ?? null,
                'subdomain' => $request->subdomain ?? null,
                'status' => $request->status ?? false,
            ]
        );

        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Vendor Template created successfully',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data creation failed',
            ]);
        }
    }

    public function getVendorDetail(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $detail = Vendor::where('id', $request->vendor_id)->with(['users', 'websiteSettings'])->first();

        if ($detail) {
            return response()->json([
                'status' => true,
                'message' => 'Vendor detail found!',
                'data' => $detail,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Vendor found',
            ]);
        }
    }

    public function publishTemplate(Request $request)
    {
        // Request Validation
        $validation = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'status' => 'required|boolean',
        ]);
        if ($validation->fails()) {
            return $this->validationResponse($validation);
        }

        $vendorTemplate = VendorWebsiteSetting::where('vendor_id', $request->vendor_id)->first();

        if (!$vendorTemplate) {
            return response()->json(['status' => false, 'message' => 'No template to publish'], 404);
        }

        $vendorTemplate->status = (bool)$request->status ?? false;
        $vendorTemplate->save();

        return response()->json(['status' => true, 'message' => ((bool)$request->status) ? "Template published" : "Template unpublished"]);
    }
}
