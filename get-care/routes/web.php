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
        Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');

        // Doctor Management Routes
        Route::post('/doctors', 'DoctorController@createDoctor');
        Route::get('/doctors', 'DoctorController@listDoctors')->name('admin.doctors');
        Route::put('/doctors/{id}', 'DoctorController@editDoctor');
        Route::post('/doctors/{user_id}/store-details', 'DoctorController@storeDoctorDetails')->name('admin.doctors.store_details');
        Route::put('/doctors/{user_id}/update-details', 'DoctorController@updateDoctorDetails')->name('admin.doctors.update_details');
        // Route::get('/doctors/{user_id}/create-details', 'DoctorController@createDetailsForm')->name('admin.doctors.create_details');
        
        Route::delete('/doctors/{id}', 'DoctorController@deleteDoctor');

        // User Management Routes
        Route::get('/users', 'AdminController@listUsers')->name('admin.users');
        Route::get('/users/create','AdminController@createUser')->name('admin.users.create');
        Route::post('/users', 'AdminController@storeUser')->name('admin.users.store');
        Route::get('/users/{id}/edit', 'AdminController@editUser')->name('admin.users.edit');
        Route::put('/users/{id}', 'AdminController@editUser')->name('admin.users.update');
        Route::delete('/users/{id}', 'AdminController@deleteUser')->name('admin.users.delete');
        
        
        Route::get('/patients', 'AdminController@listPatients')->name('admin.patients');
        Route::get('/patients/{id}', 'AdminController@viewPatientDetails');
        Route::get('/patients/{user_id}/create-details', function ($user_id) {
            $user = App\User::findOrFail($user_id);
            return view('admin.patient-details-form', compact('user'));
        })->name('admin.patients.create_details');
        Route::post('/patients/{user_id}/store-details', 'AdminController@storePatientDetails')->name('admin.patients.store_details');
        Route::put('/patients/{user_id}/update-details', 'AdminController@updatePatientDetails')->name('admin.patients.update_details');

        Route::get('/doctors/{id}/performance', 'DoctorController@viewDoctorPerformanceMetrics')->name('admin.doctors.performance');
        
        Route::get('/appointments', 'AdminController@listAllAppointments')->name('admin.appointments');
        Route::get('/appointments/filter', 'AdminController@filterAppointments');
        Route::put('/appointments/{id}/cancel', 'AdminController@cancelAppointment');
        Route::put('/appointments/{id}/reschedule', 'AdminController@rescheduleAppointment');
        Route::put('/appointments/{id}/reassign', 'AdminController@reassignAppointment');
        Route::get('/patients/{id}/consultation-history', 'AdminController@viewConsultationHistory');
        Route::get('/subscriptions', 'AdminController@listSubscriptions')->name('admin.subscriptions');
        Route::get('/transactions', 'AdminController@monitorTransactions')->name('admin.transactions');
        Route::get('/audit-logs', 'AdminController@viewAuditLogs')->name('admin.audit_logs');
    });

    // Doctor routes
    Route::prefix('doctor')->middleware('role:doctor')->group(function () {
        Route::get('/create-doctor', 'DoctorController@createDoctor')->name('doctor.create');
        Route::post('/store-doctor', 'DoctorController@storeDoctorDetails')->name('doctor.store');
        Route::group(['middleware' => 'doctor.profile.check'], function () {

            Route::get('/', 'DoctorController@dashboard')->name('doctor.dashboard');
            
            Route::get('/edit-doctor', 'DoctorController@editDoctor')->name('doctor.edit');
            Route::put('/update-doctor', 'DoctorController@updateDoctorDetails')->name('doctor.update');

            Route::get('/availability', 'DoctorController@editAvailability')->name('doctor.availability.edit');
            Route::put('/availability', 'DoctorController@updateAvailability')->name('doctor.availability.update');


            Route::get('/appointments', 'DoctorAppointmentController@index');
            Route::post('/appointments', 'DoctorAppointmentController@store');
            Route::get('/appointments/{id}', 'DoctorAppointmentController@show');
            Route::put('/appointments/{id}', 'DoctorAppointmentController@update');
            Route::delete('/appointments/{id}', 'DoctorAppointmentController@destroy');
        
            Route::get('/patients', 'DoctorController@listAssignedPatients');
            Route::get('/patients/{id}/consultation-history', 'DoctorController@viewPatientConsultationHistory');
            Route::post('/patient-notes', 'PatientNoteController@store');
        });
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
        });
        
    });
});
