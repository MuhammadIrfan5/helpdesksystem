<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeMiddleware
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
        if($request->header('Authorization') && Auth::guard('employee')->check() && $request->accepts(['application/json'])){
            if(strtolower(auth()->user()->status)  === "active") {
                return $next($request);
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'status' => config('constant.messages.Unauthorized'),
                        'message' => 'Account Not Active',
                        'code' => config('constant.codes.Unauthorized'),
                        'data' => [],
                    ]);
            }
        }else {
            return response()->json(
                [
                    'success' => false,
                    'status' => config('constant.messages.Unauthorized'),
                    'message' => 'Unauthorized Access',
                    'code' => config('constant.codes.Unauthorized'),
                    'data' => [],
                ]);
        }
    }
}
