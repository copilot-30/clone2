<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            switch (Auth::user()->role) {
                case 'ADMIN':
                    return redirect('/admin/dashboard');
                case 'DOCTOR':
                    return redirect('/doctor/dashboard');
                case 'PATIENT':
                    return redirect('/patient/dashboard'); // Patient's default dashboard
                default:
                    return redirect('/login')->with('error', 'Unauthorized access.'); // Fallback
            }
        }

        return $next($request);
    }
}
