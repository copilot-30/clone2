<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Patient;
use App\Subscription;

class MembershipCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$planNames
     * @return mixed
     */
    public function handle($request, Closure $next, ...$planNames)
    {
 
        if (!Auth::check()) {
            return redirect()->route('login'); // Or any other appropriate redirect for unauthenticated users
        }

        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            // If there's no patient profile, they can't have a subscription
            return redirect()->route('patient.plans')->with('error', 'Please complete your patient profile to access this feature.');
        }

        $currentSubscription = $patient->subscriptions()->first();

        // If no active subscription, redirect to plans page
        if (!$currentSubscription || $currentSubscription->status !== 'active') {
            return redirect()->route('patient.plans')->with('error', 'You need an active subscription to access this feature.');
        }

        // If specific plan names are provided, check if the current subscription matches any of them
        if (!empty($planNames)) {
            if (!in_array($currentSubscription->plan->name, $planNames)) {
                return redirect()->route('patient.plans')->with('error', 'Your current subscription does not grant access to this feature.');
            }
        }

        return $next($request);
    }
}