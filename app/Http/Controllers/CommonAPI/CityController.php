<?php

namespace App\Http\Controllers\CommonAPI;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $city = City::where('status','active')->get();
        if(!empty($city)) {
            return response()->json(
                [
                    'status' => config('constant.messages.Success'),
                    'message' => 'All record list',
                    'code' => config('constant.codes.success'),
                    'data' => $city,
                ]);
        }else{
            return response()->json(
                [
                    'status' => config('constant.messages.Failure'),
                    'message' => 'No roles found',
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*Response format*/
        $response = [
            "success" => false,
            "message" => ""
        ];
        /*validation*/
        $validationRules = [
            "countryId" => "required|exist:countries,id",
            "name" => "required",
            "code" => "required",
        ];
        $validator = Validator::make($request->all(), $validationRules);
        /*check is validation success*/
        if ($validator->fails()) {
            $response["message"] = $validator->errors()->first();
        } else {
            /*create user*/
            $data = [
                'uuid' => Str::uuid(),
                'country_id' => $request->countryId,
                'name' => $request->name,
                'code' => $request->code,
                'status' => 'active',
            ];
            $result = City::create($data);
            /*make response*/
            $response["success"] = true;
            $response["message"] = "Application Submitted!";
        }
        return response($response);
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
        /*Response format*/
        $response = [
            "success" => false,
            "message" => ""
        ];
        /*validation*/
        $validationRules = [
            "cityId" => "required|exist:cities,id",
            "name" => "nullable",
            "code" => "nullable",
            "status" => "required",
        ];
        $validator = Validator::make($request->all(), $validationRules);
        /*check is validation success*/
        if ($validator->fails()) {
            $response["message"] = $validator->errors()->first();
        } else {
            /*update city*/
            $result = City::where('uuid', $request->cityId)->first();
            if (!empty($request->phone)) {
                $result->name = $request->name;
            }
            if (!empty($request->phone)) {
                $result->code = $request->code;
            }
            if (!empty($request->phone)) {
                $result->status = $request->status;
            }
            $result->update();
            /*make response*/
            $response["success"] = true;
            $response["message"] = "Application Submitted!";
        }
        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = City::where('uuid',$id)->first();
        if($role){
            $role->delete();
            return response()->json(
                [
                    'status' => config('constant.messages.Success'),
                    'message' => 'Record deleted successfully',
                    'code' => config('constant.codes.success'),
                    'data' => [],
                ]);
        }else{
            return response()->json(
                [
                    'status' => config('constant.messages.Failure'),
                    'message' => 'Record not delete',
                    'code' => config('constant.codes.badRequest'),
                    'data' => [],
                ]);
        }
    }

    public function show_cities_by_country(Request $request){
        $validationRules = [
            'uuid' => 'required|uuid|exists:countries,uuid',
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
            $cities = Country::with('city')->where('uuid',$request->uuid)->where('status','active')->first();
            return response()->json(
                [
                    'status' => config('constant.messages.Success'),
                    'message' => 'Cities data',
                    'code' => config('constant.codes.success'),
                    'data' => $cities
                ]);
        }
    }


}
