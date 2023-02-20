<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Str;

class UserController extends Controller
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
            $rsp = User::create($userData);
            /*create user token*/
            $token = $rsp->createToken('userapptoken')->plainTextToken;
            /*make response*/

            $rsp = User::find($rsp["id"]);
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
        if ($request->accepts(['application/json'])) {
            $validationRules = [
                'email' => 'required|string',
                'password' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'Validation Errors',
                        'message' => $validator->errors()->first(),
                        'code' => config('constant.codes.validation'),
                        'data' => [],
                    ]);

            } else {
                $user = User::Where('email', $request['email'])->first();
                if (!empty($user) && ($user->role->slug === 'admin' || $user->role->slug === 'super-admin')) {
                    if (!$user || !Hash::check($request["password"], $user->password)) {
                        return response()->json(
                            [
                                'success' => false,
                                'status' => config('constant.messages.Unauthorized'),
                                'message' => 'Invalid Credentials',
                                'code' => config('constant.codes.Unauthorized'),
                                'data' => [],
                            ]);
                    } else {
                        if ($user != null) {
                            $user->tokens()->delete();
                        }
                        $token = $user->createToken('userToken')->plainTextToken;
                        $user->remember_token = $token;
                        $user->save();
                        $user->usertoken = $token;
                        return response()->json(
                            [
                                'success' => true,
                                'status' => config('constant.messages.loginSuccess'),
                                'message' => 'Logged In',
                                'code' => config('constant.codes.success'),
                                'data' => $user,
                            ]);
                    }
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'status' => config('constant.messages.Unauthorized'),
                            'message' => 'Invalid Role',
                            'code' => config('constant.codes.Unauthorized'),
                            'data' => [],
                        ]);
                }
            }
        } else {
            return response()->json(
                [
                    'success' => false,
                    'status' => config('constant.messages.badRequest'),
                    'message' => 'Only Accepts Application json',
                    'code' => config('constant.codes.badRequest'),
                    'data' => [],
                ]);
        }
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

    public function allUsersLogout(Request $request)
    {
        if ($request->accepts(['application/json'])) {
            $user = auth()->user()->tokens()->delete();
            return response()->json(
                [
                    'success' => true,
                    'status' => config('constant.messages.Success'),
                    'message' => 'Logout Successfully',
                    'code' => config('constant.codes.success'),
                    'data' => [],
                ]);
        } else {
            return response()->json(
            [
                'success' => false,
                'status' => config('constant.messages.badRequest'),
                'message' => 'Only Accepts Application json',
                'code' => config('constant.codes.badRequest'),
                'data' => [],
            ]);
        }
    }

}
