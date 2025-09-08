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
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::user();

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->roles->contains('name', $role)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized: You do not have the required role.'], 403);
    }
}
