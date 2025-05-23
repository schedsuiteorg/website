<?php

use App\Http\Controllers\website\vendor\VendorSiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/clear-cache', function() {
//     Artisan::call('cache:clear');
//     Artisan::call('view:clear');
//     Artisan::call('route:clear');
//     Artisan::call('config:clear');

//     return "Caches cleared successfully!";
// });

//Route::get('/', [HomeController::class, 'index'])->name('index');

Route::domain('{vendor}.localhost')->group(function () {
    Route::get('/', [VendorSiteController::class, 'index']);
});
