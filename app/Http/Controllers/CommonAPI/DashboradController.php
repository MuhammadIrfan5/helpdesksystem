<?php

namespace App\Http\Controllers\CommonAPI;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyComplainType;
use App\Models\ComplainType;
use App\Models\Country;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Package;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboradController extends Controller
{
    public function dashborad_analytics(){
        $engineers = Employee::where('status','active')->where('role_id',4)->count();
        $employees = Employee::where('status','active')->where('role_id',5)->count();
        $cities = City::where('status','active')->count();
        $countries = Country::where('status','active')->count();
        $roles = Role::where('status','active')->count();
        $companies = Company::where('status','active')->count();
        $packages = Package::where('status','active')->count();
        $employeeTypes = EmployeeType::where('status','active')->count();
        if(auth()->user()->role->slug ==  'admin' || auth()->user()->role->slug ==  'super-admin'){
            $admin = array();
            $admin['engineers'] = $engineers;
            $admin['employees'] = $employees;
            $admin['cities'] = $cities;
            $admin['countries'] = $countries;
            $admin['roles'] = $roles;
            $admin['companies'] = $companies;
            $admin['packages'] = $packages;
            $admin['employeeTypes'] = $employeeTypes;
            $admin['complain_type'] = null;
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'All record list',
                    'code' => config('constant.codes.success'),
                    'data' => $admin,
                ]);
        }
        else if(auth()->user()->role->slug ==  'employee' || auth()->user()->role->slug ==  'engineer'){
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'All record list',
                    'code' => config('constant.codes.success'),
                    'data' => [],
                ]);
        }
        else if(auth()->user()->role->slug ==  'company'){
            $data = array();
            $company_complain_type = ComplainType::where('company_id',auth()->user()->id)->count();
            $company_engineer = Employee::where('company_id',auth()->user()->id)->where('role_id',4)->count();
            $company_employees = Employee::where('company_id',auth()->user()->id)->where('role_id',4)->count();
            $data['engineers'] = $company_engineer;
            $data['employees'] = $company_employees;
            $data['cities'] = null;
            $data['countries'] = null;
            $data['roles'] = null;
            $data['companies'] = null;
            $data['packages'] = null;
            $data['employeeTypes'] = $employeeTypes;
            $data['complain_type'] = $company_complain_type;
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'All record list',
                    'code' => config('constant.codes.success'),
                    'data' => $data,
                ]);
        }else{
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'Invalid Role',
                    'code' => config('constant.codes.success'),
                    'data' => [],
                ]);
        }
    }
}
