<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
//            'company_id' => 'required|string',
            'branch_id' => 'required|string',
            'country_id' => 'required|string',
            'city_id' => 'required|string',
            'role_id' => 'required|string',
            'employee_code' => 'required|string|unique:employee,employee_code',
            'employee_type_id' => 'required|string',
            'first_name' => 'required|string',
            'company_email' => 'required|email|unique:employee,company_email',
            'primary_phone_no' => 'required|string|min:11|max:12',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'Validation Errors' ,
                    'message' => $validator->errors()->first(),
                    'code' => config('constant.codes.validation'),
                    'data' => [],
                ]);
        } else {
            $data = [
                'uuid' => Str::uuid(),
                'company_id' => auth()->user()->id,
                'branch_id' => $request->branch_id ,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'role_id' => $request->role_id,
                'employee_code' => $request->employee_code,
                'employee_type_id' => $request->employee_type_id,
                'first_name' => $request->first_name,
                'company_email' => $request->company_email,
                'primary_phone_no' => $request->primary_phone_no,
                'password'  => bcrypt($request->email), // Str::random(20)
                'status' => $request->status,
            ];
            $employee = Employee::create($data);
            if($employee){
                return response()->json(
                    [
                        'status' => config('constant.messages.Success'),
                        'message' => 'Record created successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $employee,
                    ]);
            }else{
                return response()->json(
                    [
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Data not created',
                        'code' => config('constant.codes.badRequest'),
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
