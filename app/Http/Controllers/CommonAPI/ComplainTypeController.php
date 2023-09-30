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
use function PHPUnit\Framework\isEmpty;

class ComplainTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complain_type = ComplainType::paginate(10);
        foreach ($complain_type as $type) {
            $type->company_id = $type->company->name;
        }
        return response()->json(
            [
                'success' => true,
                'status' => config('constant.messages.Success'),
                'message' => 'Complain type fetch successfully',
                'code' => config('constant.codes.success'),
                'data' => $complain_type,
            ]);
    }


    public function list_complaintype_by_company(Request $request)
    {
        $validationRules = [
            'uuid' => 'required|uuid|exists:companies,uuid'
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
            $types = Company::with('complaintype')->where('uuid', $request->uuid)->first();
            foreach ($types->complaintype as $type) {
                $typed[] = [
                    'label' => $type->type,
                    'value' => $type->uuid,
                    'companyName' => $type->company->name,
                ];
            }
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'Complain type fetch successfully',
                    'code' => config('constant.codes.success'),
                    'data' => $typed,
                ]);
        }
    }

    public function block_complain_type(Request $request)
    {
        $validationRules = [
            'uuid' => 'required|uuid|exists:complain_type,uuid',
            'status' => 'required'
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
            $type = ComplainType::where('uuid', $request->uuid)->update(['status' => $request->status]);
            if ($type) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complain type status changed successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $type,
                    ]);
            } else {
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'type' => 'required',
            'description' => 'nullable',
            'status' => 'required',
            'uuid' => 'required|uuid|exists:companies,uuid'
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
            $complain_type = ComplainType::create([
                'uuid' => Str::uuid(),
                'type' => $request->type,
                'description' => $request->description,
                'company_id' => Company::where('uuid', $request->uuid)->first()['id'],
                'status' => $request->status
            ]);
            if ($complain_type) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complain type added successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $complain_type,
                    ]);
            } else {
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
        $validationRules = [
            'uuid' => 'required|uuid|exists:complain_type,uuid',
            'company_uuid' => 'required|uuid|exists:companies,uuid',
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
                    'code' => config('constant.codes.validation'),
                    'data' => [],
                ]);
        } else {

            $company_id = Company::where('uuid', $request->company_uuid)->first()['id'];
            $complain_type = ComplainType::where('uuid', $request->uuid)->where('company_id', $company_id)->first();
            if ($complain_type) {
                $complain_type->type = $request->type ?? $complain_type->type;
                $complain_type->description = $request->description ?? $complain_type->description;
                $complain_type->status = $request->status ?? $complain_type->status;
                $complain_type->update();
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complain type edited successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $complain_type,
                    ]);
            } else {
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validationRules = [
            'uuid' => 'required|uuid|exists:complain_type,uuid',
            'company_uuid' => 'required|uuid|exists:companies,uuid',
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
            $complain_type = ComplainType::where('uuid', $request->uuid)->where('company_id', $request->company_uuid)->delete();
            if ($complain_type) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complain Type delete successfully',
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

    public function assign_complain_type_engineer(Request $request)
    {
        $validationRules = [
            'company_uuid' => 'required|uuid|exists:companies,uuid',
            'employee_uuid' => 'required|uuid|exists:employee,uuid',
            'complain_uuid' => 'required|string',
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
            $complaintype_uuids = explode(',', $request->complain_uuid);
            foreach ($complaintype_uuids as $uuid) {
                $type = ComplainType::where('uuid', $uuid)->first();
                if ($type != null) {
                    $company_complain_type = new CompanyComplainType();
                    $company_complain_type->uuid = Str::uuid();
                    $company_complain_type->company_id = Company::where('uuid', $request->company_uuid)->first()['id'];
                    $company_complain_type->complain_type_id = $type->id;
                    $company_complain_type->employee_id = Employee::where('uuid', $request->employee_uuid)->first()['id'];
                    $company_complain_type->employee_role_id = Employee::where('uuid', $request->employee_uuid)->first()['role_id'];
                    $company_complain_type->save();
                }
            }
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'Complaint Type Assign Successfully',
                    'code' => config('constant.codes.success'),
                    'data' => [],
                ]);
        }
    }


    public function engineer_unassign_complain_type(Request $request){
        $validationRules = [
            'company_uuid' => 'required|uuid|exists:companies,uuid',
            'employee_uuid' => 'required|uuid|exists:employee,uuid'
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
            $company = Company::where('uuid',$request->company_uuid)->first();
            $employee = Employee::where('uuid',$request->employee_uuid)->first();
            $company_complain_type = CompanyComplainType::where('company_id',$company->id)->where('employee_id',$employee->id)->get();
            $type_uuids = array();
            foreach ($company_complain_type as $type){
                array_push($type_uuids,$type->complain_type_id);
            }
            $complain_types = ComplainType::where('company_id',$company->id)->whereNotIn('id',$type_uuids)->get();
            if(count($complain_types) > 0) {
                $response = array();
                foreach ($complain_types as $key => $type) {
                    $response[$key]['value'] = $type->uuid;
                    $response[$key]['label'] = $type->type;
                }
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complaint Types List',
                        'code' => config('constant.codes.success'),
                        'data' => $response,
                    ]);
            }else{
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Complaint types already assigned',
                        'code' => config('constant.codes.success'),
                        'data' => [],
                    ]);
            }
        }
    }

}


//e03fd285-5392-4983-b880-4d4c3a08096e,29b1515a-3b69-4176-86bb-181c105f5eb8
