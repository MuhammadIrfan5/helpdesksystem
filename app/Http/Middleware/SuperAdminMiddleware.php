<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,...$role)
    {
        if ($request->hasHeader( 'Authorization' )
            && Auth::guard('users')->check()
            && (Auth::user()->role->slug == $role[0] || Auth::user()->role->slug == $role[1])
            && $request->accepts(['application/json']) ) {
            return $next($request);
        }else {
            return response()->json(
                [
                    'status' => config('constant.messages.Unauthorized'),
                    'message' => 'Unauthorized Access',
                    'code' => config('constant.codes.Unauthorized'),
                    'data' => [],
                ]);
        }
    }
}
