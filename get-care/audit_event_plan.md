# AuditableEvent Implementation Plan

This document outlines the `AuditableEvent` calls to be implemented or reviewed in `DoctorController.php` and `AdminController.php`.

## `app/Http/Controllers/DoctorController.php`

### Functions with Existing `AuditableEvent` Calls (for review)

*   `storeDoctorDetails()`
    *   Event Type: `doctor_details_added`
    *   Data: `['doctor_user_id' => $user->id, 'email' => $user->email]`
*   `updateDoctorDetails()`
    *   Event Type: `doctor_details_updated`
    *   Data: `['doctor_user_id' => $user->id, 'email' => $user->email]`
*   `storeClinic()`
    *   Event Type: `clinic_created`
    *   Data: `['clinic_id' => $clinic->id, 'clinic_name' => $clinic->name]`
*   `updateClinic()`
    *   Event Type: `clinic_updated`
    *   Data: `['clinic_id' => $clinic->id, 'clinic_name' => $clinic->name]`
*   `deleteClinic()`
    *   Event Type: `clinic_deleted`
    *   Data: `['clinic_id' => $clinic->id, 'clinic_name' => $clinic->name]`
*   `cancelAppointment()`
    *   Event Type: `doctor_appointment_cancelled`
    *   Data: `['appointment_id' => $appointment->id, 'patient_id' => $appointment->patient_id, 'doctor_id' => $appointment->doctor_id]`
*   `rescheduleAppointment()`
    *   Event Type: `doctor_appointment_rescheduled`
    *   Data: `['appointment_id' => $appointment->id, 'patient_id' => $appointment->patient_id, 'doctor_id' => $appointment->doctor->id, 'new_datetime' => $appointment->appointment_datetime]`
*   `storeSoapNote()`
    *   Event Type: `soap_note_created`
    *   Data: `['soap_note_id' => $soapNote->id, 'patient_id' => $patientId, 'doctor_id' => $doctor->id]`
*   `updateSoapNote()`
    *   Event Type: `soap_note_updated`
    *   Data: `['soap_note_id' => $soapNote->id, 'patient_id' => $patientId, 'doctor_id' => $doctor->id]`
*   `storeSharedCase()`
    *   Event Type: `shared_case_created`
    *   Data: `['patient_id' => $request->input('patient_id'), 'sharing_doctor_id' => $sharingDoctor->id, 'receiving_doctor_id' => $receivingDoctor->id]`
*   `acceptSharedCaseInvitation()`
    *   Event Type: `shared_case_accepted`
    *   Data: `['shared_case_id' => $sharedCase->id, 'patient_id' => $sharedCase->patient_id, 'receiving_doctor_id' => Auth::user()->doctor->id]`
*   `declineSharedCaseInvitation()`
    *   Event Type: `shared_case_declined`
    *   Data: `['shared_case_id' => $sharedCase->id, 'patient_id' => $sharedCase->patient_id, 'receiving_doctor_id' => Auth::user()->doctor->id]`
*   `cancelSharedCaseInvitation()`
    *   Event Type: `shared_case_cancelled`
    *   Data: `['shared_case_id' => $sharedCase->id, 'patient_id' => $sharedCase->patient_id, 'sharing_doctor_id' => Auth::user()->doctor->id]`
*   `removeSharedCase()`
    *   Event Type: `shared_case_removed`
    *   Data: `['shared_case_id' => $sharedCase->id, 'patient_id' => $sharedCase->patient_id, 'receiving_doctor_id' => $sharedCase->receiving_doctor_id]`
*   `removeDeclinedSharedCase()`
    *   Event Type: `rejected_shared_case_removed`
    *   Data: `['shared_case_id' => $sharedCase->id, 'patient_id' => $sharedCase->patient_id, 'receiving_doctor_id' => $sharedCase->receiving_doctor_id]`
*   `storePatientPrescription()`
    *   Event Type: `patient_prescription_created`
    *   Data: `['patient_id' => $request->input('patient_id'), 'doctor_id' => $request->input('doctor_id'), 'soap_note_id' => $request->input('soap_note_id')]`
