<?php

namespace App\Http\Middleware;

use Closure;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\Sentinel::check()) {
            return response()->json(['success' => false, 'data' => ['code' => 401], 'msg' =>  'No hay usuario', 'error' => 40], 200);
        } else {
            return $next($request);
        }
    }
}