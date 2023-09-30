<?php

namespace App\Http\Controllers\CommonAPI;

use App\Http\Controllers\Controller;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = EmployeeType::all();
        foreach ($types as $type){
            $type->value = $type->uuid;
            $type->label = $type->type;
        }
        if(!empty($types)) {
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'All record list',
                    'code' => config('constant.codes.success'),
                    'data' => $types,
                ]);
        }else{
            return response()->json(
                [
                    'success' => false,
                    'status' => config('constant.messages.Failure'),
                    'message' => 'No employee type found',
                    'code' => config('constant.codes.badRequest'),
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
            'type' => 'required|string',
            'slug' => 'required|string',
            'status' => 'required|string',
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
            try {
                $type = new EmployeeType();
                $type->uuid = Str::uuid();
                $type->type = $request->type;
                $type->slug = $request->slug;
                $type->status = $request->status;
                $type->save();
                if($type){
                    return response()->json(
                        [
                            'success' => true,
                            'status' => config('constant.messages.Success'),
                            'message' => 'Record created successfully',
                            'code' => config('constant.codes.success'),
                            'data' => $type,
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
            }catch (\Exception $e){
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => $e,
                        'code' => config('constant.codes.notFound'),
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
    public function update(Request $request)
    {
        $validationRules = [
            'type' => 'required|string',
            'slug' => 'required|string',
            'status' => 'required|string',
            'uuid' => 'required|exists:employee_type,uuid'
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
            try {
                $type = EmployeeType::where('uuid', $request->uuid)->first();
                if (!empty($type)) {
                    $type->type = $request->type ?? $type->type;
                    $type->slug = $request->slug ?? $type->slug;
                    $type->status = $request->status ?? $type->status;
                    $type->update();

                    if ($type) {
                        return response()->json(
                            [
                                'success' => true,
                                'status' => config('constant.messages.Success'),
                                'message' => 'Record updated successfully',
                                'code' => config('constant.codes.success'),
                                'data' => $type,
                            ]);
                    } else {
                        return response()->json(
                            [
                                'success' => false,
                                'status' => config('constant.messages.Failure'),
                                'message' => 'Data not updated',
                                'code' => config('constant.codes.badRequest'),
                                'data' => [],
                            ]);
                    }
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'status' => config('constant.messages.notFound'),
                            'message' => 'Data not found',
                            'code' => config('constant.codes.notFound'),
                            'data' => [],
                        ]);
                }
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => $e,
                        'code' => config('constant.codes.badRequest'),
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
        'uuid' => 'required|exists:employee_type,uuid'
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
            $type = EmployeeType::where('uuid', $request->uuid)->delete();
            if ($type) {
                return response()->json(
                    [
                        'status' => config('constant.messages.Success'),
                        'message' => 'Record deleted successfully',
                        'code' => config('constant.codes.success'),
                        'data' => [],
                    ]);
            } else {
                return response()->json(
                    [
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Record not delete',
                        'code' => config('constant.codes.badRequest'),
                        'data' => [],
                    ]);
            }
        }
    }


}
