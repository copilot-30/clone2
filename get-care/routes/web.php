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

Route::get('/', 'PublicController@landingPage')->name('landing');

Route::get('/login', 'PublicController@loginPage')->name('login');

Route::get('/register', 'PublicController@registerPage')->name('register');

Route::get('/account-recovery', 'PublicController@accountRecoveryPage')->name('account-recovery');

Route::get('/privacy-policy', 'PublicController@privacyPolicyPage')->name('privacy-policy');


Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/account-recovery', 'AuthController@sendPasswordResetEmail')->name('send-password-reset-email');
 

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', 'AuthController@logout')->name('logout');

    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::post('/doctors', 'AdminController@createDoctor');
        Route::get('/doctors', 'AdminController@listDoctors');
        Route::put('/doctors/{id}', 'AdminController@editDoctor');
        Route::delete('/doctors/{id}', 'AdminController@deleteDoctor');
        Route::get('/patients', 'AdminController@listPatients');
        Route::get('/patients/{id}', 'AdminController@viewPatientDetails');
        Route::get('/appointments', 'AdminController@listAllAppointments');
        Route::get('/appointments/filter', 'AdminController@filterAppointments');
        Route::put('/appointments/{id}/cancel', 'AdminController@cancelAppointment');
        Route::put('/appointments/{id}/reschedule', 'AdminController@rescheduleAppointment');
        Route::put('/appointments/{id}/reassign', 'AdminController@reassignAppointment');
        Route::get('/doctors/{id}/performance', 'AdminController@viewDoctorPerformanceMetrics');
        Route::get('/patients/{id}/consultation-history', 'AdminController@viewConsultationHistory');
        Route::get('/subscriptions', 'AdminController@listSubscriptions');
        Route::get('/transactions', 'AdminController@monitorTransactions');
    });

    // Doctor routes
    Route::prefix('doctor')->middleware('role:doctor')->group(function () {
        Route::get('/appointments', 'DoctorAppointmentController@index');
        Route::post('/appointments', 'DoctorAppointmentController@store');
        Route::get('/appointments/{id}', 'DoctorAppointmentController@show');
        Route::put('/appointments/{id}', 'DoctorAppointmentController@update');
        Route::delete('/appointments/{id}', 'DoctorAppointmentController@destroy');
        Route::get('/profile/{id}', 'DoctorProfileController@show');
        Route::put('/profile/{id}', 'DoctorProfileController@update');
        Route::get('/patients', 'DoctorController@listAssignedPatients');
        Route::get('/patients/{id}/consultation-history', 'DoctorController@viewPatientConsultationHistory');
        Route::post('/patient-notes', 'PatientNoteController@store');
    });

    // Patient routes
    Route::prefix('patient')->middleware('role:patient')->group(function () {

        Route::get('/patient-details', 'PatientController@showProfileForm')->name('patient-details'); // Add a name to the route for easy redirection
        
        Route::post('/patient-profile', 'PatientController@storeProfile')->name('patient.profile.store');
Route::group(['middleware' => 'patient.profile.check'], function () {
    Route::get('/dashboard', 'PatientController@dashboard')->name('patient.dashboard');
    Route::get('/appointments', 'PatientAppointmentController@index');
    Route::post('/appointments', 'PatientAppointmentController@store');
    Route::get('/appointments/{id}', 'PatientAppointmentController@show');
    Route::put('/appointments/{id}', 'PatientAppointmentController@update');
    Route::delete('/appointments/{id}', 'PatientAppointmentController@destroy');
    Route::get('/profile', 'PatientProfileController@show');
    Route::put('/profile', 'PatientProfileController@update');
    Route::get('/chat', 'PatientController@chat')->name('patient.chat');
        Route::get('/chat', 'PatientController@chat')->name('patient.chat');
        
});
        
    });
});
