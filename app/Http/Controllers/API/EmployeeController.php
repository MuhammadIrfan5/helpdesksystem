<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /*User API Functions*/
    public function register(Request $request)
    {
        /*Response format*/
        $response = [
            "success" => false,
            "message" => "",
        ];
        /*validation rules*/
        $validationRules = [
            'name' => 'required|string',
//            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'required|string|unique:users',
            'address' => 'required|string',
            'area' => 'required|string',
            'city' => 'required|string',
            'gender' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'school_college' => 'nullable|string',
            'type' => 'required|string|in:' . implode(',', $this->userTypes)

        ];
        /*apply validation*/
        $validator = Validator::make($request->all(), $validationRules);
        /*check is validation success*/
        if ($validator->fails()) {
            $response["message"] = $validator->errors()->first();
        } else {
            /*image upload*/
//            $filePath = $this->imageUpload($request->file('image'));
            /*create user*/
            $userData = [
                'name' => $request['name'],
//                'image' => $filePath,
                'phone' => $request['phone'],
                'address' => $request['address'],
                'area' => $request['area'],
                'city' => $request['city'],
                'gender' => $request['gender'],
                'password' => bcrypt($request['password']),
                'email' => $request['email'],
                'school_college' => $request['school_college'] ?? '',
                'type' => $request['type'],
                'referral_code' => empty($request["referralCode"]) ? "" : $request["referralCode"],

            ];
            $rsp = Employee::create($userData);
            /*create user token*/
            $token = $rsp->createToken('userapptoken')->plainTextToken;
            /*make response*/

            $rsp = Employee::find($rsp["id"]);
            $response["success"] = true;
            $response["message"] = "User Successfully Registered!";
            $response["id"] = $rsp["id"];
            $response["name"] = $rsp["name"];
            $response["image"] = "";
            $response["phone"] = $rsp["phone"];
            $response["address"] = $rsp["address"];
            $response["area"] = $rsp["area"];
            $response["city"] = $rsp["city"];
            $response["email"] = $rsp["email"];
            $response["school_college"] = $rsp["school_college"];
            $response["type"] = $rsp["type"];
            $response["rating"] = $rsp["rating"];
            $response["token"] = $token;
            $response["channel"] = "user-" . $rsp["id"];
        }
        return response($response, 201);
    }

    public function login(Request $request)
    {
        /*Response format*/
        $response = [
            "success" => false,
            "message" => ""
        ];
        /*validation rules*/
        $validationRules = [
            'phone' => 'required|string',
            'password' => 'required|string',
        ];
        /*apply validation*/
        $validator = Validator::make($request->all(), $validationRules);
        /*check is validation success*/
        if ($validator->fails()) {
            $response["message"] = $validator->errors()->first();
        } else {
            /*Authenticate user*/
            $rsp = Employee::where('phone', $request['phone'])->first();
            if (!$rsp || !Hash::check($request["password"], $rsp->password)) {
                $response["message"] = "Invalid Login Credentials!";
                return response($response, 201);
            }
            /*create user token*/
            $token = $rsp->createToken('userapptoken')->plainTextToken;
            $rsp->remember_token=$token;
            $rsp->save();
            /*make response*/
            $response["id"] = $rsp["id"];
            $response["name"] = $rsp["first_name"] . " " . $rsp["last_name"];
            $response["image"] = $rsp["image"]??'';
            $response["phone"] = $rsp["phone"];
            $response["address"] = $rsp["address"];
            $response["country"] = $rsp["country_id"];
            $response["city"] = $rsp["city_id"];
            $response["email"] = $rsp["email"];
            $response["role"] = $rsp["role_id"];
            $response["token"] = $token;
            $response["channel"] = "user-" . $rsp["id"];
            $response["success"] = true;
            $response["message"] = "User Successfully Logged In!";
        }
        return response($response, 201);
    }

    public function imageUpload($query) // Taking input image as parameter
    {
        $image_name = time();
        $ext = strtolower($query->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name . '.' . $ext;
        $upload_path = 'user/';    //Creating Sub directory in Public folder to put image
        $image_url = $upload_path . $image_full_name;
        $success = $query->move($upload_path, $image_full_name);
        return URL::to('/' . $image_url); // Just return image
    }
}
