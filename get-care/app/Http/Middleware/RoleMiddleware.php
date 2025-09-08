<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        
        if (!Auth::check()) {
            return redirect('/login'); // Redirect unauthenticated users to login
        }

         return $next($request);

        // $user = Auth::user();
 
        // // If no specific roles are required for this route, proceed
        // if (empty($roles)) {
        //     return $next($request);
        // }

        // // Check if user has any of the required roles for this route
        // $hasRequiredRole = false;
        // foreach ($roles as $role) {
        //     if ($user->roles->contains('name', $role)) {
        //         $hasRequiredRole = true;
        //         break; // User has one of the required roles, no need to check further
        //     }
        // }

        // if ($hasRequiredRole) {
        //     return $next($request); // User has the required role, proceed to the requested route
        // } else {
        //     // User does NOT have the required role for this route, so redirect them based on their actual role
        //     switch ($user->role) {
        //         case 'ADMIN':
        //             return redirect('/admin/dashboard');
        //         case 'DOCTOR':
        //             return redirect('/doctor/dashboard');
        //         case 'PATIENT':
        //             return redirect('/patient/dashboard'); // Patient's default dashboard
        //         default:
        //             return redirect('/login')->with('error', 'Unauthorized access.'); // Fallback
        //     }
        // }
    }
}
