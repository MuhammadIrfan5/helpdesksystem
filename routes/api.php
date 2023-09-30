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
    Route::post('delete_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'destroy'])->name('delete_type');
    Route::post('update_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'update'])->name('update_type');
});

Route::prefix('admin/packages')->namespace('packages')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
//    PACKAGES ROUTES
    Route::post('create_package',[\App\Http\Controllers\CommonAPI\PackageController::class,'store'])->name('create_package');
    Route::post('update_package',[\App\Http\Controllers\CommonAPI\PackageController::class,'update'])->name('update_package');
    Route::post('delete_package',[\App\Http\Controllers\CommonAPI\PackageController::class,'destroy'])->name('delete_package');
    Route::post('block_package',[\App\Http\Controllers\CommonAPI\PackageController::class,'block_package'])->name('block_package');
});

Route::prefix('logout')->namespace('logout')->middleware(['auth:sanctum','check_status'])->group(function () {
    Route::post('logout',[UserController::class,'allUsersLogout'])->name('logout');
});

Route::prefix('company_employee')->namespace('company_employee')->middleware(['auth:sanctum','company'])->group(function () {
    Route::post('employee_creates',[\App\Http\Controllers\Company\EmployeeController::class,'store'])->name('employee_creates')->middleware(['limit_check']);
});

Route::prefix('company/complain')->namespace('complain')->middleware(['auth:sanctum','company'])->group(function () {
    Route::post('create_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'store'])->name('create_complain_type');
    Route::post('edit_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'update'])->name('edit_complain_type');
    Route::post('delete_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'destroy'])->name('delete_complain_type');
    Route::post('assign_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'assign_complain_type_engineer'])->name('assign_complain_type');
    Route::post('list_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'list_complaintype_by_company'])->name('list_complain_type');
});

Route::prefix('company_profile')->namespace('company')->middleware(['auth:sanctum','company'])->group(function () {
    Route::post('update_company',[\App\Http\Controllers\Company\CompanyController::class,'update'])->name('update_company');
});

Route::prefix('general/listing')->namespace('listing')->middleware(['auth:sanctum'])->group(function () {
    Route::get('list_all_country',[\App\Http\Controllers\CommonAPI\CountryController::class,'index'])->name('list_all_country');
    Route::get('show_all_city',[\App\Http\Controllers\CommonAPI\CityController::class,'index'])->name('show_all_city');
    Route::post('show_cities_by_country',[\App\Http\Controllers\CommonAPI\CityController::class,'show_cities_by_country'])->name('show_cities_by_country');
    Route::get('show_all_type',[\App\Http\Controllers\CommonAPI\EmployeeTypeController::class,'index'])->name('show_all_type');
    Route::get('show_all_packages',[\App\Http\Controllers\CommonAPI\PackageController::class,'index'])->name('show_all_packages');
    Route::get('show_all_roles', [\App\Http\Controllers\CommonAPI\RoleController::class, 'index'])->name('show_all');
    Route::post('list_engineers_by_company', [\App\Http\Controllers\Company\EmployeeController::class, 'list_engineers_by_company'])->name('list_engineers_by_company');
    Route::post('engineer_unassign_complain_type', [\App\Http\Controllers\CommonAPI\ComplainTypeController::class, 'engineer_unassign_complain_type'])->name('engineer_unassign_complain_type');
    Route::get('show_branch_by_company', [\App\Http\Controllers\Company\BranchController::class, 'show_branch_by_company'])->name('show_branch_by_company');
    Route::get('list_all_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'index'])->name('list_all_complain_type');
    Route::post('block_complain_type',[\App\Http\Controllers\CommonAPI\ComplainTypeController::class,'block_complain_type'])->name('block_complain_type');
    Route::get('dashborad_analytics', [\App\Http\Controllers\CommonAPI\DashboradController::class, 'dashborad_analytics'])->name('dashborad_analytics');
});
Route::prefix('admin')->namespace('admin')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('change_password', [\App\Http\Controllers\API\UserController::class, 'change_password'])->name('change_password');
});
Route::prefix('admin')->namespace('admin')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('change_password', [\App\Http\Controllers\API\UserController::class, 'change_password'])->name('change_password');
});
Route::prefix('company')->namespace('company')->middleware(['auth:sanctum','company'])->group(function () {
    Route::post('change_password', [\App\Http\Controllers\Company\CompanyController::class, 'change_password'])->name('change_password');
});
Route::prefix('employee')->namespace('employee')->middleware(['auth:sanctum','employee'])->group(function () {
    Route::post('change_password', [\App\Http\Controllers\Company\EmployeeController::class, 'change_password'])->name('change_password');
});
