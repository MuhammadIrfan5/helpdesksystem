<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompanyController extends Controller
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
                            return response()->json(
                                [
                                    'success' => true,
                                    'status' => config('constant.messages.loginSuccess'),
                                    'message' => 'Logged In',
                                    'code' => config('constant.codes.success'),
                                    'data' => $company,
                                ]);
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
            'country_id' => 'required|string',
            'city_id' => 'required|string',
            'package_id' => 'required|string',
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:companies,email',
            'phone_no' => 'required|string|min:11|max:12',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'is_approved' => 'required',
            'address' => 'required',
            'engineer_limit' => 'required',
            'employee_limit' => 'required',
            'status' => 'required,enum:[active,inactive]',
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
            $data = [
                'uuid' => Str::uuid(),
                'user_created_by' => auth()->user()->id,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'package_id' => $request->package_id,
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
    public function update(Request $request, $id)
    {
        //
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
