<?php

namespace App\Http\Controllers\CommonAPI;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::guard('users')->check()){
            $role = Role::where('status','active')->get();
        }elseif (Auth::guard('company')->check()){
            $role = Role::where('status','active')->whereNotIn('slug',['admin','super-admin','company'])->get();
        }
        if(!empty($role)) {
            return response()->json(
                [
                    'status' => config('constant.messages.Success'),
                    'message' => 'All record list',
                    'code' => config('constant.codes.success'),
                    'data' => $role,
                ]);
        }else{
            return response()->json(
                [
                    'status' => config('constant.messages.Failure'),
                    'message' => 'No roles found',
                    'code' => config('constant.codes.Failure'),
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
            'name' => 'required|string',
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
                $data = [
                    'uuid' => Str::uuid(),
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'status' => $request->status,
                ];
                $role =  Role::create($data);
                if($role){
                    return response()->json(
                        [
                            'success' => true,
                            'status' => config('constant.messages.Success'),
                            'message' => 'Record created successfully',
                            'code' => config('constant.codes.success'),
                            'data' => $role,
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
            'name' => 'required|string',
            'slug' => 'required|string',
            'status' => 'required|string',
            'uuid' => 'required|exists:roles,uuid'
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
                $role = Role::where('uuid',$request->uuid)->first();
                if(!empty($role)) {
                    $role->name = $request->name ?? $role->name;
                    $role->slug = $request->slug ?? $role->slug;
                    $role->status = $request->status ?? $role->status;
                    $role->update();
                    if ($role) {
                        return response()->json(
                            [
                                'success' => true,
                                'status' => config('constant.messages.Success'),
                                'message' => 'Record updated successfully',
                                'code' => config('constant.codes.success'),
                                'data' => $role,
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
                }else{
                    return response()->json(
                        [
                            'success' => false,
                            'status' => config('constant.messages.notFound'),
                            'message' => 'Data not found',
                            'code' => config('constant.codes.notFound'),
                            'data' => [],
                        ]);
                }
            }catch (\Exception $e){
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => $e,
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
    public function destroy($uuid)
    {
        $role = Role::where('uuid',$uuid)->first();
        if($role){
            $role->delete();
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'Record deleted successfully',
                    'code' => config('constant.codes.success'),
                    'data' => [],
                ]);
        }else{
            return response()->json(
                [
                    'success' => false,
                    'status' => config('constant.messages.Failure'),
                    'message' => 'Record not delete',
                    'code' => config('constant.codes.badRequest'),
                    'data' => [],
                ]);
        }
    }
}
