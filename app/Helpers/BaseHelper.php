<?php

use Illuminate\Support\Facades\URL;

    function checkAdminAndSupAdmin($roleName)
    {
        if (auth()->user()->role->slug === $roleName) {
            return true;
        } else {
            return false;
        }
    }

    function get_role_data_by_id($id){
        $role = \App\Models\Role::findorfail($id);
        if($role){
            return $role;
        }
        return false;
    }



    function generate_registaion_no()
    {
        $num = '1234567890';
        $reg_no = rand(1000, 9999).str_shuffle($num);
        return $reg_no;
    }

    function count_company_engineer($company_id){
        $employee = array();
        $employee['engineer'] = \App\Models\Employee::where('company_id',$company_id)->where('role_id',4)->count();
        $employee['employee'] = \App\Models\Employee::where('company_id',$company_id)->where('role_id',5)->count();
        return $employee;
    }

    function imageUpload($query) // Taking input image as parameter
    {
        $image_name = time();
        $ext = strtolower($query->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name . '.' . $ext;
        $upload_path = 'user/';    //Creating Sub directory in Public folder to put image
        $image_url = $upload_path . $image_full_name;
        $success = $query->move($upload_path, $image_full_name);
        return URL::to('/' . $image_url); // Just return image
    }
