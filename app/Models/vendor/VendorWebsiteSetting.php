<?php


namespace App\Models\vendor;

use App\Models\template\Template;
use Illuminate\Database\Eloquent\Model;

class VendorWebsiteSetting extends Model
{
    protected $table = 'vendor_website_settings';

    protected $fillable = [
        "vendor_id",
        "template_id",
        "logo",
        "favicon",
        "colors",
        "custom_content",
        "domain",
        "subdomain",
        "status",
    ];


    protected $casts = [
        'custom_styles' => 'array',
        'status' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
