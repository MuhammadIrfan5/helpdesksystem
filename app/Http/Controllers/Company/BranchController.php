<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyBranch;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show_branch_by_company()
    {
        try {
            if(Auth::guard('company')->check()){
                $branches = CompanyBranch::where('company_id', auth()->user()->id)->where('status','active')->paginate(5);
            }elseif(Auth::guard('users')->check()) {
                $branches = CompanyBranch::where('status','active')->paginate(5);
            }
            if ($branches) {
                foreach ($branches  as $branch){
                    $branch->label = $branch->branch_name;
                    $branch->value = $branch->uuid;
                }
                return response()->json(
                    [
                        'status' => config('constant.messages.Success'),
                        'message' => 'Branches record fetched successfully',
                        'code' => config('constant.codes.success'),
                        'data' => $branches,
                    ]);
            } else {
                return response()->json(
                    [
                        'status' => config('constant.messages.Failure'),
                        'message' => 'Data not found',
                        'code' => config('constant.codes.badRequest'),
                        'data' => [],
                    ]);
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => config('constant.messages.Failure'),
                    'message' => $e,
                    'code' => config('constant.codes.internalServer'),
                    'data' => [],
                ]);
        }

    }

    public function index()
    {
        //
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
        //
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
        //
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
