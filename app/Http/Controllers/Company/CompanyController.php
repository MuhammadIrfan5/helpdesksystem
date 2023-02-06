<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
//            'user_created_by' => 'required|string',
            'country_id' => 'required|string',
            'city_id' => 'required|string',
            'package_id' => 'required|string',
            'registration_no' => 'required|string|unique:companies,registration_no',
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:companies,email',
            'phone_no' => 'required|string|min:11|max:12',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'is_approved' => 'required',
            'address' => 'required',
            'password' => 'required|string|min:8',
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'Validation Errors' ,
                    'message' => $validator->errors()->first(),
                    'code' => config('constants.codes.Forbidden'),
                    'data' => [],
                ]);
        } else {
            if((checkAdminAndSupAdmin("admin") || checkAdminAndSupAdmin('superadmin')) && $request->header('Authorization') ){

                $data = [
//                    $request->user_created_by
                    'user_created_by' => auth()->user()->id,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'package_id' => $request->package_id,
                    'registration_no' => $request->registration_no,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_no' => $request->phone_no,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'is_approved' => $request->is_approved,
                    'address' => $request->address,
                    'password' => $request->password,
                ];
                $company = Company::create($data);
                return response()->json(
                    [
                        'status' => config('constant.messages.Success'),
                        'message' => 'Company created Successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $company,
                    ]);
            }else{
                return response()->json(
                    [
                        'status' => config('constant.messages.Unauthorized'),
                        'message' => 'Invalid Role',
                        'code' => config('constant.codes.Unauthorized'),
                        'data' => [],
                    ]);
            }
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
