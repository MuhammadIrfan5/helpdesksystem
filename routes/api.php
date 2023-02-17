<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login',[UserController::class,'login'])->name('login');
Route::post('company_login',[\App\Http\Controllers\Company\CompanyController::class,'login'])->name('company_login');
Route::post('employee_login',[\App\Http\Controllers\Company\EmployeeController::class,'login'])->name('employee_login');

Route::prefix('company')->namespace('company')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('company_create',[\App\Http\Controllers\Company\CompanyController::class,'store'])->name('company_create');
    Route::post('block_unblock_company_account',[\App\Http\Controllers\Company\CompanyController::class,'block_unblock_company_account'])->name('block_unblock_company_account');
});
Route::prefix('admin/country')->namespace('country')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('country_create',[\App\Http\Controllers\CommonAPI\CountryController::class,'store'])->name('company_create');
    Route::post('update',[\App\Http\Controllers\CommonAPI\CountryController::class,'update'])->name('company_update');
    Route::post('delete_country',[\App\Http\Controllers\CommonAPI\CountryController::class,'destroy'])->name('company_delete');
});
Route::prefix('admin/city')->namespace('city')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('city_create',[\App\Http\Controllers\CommonAPI\CityController::class,'store'])->name('company_create');
    Route::post('show_all',[\App\Http\Controllers\CommonAPI\CityController::class,'index'])->name('company_create');
    Route::post('update',[\App\Http\Controllers\CommonAPI\CityController::class,'update'])->name('company_create');
    Route::post('delete_city',[\App\Http\Controllers\CommonAPI\CityController::class,'destroy'])->name('company_create');
});
Route::prefix('Employee')->namespace('employee')->middleware(['auth:sanctum','company','check_status'])->group(function () {
    Route::post('employee_create',[\App\Http\Controllers\Company\EmployeeController::class,'store'])->name('employee_create')->middleware(['limit_check']);
});

Route::prefix('admin/role')->namespace('role')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
//    ROLES ROUTES
    Route::post('create', [\App\Http\Controllers\CommonAPI\RoleController::class, 'store'])->name('create');
    Route::get('show_all', [\App\Http\Controllers\CommonAPI\RoleController::class, 'index'])->name('show_all');
    Route::delete('delete/{uuid}', [\App\Http\Controllers\CommonAPI\RoleController::class, 'destroy'])->name('delete');
    Route::post('update', [\App\Http\Controllers\CommonAPI\RoleController::class, 'update'])->name('update');
});
Route::prefix('admin/type')->namespace('employeetype')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
//    EMPLOYEE TYPE ROUTES
    Route::post('create_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'store'])->name('create_type');
    Route::get('show_all_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'index'])->name('show_all_type');
    Route::delete('delete_type/{uuid}',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'destroy'])->name('delete_type');
    Route::post('update_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'update'])->name('update_type');
});

Route::prefix('admin/packages')->namespace('employeetype')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
//    PACKAGES ROUTES
    Route::get('show_all_packages',[\App\Http\Controllers\CommonAPI\PackageController::class,'index'])->name('show_all_packages');
});
