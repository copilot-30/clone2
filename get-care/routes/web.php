<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('components/landing-page');
});

Route::get('/login', function () {
    return view('components/login-page');
});

Route::get('/register', function () {
    return view('components/register-page');
});

Route::get('/account-recovery', function () {
    return view('components/account-recovery');
});

Route::get('/privacy-policy', function () {
    return view('privacy_policy');
});

Route::get('/dashboard', function () {
    return view('components/dashboard');
});

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/account-recovery', 'AuthController@sendPasswordResetEmail'); // Assuming a method for account recovery
Route::post('/admin/doctors', 'AdminController@createDoctor');

