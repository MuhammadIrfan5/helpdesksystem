<?php

namespace App\Http\Controllers\CommonAPI;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyComplainType;
use App\Models\ComplainType;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ComplainTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complain_type = ComplainType::where('status','active')->get();
        if($complain_type){
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'Complain type fetch successfully',
                    'code' => config('constant.codes.success'),
                    'data' => $complain_type,
                ]);
        }else{
            return response()->json(
                [
                    'success' => false,
                    'status' => config('constant.messages.Failure'),
                    'message' => 'No data found',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'type' => 'required',
            'description' => 'nullable',
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
            $complain_type = ComplainType::create([
                'uuid' => Str::uuid(),
                'type' => $request->type,
                'description' => $request->description,
                'created_by' => auth()->user()->id,
                'company_id' => auth()->user()->id,
                'status' => $request->status
            ]);
            if($complain_type) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complain type added successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $complain_type,
                    ]);
            }else{
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Something went wrong',
                        'code' => config('constant.codes.internalServer'),
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validationRules = [
            'uuid' => 'required|uuid|exists:complain_type,uuid',
            'type' => 'required',
            'description' => 'nullable',
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

            $complain_type = ComplainType::where('uuid',$request->uuid)->where('company_id',auth()->user()->id)->first();
            if($complain_type) {
                $complain_type->uuid = auth()->user()->uuid;
                $complain_type->type = $request->type;
                $complain_type->description = $request->description;
                $complain_type->created_by = auth()->user()->id;
                $complain_type->company_id = auth()->user()->id;
                $complain_type->status = $request->status;
                $complain_type->update();
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complain type edited successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $complain_type,
                    ]);
            }else{
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Something went wrong',
                        'code' => config('constant.codes.internalServer'),
                        'data' => [],
                    ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validationRules = [
            'uuid' => 'required|uuid|exists:complain_type,uuid',
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
            $complain_type = ComplainType::where('uuid',$request->uuid)->where('company_id',auth()->user()->id)->delete();
            if($complain_type) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complain delete successfully',
                        'code' => config('constant.codes.success'),
                        'data' => [],
                    ]);
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Something went wrong!',
                        'code' => config('constant.codes.internalServer'),
                        'data' => [],
                    ]);
            }
        }
    }

    public function assign_complain_type_engineer(Request $request){
        $validationRules = [
            'complain_type_uuid' => 'required|uuid|exists:complain_type,uuid',
            'company_uuid' => 'required|uuid|exists:companies,uuid',
            'employee_uuid' => 'required|uuid|exists:employee,uuid',
            'employee_uuid' => Rule::prohibitedIf(fn () => Employee::with('role')->where('uuid',$request->employee_uuid)->first()->role->slug != 'engineer'),
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
            $complain_type = ComplainType::where('uuid',$request->complain_type_uuid)->first();
            $company = Company::where('uuid',$request->company_uuid)->first();
            $engineer = Employee::with('role')->where('uuid',$request->employee_uuid)->first();

            $assign = CompanyComplainType::create(
                [
                    'uuid' => Str::uuid(),
                    'company_id' => $company->id,
                    'complain_type_id' => $complain_type->id,
                    'employee_id' => $engineer->id,
                    'employee_role_id' => $engineer->role->id
                ]
            );
            if($assign) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complaint Type Assign Successfully',
                        'code' => config('constant.codes.success'),
                        'data' => [],
                    ]);
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Something went wrong!',
                        'code' => config('constant.codes.internalServer'),
                        'data' => [],
                    ]);
            }
        }
    }

}
