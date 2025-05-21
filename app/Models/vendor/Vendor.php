<?php

namespace App\Models\vendor;


use App\Models\auth\User;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{

    protected $table = 'vendors';

    protected $fillable = [
        'business_name',
        'email',
        'phone',
        'logo',
        'category_type',
        'website_slug',
        'status'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function websiteSettings()
    {
        return $this->hasOne(VendorWebsiteSetting::class);
    }
}
