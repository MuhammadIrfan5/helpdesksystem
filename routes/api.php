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

Route::prefix('Company')->namespace('company')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('company_create',[\App\Http\Controllers\Company\CompanyController::class,'store'])->name('company_create');
});
Route::prefix('Country')->namespace('country')->middleware(['auth:sanctum','adminSuperAdmin:super-admin,admin'])->group(function () {
    Route::post('country_create',[\App\Http\Controllers\CommonAPI\CountryController::class,'store'])->name('company_create');
});
Route::prefix('Employee')->namespace('employee')->middleware(['auth:sanctum','company'])->group(function () {
    Route::post('employee_create',[\App\Http\Controllers\Company\EmployeeController::class,'store'])->name('employee_create');
});

