<?php

namespace App\Http\Middleware;

use Closure;

class CheckPatientProfileCompletion
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
        if (Auth::check() && Auth::user()->user_type === 'patient') {
            // Check if patient profile exists
            $patient = Patient::where('user_id', Auth::id())->first();

            if (!$patient) {
                // If patient profile does not exist, redirect to the fill-up form
                return redirect('/patient-details');
            }
        }
        return $next($request);
    }
}
