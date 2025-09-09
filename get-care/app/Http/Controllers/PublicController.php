<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function landingPage()
    {
        return view('public.landing-page');
    }

    public function loginPage()
    {
        return view('public.login-page');
    }

    public function registerPage()
    {
        return view('public.register-page');
    }

    public function accountRecoveryPage()
    {
        return view('public.account-recovery');
    }

    public function privacyPolicyPage()
    {
        return view('privacy_policy');
    }
}