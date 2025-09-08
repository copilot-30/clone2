<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use App\Patient;

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
 
        if (Auth::check() && strtolower(Auth::user()->role )=== 'patient') {
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
