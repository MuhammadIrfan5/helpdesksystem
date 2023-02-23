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
    Route::get('show_all_companies',[\App\Http\Controllers\Company\CompanyController::class,'index'])->name('show_all_companies');
});
Route::prefix('admin/country')->namespace('country')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('country_create',[\App\Http\Controllers\CommonAPI\CountryController::class,'store'])->name('country_create');
    Route::post('update_country',[\App\Http\Controllers\CommonAPI\CountryController::class,'update'])->name('update_country');
    Route::post('delete_country',[\App\Http\Controllers\CommonAPI\CountryController::class,'destroy'])->name('delete_country');

});
Route::prefix('admin/city')->namespace('city')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('create_city',[\App\Http\Controllers\CommonAPI\CityController::class,'store'])->name('create_city');
    Route::post('update_city',[\App\Http\Controllers\CommonAPI\CityController::class,'update'])->name('update_city');
    Route::post('delete_city',[\App\Http\Controllers\CommonAPI\CityController::class,'destroy'])->name('delete_city');

});

Route::prefix('admin/role')->namespace('role')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
//    ROLES ROUTES
    Route::post('create', [\App\Http\Controllers\CommonAPI\RoleController::class, 'store'])->name('create');
    Route::delete('delete/{uuid}', [\App\Http\Controllers\CommonAPI\RoleController::class, 'destroy'])->name('delete');
    Route::post('update', [\App\Http\Controllers\CommonAPI\RoleController::class, 'update'])->name('update');
});
Route::prefix('admin/type')->namespace('employeetype')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
//    EMPLOYEE TYPE ROUTES
    Route::post('create_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'store'])->name('create_type');

    Route::delete('delete_type/{uuid}',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'destroy'])->name('delete_type');
    Route::post('update_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'update'])->name('update_type');
});

Route::prefix('admin/packages')->namespace('employeetype')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
//    PACKAGES ROUTES
    Route::post('create_package',[\App\Http\Controllers\CommonAPI\PackageController::class,'store'])->name('create_package');
    Route::post('update_package',[\App\Http\Controllers\CommonAPI\PackageController::class,'update'])->name('update_package');
    Route::post('delete_package',[\App\Http\Controllers\CommonAPI\PackageController::class,'destroy'])->name('delete_package');
});

Route::prefix('logout')->namespace('logout')->middleware(['auth:sanctum','check_status'])->group(function () {
    Route::post('logout',[UserController::class,'allUsersLogout'])->name('logout');
});

Route::prefix('employee')->namespace('employee')->middleware(['auth:sanctum','company','check_status'])->group(function () {
    Route::post('employee_create',[\App\Http\Controllers\Company\EmployeeController::class,'store'])->name('employee_create')->middleware(['limit_check']);
});
Route::prefix('company/complain')->namespace('complain')->middleware(['auth:sanctum','company','check_status'])->group(function () {
    Route::post('complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'store'])->name('complain_type');
    Route::post('edit_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'update'])->name('edit_complain_type');
    Route::get('list_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'index'])->name('list_complain_type');
    Route::post('delete_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'destroy'])->name('delete_complain_type');
    Route::post('assign_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'assign_complain_type_engineer'])->name('assign_complain_type');
});

Route::prefix('general/listing')->namespace('listing')->middleware(['auth:sanctum'])->group(function () {
    Route::get('list_all_country',[\App\Http\Controllers\CommonAPI\CountryController::class,'index'])->name('list_all_country');
    Route::get('show_all_city',[\App\Http\Controllers\CommonAPI\CityController::class,'index'])->name('show_all_city');
    Route::post('show_cities_by_country',[\App\Http\Controllers\CommonAPI\CityController::class,'show_cities_by_country'])->name('show_cities_by_country');
    Route::get('show_all_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'index'])->name('show_all_type');
    Route::get('show_all_packages',[\App\Http\Controllers\CommonAPI\PackageController::class,'index'])->name('show_all_packages');
    Route::get('show_all_roles', [\App\Http\Controllers\CommonAPI\RoleController::class, 'index'])->name('show_all');
    Route::get('show_branch_by_company', [\App\Http\Controllers\Company\BranchController::class, 'show_branch_by_company'])->name('show_branch_by_company');
});
