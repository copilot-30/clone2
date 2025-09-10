<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use App\Doctor;

class CheckDoctorProfileCompletion
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
 
        if (Auth::check() && strtolower(Auth::user()->role )=== 'doctor') { 
            $doctor = Doctor::where('user_id', Auth::user()->id)->first();
 
            if (!$doctor) { 
                return redirect(route('doctor.create'));
            } 

        }
        return $next($request);
    }
}
