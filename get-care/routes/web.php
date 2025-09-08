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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', 'AuthController@logout');

    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::post('/doctors', 'AdminController@createDoctor');
        // Other admin routes will go here
    });

    // Doctor routes
    Route::prefix('doctor')->middleware('role:doctor')->group(function () {
        // Doctor specific routes will go here
    });

    // Patient routes
    Route::prefix('patient')->middleware('role:patient')->group(function () {
        // Patient specific routes will go here
    });
});

