<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    /**
    * Handle an incoming request.
    *
    * @param  Request  $request
    * @param  Closure  $next
    * @param  string|null  $guard
    * @return mixed
    */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        return Auth::guard($guard)->check()
            ? redirect(RouteServiceProvider::HOME)
            : $next($request);
    }
}
