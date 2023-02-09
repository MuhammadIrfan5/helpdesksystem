<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEmployeeLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
//        dd(count_company_engineer(auth()->user()->id)['engineer']);
        if(get_role_data_by_id($request->role_id)['slug'] == 'engineer'){
            if(count_company_engineer(auth()->user()->id)['engineer'] < auth()->user()->engineer_limit){
                return $next($request);
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'your engineer registration limit reached.',
                        'code' => config('constant.codes.Failure'),
                        'data' => [],
                    ]);
            }
        }
        if(get_role_data_by_id($request->role_id)['slug'] == 'employee'){
            if(count_company_engineer(auth()->user()->id)['employee'] < auth()->user()->employee_limit){
                return $next($request);
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Failure'),
                        'message' => 'your employee registration limit reached.',
                        'code' => config('constant.codes.Failure'),
                        'data' => [],
                    ]);
            }
        }else {
            return response()->json(
                [
                    'success' => false,
                    'status' => config('constant.messages.Failure'),
                    'message' => 'Invalid Role choosen',
                    'code' => config('constant.codes.Failure'),
                    'data' => [],
                ]);
        }
    }
}
