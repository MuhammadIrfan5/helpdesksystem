<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\Country;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function login(Request $request){
        if ($request->accepts(['application/json'])) {
            $validationRules = [
                'email' => 'required|string',
                'password' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'Validation Errors',
                        'message' => $validator->errors()->first(),
                        'code' => config('constant.codes.validation'),
                        'data' => [],
                    ]);

            } else {
                $response = array();
                $employee = Employee::where('company_email', $request['email'])->first();
                if (!empty($employee)) {
                    if($employee->company->is_approved == 1 && strtolower($employee->company->status) == 'active') {
                        if (strtolower($employee->status) == 'active') {
                            if (!Hash::check($request["password"], $employee->password)) {
                                return response()->json(
                                    [
                                        'success' => false,
                                        'status' => config('constant.messages.Unauthorized'),
                                        'message' => 'Invalid Credentials',
                                        'code' => config('constant.codes.Unauthorized'),
                                        'data' => [],
                                    ]);
                            } else {
                                if (!empty($employee->tokens())) {
                                    $employee->tokens()->delete();
                                }
                                $token = $employee->createToken('employeeToken')->plainTextToken;
                                $employee->save();
                                $employee->employeeToken = $token;
                                $response['success'] = true;
                                $response['status'] = config('constant.messages.loginSuccess');
                                $response['message'] ='Logged In';
                                $response['code'] = config('constant.codes.success');
                                $response['data']['uuid'] = $employee->uuid;
                                $response['data']['name'] = $employee->first_name . $employee->last_name;
                                $response['data']['employee_code'] = $employee->employee_code;
                                $response['data']['email'] = $employee->company_email;
                                $response['data']['phone'] = $employee->primary_phone_no;
                                $response['data']['address'] = $employee->address_line1;
                                $response['data']['token'] = $employee->employeeToken;
                                $response['data']['key'] = null;
                                $response['data']['registration'] = null;
                                $response['data']['status'] = $employee->status;
                                $response['data']['role'] = $employee->role;
                                $response['data']['country'] = $employee->country;
                                $response['data']['city'] = $employee->city;
                                $response['data']['package'] = null;
                                $response['data']['branch'] = $employee->branch;
                                $response['data']['emp_type'] = $employee->employee_type;
                                $response['data']['company'] = $employee->company;
                                return response($response, 200);
                            }
                        } else {
                            return response()->json(
                                [
                                    'success' => false,
                                    'status' => config('constant.messages.Unauthorized'),
                                    'message' => 'your account is inactive',
                                    'code' => config('constant.codes.Unauthorized'),
                                    'data' => [],
                                ]);
                        }
                    }else{
                        return response()->json(
                            [
                                'success' => false,
                                'status' => config('constant.messages.Unauthorized'),
                                'message' => 'Company account is inactive',
                                'code' => config('constant.codes.Unauthorized'),
                                'data' => [],
                            ]);
                    }
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'status' => config('constant.messages.Unauthorized'),
                            'message' => 'Invalid Role',
                            'code' => config('constant.codes.Unauthorized'),
                            'data' => [],
                        ]);
                }
            }
        }else{
        return response()->json(
            [
                'success' => false,
                'status' => config('constant.messages.badRequest'),
                'message' => 'Only Accepts Application json',
                'code' => config('constant.codes.badRequest'),
                'data' => [],
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'company_id' => 'required|uuid|exists:companies,uuid',
            'branch_id' => 'required|uuid|exists:employee,uuid',
            'country_id' => 'required|uuid|exists:countries,uuid',
            'city_id' => 'required|uuid|exists:cities,uuid',
            'role_id' => 'required|uuid|exists:roles,uuid',
            'employee_code' => 'required|string|unique:employee,employee_code',
            'employee_type_id' => 'required|uuid|exists:employee_type,uuid',
            'first_name' => 'required|string',
            'company_email' => 'required|email|unique:employee,company_email',
            'primary_phone_no' => 'required|unique:employee,primary_phone_no|min:11|max:12',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'status' => 'Validation Errors' ,
                    'message' => $validator->errors()->first(),
                    'code' => config('constant.codes.validation'),
                    'data' => [],
                ]);
        } else {

            $data = [
                'uuid' => Str::uuid(),
                'company_id' => Company::where('uuid',$request->company_id)->first()['id'],
                'branch_id' => CompanyBranch::where('uuid',$request->branch_id)->first()['id'],
                'country_id' => Country::where('uuid',$request->country_id)->first()['id'],
                'city_id' => City::where('uuid',$request->city_id)->first()['id'],
                'role_id' => Role::where('uuid',$request->role_id)->first()['id'],
                'employee_code' => $request->employee_code,
                'employee_type_id' => EmployeeType::where('uuid',$request->employee_type_id)->first()['id'],
                'first_name' => $request->first_name,
                'company_email' => $request->company_email,
                'primary_phone_no' => $request->primary_phone_no,
                'password'  => bcrypt($request->company_email), // Str::random(20)
                'status' => $request->status,
            ];
            $employee = Employee::create($data);
            if($employee){
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Record created successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $employee,
                    ]);
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Data not created',
                        'code' => config('constant.codes.badRequest'),
                        'data' => [],
                    ]);
            }
        }
    }

    public function change_password(Request $request){
        if ($request->accepts(['application/json'])) {
            $validationRules = [
                'uuid' => 'required|exists:employee,uuid',
                'old_password' => 'required|string|current_password:employee',
                'password' => ['required', Password::min(10)->mixedCase()->numbers()->symbols()->uncompromised(), 'different:old_password'],
            ];
            $messages = [
                'old_password.current_password' => 'current password does not matched'
            ];
            $validator = Validator::make($request->all(), $validationRules, $messages);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'Validation Errors',
                        'message' => $validator->errors()->first(),
                        'code' => config('constant.codes.validation'),
                        'data' => [],
                    ]);

            } else {
                $check = DB::table('employee')
                    ->where('id',auth()->user()->id)
                    ->lockForUpdate()
                    ->update(['password' => bcrypt($request->password)]);
                if ($check) {
                    return response()->json(
                        [
                            'success' => true,
                            'status' => config('constant.messages.Success'),
                            'message' => 'Password Changed Successfully',
                            'code' => config('constant.codes.success'),
                            'data' => [],
                        ]);
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'status' => config('constant.messages.Failure'),
                            'message' => 'Something went wrong!',
                            'code' => config('constant.codes.badRequest'),
                            'data' => [],
                        ]);
                }
            }
        } else {
            return response()->json(
                [
                    'success' => false,
                    'status' => config('constant.messages.badRequest'),
                    'message' => 'Only Accepts Application json',
                    'code' => config('constant.codes.badRequest'),
                    'data' => [],
                ]);
        }
    }

    public function list_engineers_by_company(Request $request){
        if ($request->accepts(['application/json'])) {
            $validationRules = [
                'company_uuid' => 'required|uuid|exists:companies,uuid',
            ];
            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'Validation Errors',
                        'message' => $validator->errors()->first(),
                        'code' => config('constant.codes.validation'),
                        'data' => [],
                    ]);

            } else {

                $engineers = Employee::where('role_id',4)->get();
                $response = array();
                foreach ($engineers as $key => $engineer){
                    $response[$key]['value'] = $engineer->uuid;
                    $response[$key]['label'] = $engineer->first_name ." ".$engineer->last_name;
                }
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Engineer List Successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $response,
                    ]);
            }
        }else{
            return response()->json(
            [
                'success' => false,
                'status' => config('constant.messages.badRequest'),
                'message' => 'Only Accepts Application json',
                'code' => config('constant.codes.badRequest'),
                'data' => [],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
