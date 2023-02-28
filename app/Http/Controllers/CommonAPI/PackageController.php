<?php

namespace App\Http\Controllers\CommonAPI;

use App\Http\Controllers\Controller;
use App\Models\ComplainType;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::where('status','active')->get();
        if(!$packages->isEmpty()){
            foreach ($packages as $package){
                $package->label = $package->name;
                $package->value = $package->slug;
            }
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'All record list',
                    'code' => config('constant.codes.success'),
                    'data' => $packages,
                ]);
        }else{
            return response()->json(
                [
                    'success' => false,
                    'status' => config('constant.messages.Failure'),
                    'message' => 'No packages found',
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
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required',
            'package_cost' => 'numeric',
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
            $package = Package::create([
                'uuid' => Str::uuid(),
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
                'package_cost' => $request->package_cost,
            ]);
            if($package) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Data Enter Successfully',
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
        $validationRules = [
            'uuid' => 'required|exists:packages,uuid',
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required',
            'package_cost' => 'numeric',
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
            $package = Package::where('uuid',$request->uuid)->first();
            $package->name = $request->name;
            $package->slug = $request->slug;
            $package->status = $request->status;
            $package->package_cost = $request->package_cost;
            if($package->update()) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Data Updated Successfully',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validationRules = [
            'uuid' => 'required|exists:packages,uuid'
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
            $package = Package::where('uuid',$request->uuid)->delete();
            if($package) {
                return response()->json(
                    [
                        'success' => true,
                        'status' => config('constant.messages.Success'),
                        'message' => 'Data Updated Successfully',
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
