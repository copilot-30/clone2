<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

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