*   `storePatientTestRequest()`
    *   Event Type: `patient_test_request_created`
    *   Data: `['patient_id' => $request->input('patient_id'), 'doctor_id' => $request->input('doctor_id'), 'soap_note_id' => $request->input('soap_note_id')]`

### Functions Requiring New `AuditableEvent` Calls

*   `updateAvailability()`
    *   Event Type: `doctor_availability_updated`
    *   Data: `['doctor_id' => $doctor_id, 'enabled' => $request->input('status')]`
*   `storeAppointment()`
    *   Event Type: `doctor_appointment_created`
    *   Data: `['appointment_id' => $appointment->id, 'patient_id' => $appointment->patient_id, 'doctor_id' => $appointment->doctor_id]`
*   `updateSubscription()`
    *   Event Type: `subscription_updated_by_admin`
    *   Data: `['subscription_id' => $subscription->id, 'patient_id' => $subscription->patient_id, 'status' => $validatedData['status']]`

## `app/Http/Controllers/AdminController.php`

### Functions with Existing `AuditableEvent` Calls (for review)

*   `storeUser()`
    *   Event Type: `user_created`
    *   Data: `['user_id' => $user->id, 'email' => $user->email, 'role' => $user->role]`
*   `updateUser()`
    *   Event Type: `user_updated`
    *   Data: `['user_id' => $user->id, 'email' => $user->email, 'role' => $user->role]`
*   `deleteUser()`
    *   Event Type: `user_deleted`
    *   Data: `['user_id' => $user_id, 'email' => $email]`
*   `storePatientDetails()`
    *   Event Type: `patient_details_added`
    *   Data: `['patient_user_id' => $user->id, 'email' => $user->email]`
*   `updatePatientDetails()`
    *   Event Type: `patient_details_updated`
    *   Data: `['patient_user_id' => $user->id, 'email' => $user->email]`
*   `cancelAppointment()`
    *   Event Type: `appointment_cancelled`
    *   Data: `['appointment_id' => $appointment->id, 'patient_id' => $appointment->patient_id, 'doctor_id' => $appointment->doctor_id]`
*   `rescheduleAppointment()`
    *   Event Type: `appointment_rescheduled`
    *   Data: `['appointment_id' => $appointment->id, 'old_datetime' => $oldDateTime, 'new_datetime' => $appointment->appointment_datetime]`
*   `deleteDoctor()`
    *   Event Type: `doctor_deleted`
    *   Data: `['user_id' => $user_id, 'email' => $email]`
*   `storeDoctorDetails()`
    *   Event Type: `doctor_details_added`
    *   Data: `['doctor_user_id' => $user->id, 'email' => $user->email]`
*   `updateDoctorDetails()`
    *   Event Type: `doctor_details_updated`
    *   Data: `['doctor_user_id' => $user->id, 'email' => $user->email]`

### Functions Requiring New `AuditableEvent` Calls

*   `reassignAppointment()`
    *   Event Type: `appointment_reassigned`
    *   Data: `['appointment_id' => $appointment->id, 'old_doctor_id' => $oldDoctorId, 'new_doctor_id' => $appointment->doctor_id]`
*   `editDoctor()` (function `updateDoctor` is its content and functionality)
    *   Event Type: `doctor_profile_updated_by_admin`
    *   Data: `['doctor_id' => $doctor->id, 'user_id' => $doctor->user_id, 'email' => $doctor->email]`
*   `updatePaymentStatus()`
    *   Event Type: `payment_status_updated`
    *   Data: `['payment_id' => $payment->id, 'status' => $validatedData['status'], 'user_id' => $payment->user_id]`
*   `storePlan()`
    *   Event Type: `plan_created`
    *   Data: `['plan_id' => $plan->id, 'name' => $validatedData['name']]`
*   `updatePlan()`
    *   Event Type: `plan_updated`
    *   Data: `['plan_id' => $plan->id, 'name' => $validatedData['name']]`
*   `deletePlan()`
    *   Event Type: `plan_deleted`
    *   Data: `['plan_id' => $plan->id, 'name' => $plan->name]`