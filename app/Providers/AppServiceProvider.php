<?php

namespace App\Providers;

use App\Models\vendor\Vendor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (request()->getHost() !== config('app.domain')) {
            $subdomain = explode('.', request()->getHost())[0];

            $vendor = Vendor::where('slug', $subdomain)->first();

            if ($vendor) {
                app()->instance('current_vendor', $vendor);
            }
        }
    }
}
