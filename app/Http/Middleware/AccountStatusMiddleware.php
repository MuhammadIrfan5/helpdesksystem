<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountStatusMiddleware
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
        if($request->header('Authorization') && $request->accepts(['application/json'])){
            if(Auth::guard('company')->check() && strtolower(Auth::user()->status == 'active') && Auth::user()->is_approved == 1){
                return $next($request);
            }
            if(Auth::guard('employee')->check() && strtolower(Auth::user()->status == 'active')){
//                && auth()->user()->company->is_approved == 1 && auth()->user()->company->status == 'active'
                return $next($request);
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.badRequest'),
                        'message' => 'account inactivated',
                        'code' => config('constant.codes.badRequest'),
                        'data' => [],
                    ]);
            }
        }
    }
}
