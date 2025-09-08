<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function landingPage()
    {
        return view('components/landing-page');
    }

    public function loginPage()
    {
        return view('components/login-page');
    }

    public function registerPage()
    {
        return view('components/register-page');
    }

    public function accountRecoveryPage()
    {
        return view('components/account-recovery');
    }

    public function privacyPolicyPage()
    {
        return view('privacy_policy');
    }
}