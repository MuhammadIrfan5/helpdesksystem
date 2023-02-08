<?php

namespace App\Http\Controllers\CommonAPI;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CountryController extends Controller
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
    public function create(Request $request)
    {

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
            "phone" => "required|min:11|numeric",
            "code" => "required",
            "name" => "required",
            "symbol" => "required",
            "capital" => "required",
            "currency" => "required",
            "continent" => "required",
            "continentCode" => "required",
        ];
        $validator = Validator::make($request->all(), $validationRules);
        /*check is validation success*/
        if ($validator->fails()) {
            $response["message"] = $validator->errors()->first();
        } else {
            /*create user*/
            $apply = [
                'uuid' => Str::uuid(),
                'phone' => $request->phone,
                'code' => $request->code,
                'name' => $request->name,
                'symbol' => $request->symbol,
                'capital' => $request->capital,
                'currency' => $request->currency,
                'continent' => $request->continent,
                'continent_code' => $request->continentCode,
                'status' => 'active',

            ];
            $save = Country::create($apply);
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
            "countryId" => "required|exists:countries,id",
            "phone" => "nullable|min:11|numeric",
            "code" => "nullable",
            "name" => "nullable",
            "symbol" => "nullable",
            "capital" => "nullable",
            "currency" => "nullable",
            "continent" => "nullable",
            "continentCode" => "nullable",
        ];
        $validator = Validator::make($request->all(), $validationRules);
        /*check is validation success*/
        if ($validator->fails()) {
            $response["message"] = $validator->errors()->first();
        } else {
            /*update country*/
            $result = Country::where('uuid', $request->uuId)->first();
            if (!empty($request->phone)) {
                $result->phone = $request->phone;
            }
            if (!empty($request->code)) {
                $result->code = $request->code;
            }
            if (!empty($request->code)) {
                $result->name = $request->name;
            }
            if (!empty($request->code)) {
                $result->symbol = $request->symbol;
            }
            if (!empty($request->code)) {
                $result->capital = $request->capital;
            }
            if (!empty($request->code)) {
                $result->currency = $request->currency;
            }
            if (!empty($request->code)) {
                $result->continent = $request->continent;
            }
            if (!empty($request->code)) {
                $result->continent_code = $request->continentCode;
            }
            if (!empty($request->code)) {
                $result->status = 'active';
            }
            $result->save();
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
        //
    }
}
