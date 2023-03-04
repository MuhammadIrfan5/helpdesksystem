<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use PHPUnit\Framework\Constraint\Count;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        if (!empty($companies)) {
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.loginSuccess'),
                    'message' => 'all companies',
                    'code' => config('constant.codes.success'),
                    'data' => $companies,
                ]);
        } else {
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.notFound'),
                    'message' => 'No companies',
                    'code' => config('constant.codes.notFound'),
                    'data' => [],
                ]);
        }
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

    public function login(Request $request)
    {
        if ($request->accepts(['application/json'])) {
            $validationRules = [
                'email' => 'required|string',
                'password' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'status' => 'Validation Errors',
                        'message' => $validator->errors()->first(),
                        'code' => config('constant.codes.validation'),
                        'data' => [],
                    ]);

            } else {
                $response = array();
                $company = Company::where('email', $request['email'])->first();
                if (!empty($company)) {
                    if ($company->is_approved == 1 && strtolower($company->status) == 'active') {
                        if (!Hash::check($request["password"], $company->password)) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'status' => config('constant.messages.Unauthorized'),
                                    'message' => 'Invalid Credentials',
                                    'code' => config('constant.codes.Unauthorized'),
                                    'data' => [],
                                ]);
                        } else {
                            if (!empty($company->tokens())) {
                                $company->tokens()->delete();
                            }
                            $token = $company->createToken('companyToken')->plainTextToken;
                            $company->save();
                            $company->companyToken = $token;
                            $response['success'] = true;
                            $response['status'] = config('constant.messages.loginSuccess');
                            $response['message'] = 'Logged In';
                            $response['code'] = config('constant.codes.success');
                            $response['data']['uuid'] = $company->uuid;
                            $response['data']['name'] = $company->name;
                            $response['data']['email'] = $company->email;
                            $response['data']['phone'] = $company->phone_no;
                            $response['data']['address'] = $company->address;
                            $response['data']['token'] = $company->companyToken;
                            $response['data']['key'] = $company->company_key;
                            $response['data']['registration'] = $company->registration_no;
                            $response['data']['status'] = $company->status;
                            $response['data']['role'] = $company->role;
                            $response['data']['country'] = $company->country;
                            $response['data']['city'] = $company->city;
                            $response['data']['package'] = $company->package;
                            $response['data']['branch'] = null;
                            $response['data']['emp_type'] = null;
                            $response['data']['company'] = null;
                            return response($response, 200);
//                            return response()->json(
//                                [
//                                    'success' => true,
//                                    'status' => config('constant.messages.loginSuccess'),
//                                    'message' => 'Logged In',
//                                    'code' => config('constant.codes.success'),
//                                    'data' => $company,
//                                ]);
                        }
                    } else {
                        return response()->json(
                            [
                                'success' => false,
                                'status' => config('constant.messages.Unauthorized'),
                                'message' => 'Account is inactive',
                                'code' => config('constant.codes.Unauthorized'),
                                'data' => [],
                            ]);
                    }
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'status' => config('constant.messages.Unauthorized'),
                            'message' => 'Company Not Exists',
                            'code' => config('constant.codes.Unauthorized'),
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
//            'user_created_by' => 'required|string',
            'country_id' => 'required|exists:countries,uuid',
            'city_id' => 'required|exists:cities,uuid',
            'package_id' => 'required|exists:packages,uuid',
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:companies,email',
            'phone_no' => 'required|string|min:11|max:12',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'is_approved' => 'required',
            'address' => 'required',
            'engineer_limit' => 'required|numeric',
            'employee_limit' => 'required|numeric',
            'status' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'status' => 'Validation Errors',
                    'message' => $validator->errors()->first(),
                    'code' => config('constant.codes.validation'),
                    'data' => [],
                ]);
        } else {
            $data = [
                'uuid' => Str::uuid(),
                'user_created_by' => auth()->user()->id,
                'country_id' => Country::where('uuid', $request->country_id)->first()['id'],
                'city_id' => City::where('uuid', $request->city_id)->first()['id'],
                'package_id' => Package::where('uuid', $request->package_id)->first()['id'],
                'role_id' => 3,
                'registration_no' => generate_registaion_no(),
                'name' => $request->name,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_approved' => $request->is_approved,
                'address' => $request->address,
                'password' => bcrypt($request->email),// Str::random(20),
                'engineer_limit' => $request->engineer_limit,
                'employee_limit' => $request->employee_limit,
                'company_key' => Str::random(40),
                'status' => $request->status,
            ];
            $company = Company::create($data);
            if ($company) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Company created Successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $company,
                    ]);
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Company not created',
                        'code' => config('constant.codes.badRequest'),
                        'data' => [],
                    ]);
            }
        }
    }

    public function block_unblock_company_account(Request $request)
    {
        $validationRules = [
            'uuid' => 'required|uuid|exists:companies,uuid',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'status' => 'Validation Errors',
                    'message' => $validator->errors()->first(),
                    'code' => config('constants.codes.validation'),
                    'data' => [],
                ]);
        } else {
            $company = Company::where('uuid', $request->uuid)->first();
            if ($company != null) {
                $company->status = $request->status;
                $company->update();
                $company->touch();
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Company ' . (($request->status == 'active') ? 'Activated' : 'Inactived') . ' Successfully',
                        'code' => config('constant.codes.success'),
                        'data' => [],
                    ]);
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Company not created',
                        'code' => config('constant.codes.badRequest'),
                        'data' => [],
                    ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $company = Company::where('uuid', $request->uuid)->first();
        $validationRules = [
            'uuid' => 'required|uuid|exists:companies,uuid',
            'country_id' => 'nullable|uuid|exists:countries,uuid',
            'city_id' => 'nullable|uuid|exists:cities,uuid',
            'name' => 'nullable|string',
            'secondary_email' => ['nullable',Rule::unique('companies', 'secondary_email')->ignore($company->id)], //'required|email|unique:companies,secondary_email',
            'phone_no' => [Rule::unique('companies', 'phone_no')->ignore($company->id),'string','min:11','max:12'],
            'mobile_no' => [Rule::unique('companies', 'mobile_no')->ignore($company->id),'string','min:11','max:12'],
            'address' => 'nullable|string',
//            'password' => ['nullable', Password::min(10)->mixedCase()->numbers()->symbols()->uncompromised()],
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'status' => 'Validation Errors',
                    'message' => $validator->errors()->first(),
                    'code' => config('constants.codes.validation'),
                    'data' => [],
                ]);
        } else {
            $company->country_id = $request->country_id ? Country::where('uuid',$request->country_id)->first()['id'] : $company->country_id;
            $company->city_id = $request->city_id ? City::where('uuid',$request->city_id)->first()['id'] : $company->city_id;
            $company->name = $request->name ?? $company->name;
            $company->secondary_email = $request->secondary_email ?? $company->secondary_email;
            $company->phone_no = $request->phone_no ?? $company->phone_no;
            $company->mobile_no = $request->mobile_no ?? $company->mobile_no;
            $company->address = $request->address ?? $company->address;
//            $company->password = $request->password ? bcrypt($request->password) : $company->password;
            $company->update();
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'Company ',
                    'code' => config('constant.codes.success'),
                    'data' => [],
                ]);
        }
    }

    public function change_password(Request $request){
        if ($request->accepts(['application/json'])) {
            $validationRules = [
                'uuid' => 'required|exists:companies,uuid',
                'old_password' => 'required|string|current_password:companies',
                'password' => ['required', Password::min(10)->mixedCase()->numbers()->symbols()->uncompromised(), 'different:password_confirmation'],
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
                $check = DB::table('companies')
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


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
