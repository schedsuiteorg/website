<?php

use App\Http\Controllers\admin\auth\AuthController;
use App\Http\Controllers\admin\role_permission\RolePermissionController;
use App\Http\Controllers\admin\role_permission\PermissionController;
use App\Http\Controllers\admin\template\TemplateCategoryController;
use App\Http\Controllers\admin\template\TemplateController;
use App\Http\Controllers\admin\vendor\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['cors']], function () {

    // Auth
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::get('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/detail', [AuthController::class, 'detail']);
        Route::post('/auth/assign-role', [AuthController::class, 'assignRole']);
        
        // User
        Route::get('/user', [AuthController::class, 'get']);
        
        // Permissions
        Route::get('permissions', [PermissionController::class, 'get']);
        Route::post('permissions/detail', [PermissionController::class, 'detail']);
        Route::post('permissions/create', [PermissionController::class, 'create']);
        Route::post('permissions/update', [PermissionController::class, 'update']);
        Route::post('permissions/destroy', [PermissionController::class, 'destroy']);

        // Role & Permissions
        Route::get('role/', [RolePermissionController::class, 'get']);
        Route::post('role/detail', [RolePermissionController::class, 'detail']);
        Route::post('role/create', [RolePermissionController::class, 'create']);
        Route::post('role/update', [RolePermissionController::class, 'update']);
        Route::post('role/destroy', [RolePermissionController::class, 'destroy']);

        // ------------------------------------------------------------------------

        // Template Category
        Route::get('template/category', [TemplateCategoryController::class, 'get']);
        Route::post('template/category/detail', [TemplateCategoryController::class, 'detail']);
        Route::post('template/category/create', [TemplateCategoryController::class, 'create']);
        Route::post('template/category/update', [TemplateCategoryController::class, 'update']);
        Route::post('template/category/destroy', [TemplateCategoryController::class, 'destroy']);

        // Templates
        Route::get('template', [TemplateController::class, 'get']);
        Route::get('template/category-wise', [TemplateController::class, 'getGroupByCat']);
        Route::post('template/by-category', [TemplateController::class, 'getTemplatesByCategory']);
        Route::post('template/detail', [TemplateController::class, 'detail']);
        Route::post('template/create', [TemplateController::class, 'create']);
        Route::post('template/update', [TemplateController::class, 'update']);
        Route::post('template/destroy', [TemplateController::class, 'destroy']);

        // Vendor
        Route::get('vendor', [VendorController::class, 'get']);
        Route::post('vendor/detail', [VendorController::class, 'getVendorDetail']);
        Route::post('vendor/register', [VendorController::class, 'registerVendor']);
        Route::post('vendor/reg-template', [VendorController::class, 'registerTemplate']);
        Route::post('vendor/template/publish', [VendorController::class, 'publishTemplate']);


        Route::middleware('role:super_admin,admin')->group(function () {
            // Admin-related routes
        });

        Route::middleware('role:vendor,vendor_staff')->group(function () {
            // Vendor-related routes
        });
    });


    Route::middleware(['auth:sanctum'])->group(function () {});
});
