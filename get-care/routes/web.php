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
 

Route::post('/ask-ai', 'GPTController@getMedicalSuggestion')->name('ask-ai');

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

    // Google OAuth Routes
    Route::get('/auth/google/redirect', 'PatientController@redirectToGoogle')->name('google.auth.redirect');
    Route::get('/auth/google/callback', 'PatientController@handleGoogleCallback')->name('google.auth.callback');

    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');

        // Doctor Management Routes
        // Route::post('/doctors', 'AdminController@createDoctor');
        Route::get('/doctors', 'AdminController@listDoctors')->name('admin.doctors');
        // Route::put('/doctors/{id}', 'AdminController@editDoctor');
        Route::post('/doctors/{user_id}/store-details', 'AdminController@storeDoctorDetails')->name('admin.doctors.store_details');
        Route::put('/doctors/{user_id}/update-details', 'AdminController@updateDoctorDetails')->name('admin.doctors.update_details');
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
            Route::get('/dashboard', 'DoctorController@dashboard')->name('doctor.dashboard');
            
            Route::get('/edit-doctor', 'DoctorController@editDoctor')->name('doctor.edit');
            Route::put('/update-doctor', 'DoctorController@updateDoctorDetails')->name('doctor.update');

            Route::get('/availability', 'DoctorController@editAvailability')->name('doctor.availability.edit');
            Route::put('/availability', 'DoctorController@updateAvailability')->name('doctor.availability.update');

            // Clinic Management Routes
            Route::get('/clinics', 'DoctorController@listClinics')->name('doctor.clinics.list');
            Route::get('/clinics/create', 'DoctorController@createClinic')->name('doctor.clinics.create');
            Route::post('/clinics', 'DoctorController@storeClinic')->name('doctor.clinics.store');
            Route::get('/clinics/{clinic}/edit', 'DoctorController@editClinic')->name('doctor.clinics.edit');
            Route::put('/clinics/{clinic}', 'DoctorController@updateClinic')->name('doctor.clinics.update');
            Route::delete('/clinics/{clinic}', 'DoctorController@deleteClinic')->name('doctor.clinics.delete');


            Route::get('/appointments', 'DoctorController@listAppointments')->name('doctor.appointments.list');
            Route::post('/appointments', 'DoctorController@storeAppointment')->name('doctor.appointments.store');
            Route::get('/appointments/{appointment}', 'DoctorController@viewAppointment')->name('doctor.appointments.view');
            Route::put('/appointments/{appointment}/cancel', 'DoctorController@cancelAppointment')->name('doctor.appointments.cancel');
            // Route::get('/appointments', 'DoctorAppointmentController@index'); // Keep existing if needed
            // Route::post('/appointments', 'DoctorAppointmentController@store'); // Keep existing if needed
            // Route::get('/appointments/{id}', 'DoctorAppointmentController@show'); // Keep existing if needed
            // Route::put('/appointments/{id}', 'DoctorAppointmentController@update'); // Keep existing if needed
            // Route::delete('/appointments/{id}', 'DoctorAppointmentController@destroy'); // Keep existing if needed
        
            // Route::get('/patients', 'DoctorController@viewPatients')->name('doctor.patients.view');
            Route::get('/patients/{patient_id?}', 'DoctorController@viewPatients')->name('doctor.patients.view');
            Route::get('/patients/{id}/consultation-history', 'DoctorController@viewPatientConsultationHistory');
            Route::post('/patient-notes/store', 'PatientNoteController@store')->name('doctor.patient-notes.store');
            Route::post('/soap-notes/store', 'DoctorController@storeSoapNote')->name('doctor.soap-notes.store');
            Route::post('/shared-cases/store', 'DoctorController@storeSharedCase')->name('doctor.shared-cases.store');
            Route::get('/shared-cases/invitations', 'DoctorController@listSharedCaseInvitations')->name('doctor.shared-cases.invitations');
            Route::post('/shared-cases/{sharedCase}/accept', 'DoctorController@acceptSharedCaseInvitation')->name('doctor.shared-cases.accept');
            Route::post('/shared-cases/{sharedCase}/cancel', 'DoctorController@cancelSharedCaseInvitation')->name('doctor.shared-cases.cancel');
            Route::post('/shared-cases/{sharedCase}/remove', 'DoctorController@removeSharedCase')->name('doctor.shared-cases.remove');
            Route::post('/shared-cases/{sharedCase}/remove-rejected', 'DoctorController@removeRejectedSharedCase')->name('doctor.shared-cases.remove-rejected');
            Route::get('/search-doctors', 'DoctorController@searchDoctors')->name('doctor.search-doctors');
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
            // Route::get('/appointments/{id}', 'PatientAppointmentController@show');
            // Route::put('/appointments/{id}', 'PatientAppointmentController@update');
            // Route::delete('/appointments/{id}', 'PatientAppointmentController@destroy');
            Route::get('/profile', 'PatientProfileController@show');
            Route::put('/profile', 'PatientProfileController@update');
            Route::get('/chat', 'PatientController@chat')->name('patient.chat');
            Route::get('/ai-consult', 'PatientController@aiConsult')->name('patient.ai-consult');
            Route::post('/ai-consult', 'OpenLLMController@getMedicalSuggestion');
            Route::get('/select-doctor', 'PatientController@showDoctorSelectionForm')->name('patient.select-doctor');
            Route::post('/attending-physician', 'PatientController@storeAttendingPhysician')->name('patient.storeAttendingPhysician');
            Route::get('/appointments/select-type/{doctor_id}', 'PatientController@showAppointmentTypeForm')->name('patient.select-appointment-type');
            Route::get('/appointments/select-date-time', 'PatientController@showDateTimeSelectionForm')->name('patient.show-date-time-selection');
            Route::post('/appointments/store', 'PatientController@storeAppointment')->name('patient.store-appointment');
            Route::get('/appointments/confirmation/{appointment_id}', 'PatientController@showAppointmentConfirmation')->name('patient.appointment-confirmed');
            Route::get('/attending-physician-details', 'PatientController@showAttendingPhysicianDetails')->name('patient.attending-physician-details');
        });
        
    });
});
