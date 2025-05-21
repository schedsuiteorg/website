<?php

namespace App\Http\Controllers\website\vendor;

use App\Http\Controllers\Controller;
use App\Models\vendor\Vendor;

class VendorSiteController extends Controller
{
    public function index($slug)
    {
        $vendor = Vendor::where('website_slug', $slug)->firstOrFail();

        $settings = $vendor->websiteSettings;

        $template = $settings->template;

        return view('templates.' . $template->folder_name . '.index', [
            'content' => json_decode($settings->content_json, true),
            'logo' => $settings->logo,
            'color' => $settings->colors,
        ]);
    }
}
