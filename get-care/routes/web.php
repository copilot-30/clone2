<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

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
 

Route::post('/ask-ai', 'OpenLLMController@getMedicalSuggestion')->name('ask-ai');

Route::get('/', 'PublicController@landingPage')->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', 'PublicController@loginPage')->name('login');
    Route::get('/register', 'PublicController@registerPage')->name('register');
    Route::get('/account-recovery', 'PublicController@accountRecoveryPage')->name('account-recovery');
    Route::get('/privacy-policy', 'PublicController@privacyPolicyPage')->name('privacy-policy');
    Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/account-recovery', 'AuthController@sendPasswordResetEmail')->name('send-password-reset-email');
Route::get('/password/reset/{token}', 'AuthController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'AuthController@resetPassword')->name('password.update');
 });





 

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', 'AuthController@logout')->name('logout');

    // Google OAuth Routes
    Route::get('/auth/google/redirect', 'PublicController@redirectToGoogle')->name('google.auth.redirect');
    Route::get('/auth/google/callback', 'PublicController@handleGoogleCallback')->name('google.auth.callback');

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
        Route::put('/users/{id}', 'AdminController@updateUser')->name('admin.users.update');
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
        Route::get('/audit-logs', 'AdminController@viewAuditLogs')->name('admin.audit-logs');

        // Admin Payment Routes
        Route::get('/payments', 'AdminController@listPayments')->name('admin.payments');
        Route::post('/payments/{payment}/update-status', 'AdminController@updatePaymentStatus')->name('admin.payments.update-status');   // Plan Management Routes
        Route::get('/plans', 'AdminController@listPlans')->name('admin.plans');
        Route::get('/plans/create', 'AdminController@createPlan')->name('admin.plans.create');
        Route::post('/plans', 'AdminController@storePlan')->name('admin.plans.store');
        Route::get('/plans/{plan}/edit', 'AdminController@editPlan')->name('admin.plans.edit');
        Route::put('/plans/{plan}', 'AdminController@updatePlan')->name('admin.plans.update');
        Route::delete('/plans/{plan}', 'AdminController@deletePlan')->name('admin.plans.delete');

        // Subscription management routes (handled by DoctorController as per instructions)
        Route::get('/subscriptions/{subscription}/edit', 'DoctorController@editSubscription')->name('admin.subscriptions.edit');
        Route::put('/subscriptions/{subscription}', 'DoctorController@updateSubscription')->name('admin.subscriptions.update');
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
            Route::put('/appointments/{appointment}/reschedule', 'DoctorController@rescheduleAppointment')->name('doctor.appointments.reschedule');
         

            Route::get('/patients/{patient_id?}', 'DoctorController@viewPatients')->name('doctor.patients.view');
            Route::get('/patients/{id}/consultation-history', 'DoctorController@viewPatientConsultationHistory');
            Route::post('/patient-notes/store', 'PatientNoteController@store')->name('doctor.patient-notes.store');
            
            Route::post('/shared-cases/store', 'DoctorController@storeSharedCase')->name('doctor.shared-cases.store');
            Route::get('/shared-cases/{filter?}', 'DoctorController@listSharedCases')->name('doctor.shared-cases.list');
            Route::post('/shared-cases/{sharedCase}/accept', 'DoctorController@acceptSharedCaseInvitation')->name('doctor.shared-cases.accept');
            Route::post('/shared-cases/{sharedCase}/decline', 'DoctorController@declineSharedCaseInvitation')->name('doctor.shared-cases.decline');
            Route::post('/shared-cases/{sharedCase}/cancel', 'DoctorController@cancelSharedCaseInvitation')->name('doctor.shared-cases.cancel');
            Route::post('/shared-cases/{sharedCase}/remove', 'DoctorController@removeSharedCase')->name('doctor.shared-cases.remove');
            Route::post('/shared-cases/{sharedCase}/remove-declined', 'DoctorController@removeDeclinedSharedCase')->name('doctor.shared-cases.remove-rejected');
            Route::get('/search-doctors', 'DoctorController@searchDoctors')->name('doctor.search-doctors');

            Route::get('/soap-notes/create/{patient_id}', 'DoctorController@createSoapNote')->name('doctor.soap-notes.create');
            Route::post('/soap-notes/store', 'DoctorController@storeSoapNote')->name('doctor.soap-notes.store');
            Route::put('/soap-notes/{soap_note_id}/update', 'DoctorController@updateSoapNote')->name('doctor.soap-notes.update');
            Route::post('/patient-prescriptions', 'DoctorController@storePatientPrescription')->name('doctor.patient-prescriptions.store');
            Route::post('/patient-test-requests', 'DoctorController@storePatientTestRequest')->name('doctor.patient-test-requests.store');


             Route::get('/analytics', 'DoctorController@showAnalytics')->name('doctor.analytics');
        });
    });

    // Patient routes
    Route::prefix('patient')->middleware('role:patient')->group(function () {

        Route::get('/patient-details', 'PatientController@showProfileForm')->name('patient-details'); // Add a name to the route for easy redirection
        
        Route::post('/patient-profile', 'PatientController@storeProfile')->name('patient.profile.store');
        Route::group(['middleware' => 'patient.profile.check'], function () {
            //patient free routes
            Route::get('/dashboard', 'PatientController@dashboard')->name('patient.dashboard');
            Route::get('/profile', 'PatientProfileController@show');
            Route::put('/profile', 'PatientProfileController@update');
            Route::get('/ai-consult', 'PatientController@aiConsult')->name('patient.ai-consult');
            Route::post('/ai-consult', 'OpenLLMController@getMedicalSuggestion');

            Route::get('/select-doctor', 'PatientController@showDoctorSelectionForm')->name('patient.select-doctor');
                

            //Plan routes
            Route::get('/plans', 'PatientController@showPlans')->name('patient.plans');
            Route::get('/plans/{plan}/checkout', 'PatientController@showCheckoutForm')->name('patient.plans.checkout');
            Route::post('/plans/{plan}/process-payment', 'PatientController@processPlanPayment')->name('patient.plans.process-payment');
            //payment routes
            Route::get('/payments/{payment}', 'PatientController@showPaymentDetails')->name('patient.payment');


            // HTML <-> PHP SERVER 

            // Model View Controller 

            // -> Web -> controller -> model -> controller -> view


            //core plan routes
            Route::group(['middleware' => 'membership:core,premium'], function () {
                Route::get('/medical-records', 'PatientController@getMedicalRecords')->name('patient.medical-records');

                Route::get('/attending-physician/details', 'PatientController@showAttendingPhysicianDetails')->name('patient.attending-physician-details');
                Route::post('/attending-physician/store', 'PatientController@storeAttendingPhysician')->name('patient.storeAttendingPhysician');
      
                Route::get('/appointments/select-type/{doctor_id}', 'PatientController@showAppointmentTypeForm')->name('patient.select-appointment-type');
                Route::get('/appointments/select-date-time', 'PatientController@showDateTimeSelectionForm')->name('patient.show-date-time-selection');
                Route::post('/appointments/store', 'PatientController@storeAppointment')->name('patient.store-appointment');
                Route::get('/appointments/confirmation/{appointment_id}', 'PatientController@showAppointmentConfirmation')->name('patient.appointment-confirmed');
                Route::get('/chat', 'PatientController@chat')->name('patient.chat');
                Route::get('/download-medical-records', 'PatientController@downloadMedicalRecords')->name('patient.download');
                Route::post('/lab-results/upload/{patientTestRequest}', 'PatientController@uploadLabResult')->name('patient.lab-results.upload');
            });


            //premium only plan routes
            Route::group(['middleware' => 'membership:premium'], function () { 

            });

        });
    });
});

Route::get('/storage/lab_results/{filename}', function ($filename) {
  

    $path = storage_path('app/public/lab_results/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('storage.view');
