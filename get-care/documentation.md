# Controller Documentation and Analysis

This document provides a detailed analysis of the `PatientController`, `DoctorController`, and `AdminController` files, including their functionality, potential issues, and suggestions for improvement.

---

## `app/Http/Controllers/PatientController.php`

This controller handles patient-specific functionalities, including profile management, appointment booking, medical record viewing, and subscription management.

#### `showProfileForm()`
- **Description:** Displays the patient profile form. It retrieves the authenticated user's patient data, or creates a new `Patient` instance if one doesn't exist, to pre-populate the form.
- **Logic:**
    - Gets the authenticated user.
    - Attempts to retrieve the associated patient record. If no patient record is found, a new `App\Patient` object is instantiated.
    - Passes the patient object to the `patient.patient-details-form` view.
- **Used?** Yes, likely linked to a route for patient profile completion/editing.
- **Problems:** None.
- **Suggestions:** None.

#### `storeProfile()`
- **Description:** Stores or updates the patient's profile information. It validates the incoming request data and then uses `updateOrCreate` to either create a new patient record or update an existing one based on the `user_id`.
- **Logic:**
    - Gets the authenticated user.
    - Validates various patient-related fields like name, address, sex, civil status, date of birth, mobile number, blood type, and medical history.
    - Uses `Patient::updateOrCreate()` to save the validated data.
    - Redirects to the patient dashboard with a success message.
- **Used?** Yes, typically submitted from the profile form.
- **Problems:** None.
- **Suggestions:** None.

#### `dashboard()`
- **Description:** Displays the patient's dashboard, showing upcoming appointments and recent patient notes. It checks if the patient has completed their profile and redirects them if not.
- **Logic:**
    - Gets the authenticated user and their patient record.
    - If no patient record exists, it redirects to the profile completion form with an error.
    - Fetches upcoming appointments for the patient (status 'pending' or 'rescheduled' and `appointment_datetime` in the future).
    - Fetches the 5 most recent 'shared' patient notes.
    - Passes this data to the `patient.dashboard` view.
- **Used?** Yes, this is the main entry point for a patient after login.
- **Problems:**
    - Lines [`110`](app/Http/Controllers/PatientController.php:110) `->where('appointment_datetime', '>=', now())` and [`111`](app/Http/Controllers/PatientController.php:111) `->orderBy('appointment_datetime')` use `appointment_datetime` which is correct, but the comments next to them (`// Use 'appointment_date'` and `// Order by 'appointment_date'`) are inconsistent and potentially misleading.
- **Suggestions:**
    - Update comments on lines 110-111 to accurately reflect the column being used (`appointment_datetime`).

#### `chat()`
- **Description:** Displays the chat interface for patients.
- **Logic:** Simply returns the `patient.chat-interface` view.
- **Used?** Yes, linked to a patient chat feature.
- **Problems:** None.
- **Suggestions:** None.

#### `aiConsult()`
- **Description:** Displays the AI consultation chat interface for patients.
- **Logic:** Simply returns the `patient.ai-consult-chat` view.
- **Used?** Yes, linked to an AI consultation feature.
- **Problems:** None.
- **Suggestions:** None.

#### `showDoctorSelectionForm()`
- **Description:** Displays a form for patients to select a doctor. If the patient already has an attending physician, it redirects them to the appointment type selection for that doctor. Otherwise, it lists available doctors, optionally filtered by specialization or search query.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Retrieves unique specializations from `Doctor` profiles.
    - Checks for an existing `attendingPhysician`. If found, redirects to `patient.select-appointment-type` with the attending doctor's ID.
    - If no attending physician, it builds a query to fetch `Doctor` profiles, applying filters for `specialization` and `search` (first name, last name, or specialization) if present in the request.
    - Passes the filtered doctors and specializations to the `patient.select-doctor` view.
- **Used?** Yes, for initial doctor selection.
- **Problems:**
    - Line [`157`](app/Http/Controllers/PatientController.php:157) `Doctor::whereNotNull('id');` is redundant. `Doctor::query()` or `Doctor::` is sufficient to start a query builder.
    - The search logic on lines [`165`](app/Http/Controllers/PatientController.php:165) and [`166`](app/Http/Controllers/PatientController.php:166) uses `ILIKE`, which is PostgreSQL specific. If the application needs to support other databases (e.g., MySQL), this might cause issues.
    - The `->orWhere()` clauses in the search filter might lead to unexpected results if not grouped properly, especially if combined with other `where` clauses later. For example, `whereA()->orWhereB()->whereC()` is interpreted as `(A OR B) AND C`, not `A OR B OR C`.
- **Suggestions:**
    - Replace `Doctor::whereNotNull('id')` with `Doctor::query()` or `Doctor::`.
    - If cross-database compatibility is a concern, consider using `where(DB::raw('LOWER(first_name)'), 'LIKE', '%' . strtolower($request->search) . '%')` or a package that abstracts this.
    - Wrap the `orWhere` conditions for search within a `where` closure to ensure correct logical grouping:
        ```php
        $q = $q->where(function ($query_inner) use ($request) {
            $query_inner->where('first_name', 'ILIKE', $request->search . '%')
                        ->orWhere('last_name', 'ILIKE',  $request->search . '%')
                        ->orWhere('specialization', 'ILIKE', $request->search . '%');
        });
        ```

#### `storeAttendingPhysician()`
- **Description:** Assigns an attending physician to the authenticated patient. It validates the doctor ID and then creates or updates the `AttendingPhysician` record for the patient.
- **Logic:**
    - Gets the authenticated user and patient. Returns an error if no patient profile is found.
    - Validates the `doctor_id` to ensure it's a UUID and exists in `doctor_profiles`.
    - Uses `AttendingPhysician::updateOrCreate()` to set the doctor as the patient's attending physician.
    - Redirects to the appointment type selection form for the newly assigned doctor with a success message.
- **Used?** Yes, after a patient selects an attending physician.
- **Problems:** None.
- **Suggestions:** None.

#### `showAppointmentTypeForm()`
- **Description:** Displays the form for selecting an appointment type (online/clinic) for a specific doctor. It also ensures that the patient is either booking with their assigned attending physician or has no attending physician set yet.
- **Logic:**
    - Gets the authenticated user and patient. Redirects if patient profile is incomplete.
    - Finds the specified doctor.
    - Checks if the patient has an `attendingPhysician` and if it matches the `doctor_id` provided. If not, it redirects with an error.
    - Fetches the doctor's availability that includes a `clinic_id` (implying face-to-face appointments) to determine available clinics.
    - Populates a unique list of clinics associated with the doctor's availability.
    - Passes the doctor and unique clinics to the `patient.select-appointment-type` view.
- **Used?** Yes, as a step in the appointment booking process.
- **Problems:** None.
- **Suggestions:**
    - The logic to gather unique clinics could be simplified using Laravel's collection methods:
        ```php
        $clinics = $doctor->doctorAvailability()
                          ->whereNotNull('clinic_id')
                          ->with('clinic') // Eager load clinic to avoid N+1
                          ->get()
                          ->pluck('clinic')
                          ->unique('id')
                          ->values(); // Reset keys
        ```

#### `showDateTimeSelectionForm()`
- **Description:** Displays a form for patients to select an available date and time for an appointment with a specific doctor, based on appointment type and subtype. It generates a list of 30-minute slots for the next 30 days.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Validates `doctor_id`, `appointment_type`, `appointment_subtype`, and `clinic_id` (if type is 'clinic').
    - Finds the doctor and clinic (if applicable).
    - Fetches the doctor's active availability based on doctor ID, clinic (if provided), and `availability_type` (consultation/follow-up).
    - Groups availabilities by day of the week.
    - Generates 30-minute slots for the next 30 days, starting from tomorrow.
    - For each potential slot, it checks if an existing appointment conflicts.
    - Stores available slots grouped by date.
    - Passes relevant data to the `patient.select-date-time` view.
- **Used?** Yes, as a step in the appointment booking process.
- **Problems:**
    - The `whereJsonContains('availability_type', $appointmentSubtype)` implies that `availability_type` is stored as a JSON array (or similar) in the database. This is a good approach for flexibility.
    - The slot generation logic, while functional, could be resource-intensive if doctor availabilities are very granular or if many days are checked. For 30 days and 30-minute slots, it's likely fine, but could be optimized for larger ranges.
    - The check for existing appointments is done inside the slot generation loop. For a large number of doctors/slots, this could lead to many database queries (N+1 problem if not optimized).
- **Suggestions:**
    - Ensure `availability_type` is properly cast as `array` or `json` in the `DoctorAvailability` model to allow `whereJsonContains` to work correctly.
    - For performance, consider fetching all existing appointments for the doctor within the 30-day range *before* the loop, and then checking against this collection in memory, rather than querying the database for each slot.
        ```php
        // Before the loop:
        $existingAppointments = Appointment::where('doctor_id', $doctor->id)
                                          ->whereBetween('appointment_datetime', [$tomorrow->startOfDay(), $tomorrow->copy()->addDays(29)->endOfDay()])
                                          ->get()
                                          ->pluck('appointment_datetime')
                                          ->map(fn($dt) => Carbon::parse($dt)->format('Y-m-d H:i'))
                                          ->toArray();

        // Inside the loop:
        // ...
        $slotDateTimeFormatted = $slotDateTime->format('Y-m-d H:i');
        if (!in_array($slotDateTimeFormatted, $existingAppointments)) {
            $slots[$date->toDateString()][] = $slot;
        }
        // ...
        ```

#### `storeAppointment()`
- **Description:** Stores a new appointment for the patient. It validates the appointment details, checks for doctor availability and conflicting appointments, and for online appointments, integrates with Google Calendar to generate a Google Meet link.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Validates `doctor_id`, `appointment_type`, `appointment_subtype`, `clinic_id` (conditionally required), `appointment_datetime`, and `chief_complaint`.
    - Retrieves the doctor and re-checks the `attendingPhysician` relationship as a security measure.
    - Validates the requested date/time slot against `DoctorAvailability` and existing appointments.
    - **Google Meet Integration:**
        - If `appointment_type` is 'online', it attempts to create a Google Calendar event with a Google Meet conference.
        - It handles Google API client initialization, token retrieval (`patient->google_access_token`, `patient->google_refresh_token`), and token refreshing.
        - Creates an `Event` object, adds attendees (patient and doctor), and requests a `hangoutsMeet` conference.
        - Extracts the `meetLink` from the created event.
        - Includes comprehensive error handling and logging for Google Meet generation failures.
    - Creates the `Appointment` record with all validated data, including the generated `meet_link` if applicable.
    - Redirects to an appointment confirmation page with a success message.
- **Used?** Yes, this is the final step in booking an appointment.
- **Problems:**
    - **Google API Configuration:** The comments clearly state, "YOU NEED TO REPLACE THIS WITH YOUR ACTUAL GOOGLE API CLIENT CONFIGURATION". If not properly configured with correct credentials and scopes, this functionality will fail. This is not a code logic problem but an environmental setup problem.
    - **Token Refresh Logic:** The logic to refresh the access token on lines [`426-438`](app/Http/Controllers/PatientController.php:426-438) checks `if (isset($newAccessToken['refresh_token']))`. While technically correct that a refresh token might not always be returned, Google's OAuth 2.0 flow generally provides a new refresh token only on the *first* authorization or if the user explicitly re-grants offline access. Subsequent refreshes typically only return a new access token. Constantly saving the `refresh_token` if it's not actually new could overwrite a persistent refresh token with a null or empty value if Google doesn't send it, leading to issues. It's usually better to only update the `refresh_token` if it's explicitly present and different.
    - **Time Zone Handling:** `Carbon::parse($validatedData['appointment_datetime'])->toIso8601String()` and `config('app.timezone')` are used for Google Calendar events. It's crucial that `config('app.timezone')` accurately reflects the *intended* timezone for appointments, and that `appointment_datetime` is either already in this timezone or is correctly converted before being passed to `Carbon::parse()` if it's from user input in a different timezone. Inconsistent timezone handling can lead to off-by-hour errors.
    - **Doctor email:** The code assumes that `doctor->user->email` exists. This should be ensured through model relationships and data integrity.
- **Suggestions:**
    - **Emphasize proper Google API setup:** Add a clear comment about the necessity of correctly configuring `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, and the JSON key file path (if using service accounts) in the `.env` file or `config/services.php`.
    - **Refine token refresh logic:** Only update the `refresh_token` field if `isset($newAccessToken['refresh_token'])` and it's actually different from the current one, or ensure that `refresh_token` is handled robustly based on Google's OAuth documentation for `offline` access. For example:
        ```php
        if ($client->isAccessTokenExpired()) {
            if ($refreshToken) {
                $client->fetchAccessTokenWithRefreshToken($refreshToken);
                $newAccessToken = $client->getAccessToken();
                $patient->google_access_token = $newAccessToken['access_token'];
                if (isset($newAccessToken['refresh_token']) && $newAccessToken['refresh_token'] !== $refreshToken) {
                    $patient->google_refresh_token = $newAccessToken['refresh_token'];
                }
                $patient->save();
            } // ... rest of logic
        }
        ```
    - **Timezone best practices:** Explicitly set the timezone for `Carbon::parse()` if the input `appointment_datetime` comes from a form where the user's local timezone might differ from `app.timezone`. E.g., `Carbon::parse($validatedData['appointment_datetime'], $patient->timezone ?? config('app.timezone'))`.
    - Ensure `Doctor` model has a clear relationship to `User` and `User` has an `email` field.

#### `showAppointmentConfirmation()`
- **Description:** Displays the confirmation page for a successfully booked appointment. It ensures that the authenticated patient can only view their own appointments.
- **Logic:**
    - Gets authenticated user and patient.
    - Finds the `Appointment` by ID.
    - Checks if the appointment's `patient_id` matches the authenticated patient's ID. Aborts with a 403 error if not.
    - Passes the `appointment` object to the `patient.confirm-appointment` view.
- **Used?** Yes, after successful appointment booking.
- **Problems:** None.
- **Suggestions:** None.

#### `showAttendingPhysicianDetails()`
- **Description:** Displays details of the patient's attending physician and their associated clinics. It redirects the patient to select a physician if one isn't assigned.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Retrieves the patient's `attendingPhysician`. Redirects to doctor selection if no attending physician is set.
    - Iterates through the attending physician's `doctorAvailability` to collect unique clinics.
    - Passes the `attendingPhysician` and unique `doctor_clinics` to the `patient.attending-physician-details` view.
- **Used?** Yes, to view attending physician's information.
- **Problems:**
    - The logic for collecting unique clinics is similar to `showAppointmentTypeForm` and can be refactored for clarity and potentially efficiency using collection methods.
- **Suggestions:**
    - Use collection methods for `doctor_clinics`:
        ```php
        $doctor_clinics = $attendingPhysician->doctor->doctorAvailability
                                        ->whereNotNull('clinic_id')
                                        ->with('clinic') // Eager load clinic to avoid N+1
                                        ->pluck('clinic')
                                        ->unique('id')
                                        ->values();
        ```
    - Consider eager loading `attendingPhysician.doctor.doctorAvailability.clinic` to avoid N+1 queries if these relationships are always needed.

#### `getPatientDetailsForApi()`
- **Description:** Returns a subset of the authenticated patient's non-sensitive data as a JSON response, intended for API consumption (e.g., by an AI assistant).
- **Logic:**
    - Gets authenticated user and patient. Returns a 404 JSON error if no patient profile.
    - Returns a JSON response containing specific patient fields like name, dob, sex, blood type, medical conditions, and allergies.
- **Used?** Yes, likely by an AI integration.
- **Problems:** None.
- **Suggestions:** None.

#### `getDoctorsForApi()`
- **Description:** Returns a list of all doctors with their non-sensitive details as a JSON response, intended for API consumption (e.g., by an AI assistant for recommendations).
- **Logic:**
    - Fetches all `Doctor` records.
    - Maps over the collection to return a filtered array of non-sensitive doctor details (id, name, specialization, experience).
    - Returns this as a JSON response.
- **Used?** Yes, likely by an AI integration.
- **Problems:** None.
- **Suggestions:** None.

#### `getMedicalRecords()`
- **Description:** Retrieves and displays all medical records (notes, prescriptions, test requests, lab results) for the authenticated patient, categorized by type and sorted by creation date.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Fetches `patientNotes`, `patientPrescriptions`, `patientTestRequests`, and `labResults` for the patient.
    - Maps each collection to add a `record_type` attribute.
    - Concatenates all records into a single collection and sorts them by `created_at` in descending order.
    - Passes the `allRecords` and `patient` to the `patient.medical-records.view` view.
- **Used?** Yes, to display a patient's medical history.
- **Problems:**
    - The repeated `->map(function($record) { $record->record_type = 'some-type'; return $record; })` pattern is functional but could be slightly more concise if the models themselves had an accessor or if the `record_type` was added more dynamically. This is a minor stylistic point.
- **Suggestions:**
    - Consider using a single `with` statement for eager loading across all relationships if possible, but the current approach of separate queries is fine if the relationships are distinct.

#### `downloadMedicalRecords()`
- **Description:** Generates and allows the patient to download a PDF of their medical records, filtered by type (all records, doctor notes, prescriptions, lab requests, lab results).
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Determines the `type` of records to download from the request query parameter, defaulting to 'all-records'.
    - Uses a `switch` statement to fetch the appropriate records based on the `type`.
    - Passes the patient information and fetched records to a specific PDF template view.
    - Uses the `PDF` facade (presumably Barryvdh/Laravel-DomPDF) to load the view and return it as a downloadable PDF.
- **Used?** Yes, for downloading medical records.
- **Problems:**
    - The `PDF` facade is used. This implies an external package is installed and configured. Assuming it is, the logic is sound.
    - The `patient->full_name` accessor is used, which is assumed to exist on the `Patient` model.
- **Suggestions:** None, assuming PDF generation works as expected with the configured package.

#### `uploadLabResult()`
- **Description:** Allows a patient to upload a lab result file associated with a specific `PatientTestRequest`. It stores the file and creates a `LabResult` record.
- **Logic:**
    - Gets authenticated user and patient.
    - Checks for authorization: ensures the `patientTestRequest` belongs to the authenticated patient.
    - Validates the uploaded file (required, mimes: pdf, jpeg, png, jpg, max 2MB) and optional notes.
    - Stores the file in `storage/app/public/lab_results`.
    - Creates a new `LabResult` record, linking it to the patient and the `patientTestRequest`, storing the file URL and other metadata.
    - Redirects back with a success message.
- **Used?** Yes, for patients to upload lab results.
- **Problems:**
    - The `result_file_url` is stored using `Storage::url($filePath)`. Ensure that your `FILESYSTEM_DRIVER` in `.env` and `config/filesystems.php` is correctly configured for public access and that `php artisan storage:link` has been run.
    - The `result_data` field is stored as JSON, which is good for flexible metadata.
- **Suggestions:** None.

#### `showPlans()`
- **Description:** Displays available subscription plans to the patient, along with their current subscription status and any pending payments for plans.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Retrieves the patient's `currentSubscription` (assuming a one-to-one or latest subscription relationship).
    - Fetches all available `Plan` records.
    - Checks for any `PENDING` payments related to `MEMBERSHIP` for the authenticated user.
    - Passes `plans`, `currentSubscription`, and `pendingPlanPayment` to the `patient.plans.index` view.
- **Used?** Yes, for patients to view and select subscription plans.
- **Problems:**
    - `currentSubscription = $patient->subscriptions()->first();` This will fetch *any* subscription, not necessarily the *active* or *current* one. If a patient can have multiple subscriptions (e.g., past, inactive, pending), this might not retrieve the truly "current" one. A more robust query would filter by status (e.g., `->where('status', 'ACTIVE')->latest('start_date')->first()`).
- **Suggestions:**
    - Refine the `currentSubscription` query to specifically look for active subscriptions, possibly ordering by `start_date` to get the most recent active one if multiple are theoretically possible.
        ```php
        $currentSubscription = $patient->subscriptions()->where('status', 'ACTIVE')->latest('start_date')->first();
        ```

#### `showCheckoutForm()`
- **Description:** Displays the checkout form for a specific plan.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Passes the selected `plan` to the `patient.plans.checkout` view.
- **Used?** Yes, as a step in the subscription process.
- **Problems:** None.
- **Suggestions:** None.

#### `processPlanPayment()`
- **Description:** Processes a patient's plan payment. It creates a `PENDING` payment record and redirects the patient to a payment details page, awaiting admin approval.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Validates the `payment_method` (currently a dummy string).
    - Creates a `Payment` record with `status` 'PENDING', linking it to the user and the selected `plan`. `transaction_id` and `payment_date` are initially null.
    - Redirects to the `patient.payment` route with the created payment and a success message.
- **Used?` Yes, for processing plan payments.`
- **Problems:**
    - This implementation uses a "dummy payment method" and sets the status to `PENDING` awaiting admin approval. This is a very basic payment flow and lacks real payment gateway integration. It also relies on manual admin intervention to activate subscriptions, which is not scalable for a production system.
- **Suggestions:**
    - Integrate with a real payment gateway (e.g., Stripe, PayPal) to handle payments securely and automatically.
    - Upon successful payment, automatically update the payment status to `PAID` and activate the subscription (or trigger a background job to do so), rather than relying on manual admin approval. This would significantly improve user experience and system automation.

#### `showPaymentDetails()`
- **Description:** Displays details for a specific pending payment. It ensures the payment belongs to the authenticated user and is indeed a pending membership payment.
- **Logic:**
    - Gets authenticated user and patient. Redirects if patient profile is incomplete.
    - Validates that the `payment` belongs to the current user, is `PENDING`, and `payable_type` is 'MEMBERSHIP'. Redirects with an error if conditions aren't met.
    - Finds the associated `Plan`. Redirects with an error if the plan is not found.
    - Passes the `payment` and `plan` to the `patient.plans.pending-payment` view.
- **Used?** Yes, to view details of a pending payment.
- **Problems:** None.
- **Suggestions:** None.

---

## `app/Http/Controllers/DoctorController.php`

This controller manages doctor-specific functionalities, including dashboard views, profile management, availability settings, clinic management, appointment handling, patient management, SOAP notes, and case sharing.

#### `dashboard()`
- **Description:** Displays the doctor's dashboard, including upcoming appointments, total unique patients, and pending shared cases. It redirects the doctor to complete their profile if it's missing.
- **Logic:**
    - Gets the authenticated user's doctor profile. Redirects to doctor creation if profile is missing.
    - Fetches upcoming appointments for the doctor, eager loaded with patient and clinic details.
    - Counts total unique patients by checking appointments and attending physician relationships.
    - Counts pending shared cases where the doctor is the receiver.
    - Returns the `doctor.dashboard` view with this data.
- **Used?** Yes, main dashboard for doctors.
- **Problems:** None.
- **Suggestions:** None.

#### `createDoctor()`
- **Description:** Displays the form to create a doctor profile if one doesn't exist. If a profile already exists, it redirects to the doctor dashboard.
- **Logic:**
    - Checks if the authenticated user already has a doctor profile.
    - If not, displays the `doctor.create-doctor` view.
    - If yes, redirects to the dashboard with an error.
- **Used?` Yes, for initial doctor profile creation.`
- **Problems:**
    - The `dd("Unauthorized")` on line [`88`](app/Http/Controllers/DoctorController.php:88) in `editDoctor` is likely a debugging leftover and should be replaced with a proper error handling mechanism (e.g., `abort(403)` or a redirect with an error message).
- **Suggestions:**
    - In `editDoctor`, replace `dd("Unauthorized")` with `abort(403, 'Unauthorized action.');` for production.

#### `editDoctor()`
- **Description:** Displays the form to edit an existing doctor profile.
- **Logic:**
    - Gets the authenticated user's doctor profile.
    - If no profile is found, it currently `dd("Unauthorized")`.
    - Otherwise, it displays the `doctor.edit-doctor` view with the doctor data.
- **Used?** Yes, for doctors to edit their profile.
- **Problems:**
    - The `dd("Unauthorized")` on line [`88`](app/Http/Controllers/DoctorController.php:88) is a debugging leftover and should be replaced with a proper error handling mechanism (e.g., `abort(403)` or a redirect with an error message).
- **Suggestions:**
    - Replace `dd("Unauthorized")` with `abort(403, 'Unauthorized action.');`

#### `storeDoctorDetails()`
- **Description:** Stores the details for a new doctor profile. It validates the input and creates a new `Doctor` record, then logs an auditable event.
- **Logic:**
    - Finds the authenticated user.
    - Validates doctor profile fields (name, specialization, licenses, etc.). `prc_license_number` and `ptr_license_number` are validated for uniqueness.
    - Creates a new `Doctor` record.
    - Dispatches an `AuditableEvent`.
    - Redirects to the doctor edit page with a success message.
- **Used?** Yes, after initial doctor profile form submission.
- **Problems:** None.
- **Suggestions:** None.

#### `updateDoctorDetails()`
- **Description:** Updates the details of an existing doctor profile. It validates the input and updates the `Doctor` record, logging an auditable event.
- **Logic:**
    - Finds the authenticated user and their `Doctor` profile.
    - Validates doctor profile fields. `prc_license_number` and `ptr_license_number` uniqueness validation excludes the current doctor's ID.
    - Updates the `Doctor` record with validated data.
    - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for updating doctor profiles.`
- **Problems:** None.
- **Suggestions:** None.

#### `editAvailability()`
- **Description:** Displays the doctor's availability settings, organized by day of the week, and lists active clinics.
- **Logic:**
    - Gets the authenticated doctor's ID.
    - Retrieves `DoctorAvailability` records for the doctor.
    - Organizes availability slots by day of the week using `self::$reverseDayMapping`.
    - Populates any days without slots with empty arrays.
    - Fetches the doctor's overall `online_availability_enabled` status.
    - Fetches all active clinics.
    - Returns the `doctor.availability` view with this data.
- **Used?** Yes, for doctors to manage their availability.
- **Problems:** None.
- **Suggestions:** None.

#### `updateAvailability()`
- **Description:** Updates the doctor's availability settings. It validates the incoming data, updates the doctor's overall availability status, and recreates/updates availability slots.
- **Logic:**
    - Gets the authenticated doctor's ID.
    - Validates the request data, including `status` (overall enabled/disabled), and an array of `availability` slots with `day_of_week`, `start_time`, `end_time`, `type`, `clinic_id`, and `availability_type`.
    - Includes custom validation rules for `day_of_week` and to ensure `clinic_id` is appropriate for `online_consultation` vs. `face_to_face`.
    - Uses a database transaction:
        - Updates the `Doctor` model's `online_availability_enabled` status.
        - Dispatches an `AuditableEvent`.
        - Deletes all existing `DoctorAvailability` records for the doctor.
        - Recreates `DoctorAvailability` records based on the submitted data.
    - Redirects back with a success message.
- **Used?` Yes, for doctors to save changes to their availability.`
- **Problems:**
    - The approach of deleting all existing availability and then re-creating them (`DoctorAvailability::where('doctor_id', $doctor_id)->delete();` followed by `DoctorAvailability::create([...]);`) can be inefficient and lead to unnecessary database operations if only a few slots change. It also means that `id` for existing records is not actually used in the creation loop, making the `availability.*.id` validation rule somewhat moot for existing records, though it's good for new slots submitted.
- **Suggestions:**
    - Consider an "upsert" or "sync" approach for `DoctorAvailability`. Instead of deleting all, identify which slots are new, which are updated, and which are deleted. This can be more performant and preserve existing record IDs if that's important for other relationships or audit trails.

#### `listClinics()`
- **Description:** Displays a list of all clinics.
- **Logic:**
    - Fetches all `Clinic` records.
    - Returns the `doctor.clinic-list` view with the clinics.
- **Used?` Yes, for doctors to view available clinics.`
- **Problems:** None.
- **Suggestions:**
    - If the list of clinics becomes very large, consider adding pagination.

#### `createClinic()`
- **Description:** Displays the form to create a new clinic.
- **Logic:** Returns the `doctor.clinic-create` view.
- **Used?` Yes, for doctors (or admins via doctor UI) to create a new clinic.`
- **Problems:** None.
- **Suggestions:** None.

#### `storeClinic()`
- **Description:** Stores a new clinic record. It validates the input, handles `operating_hours` and `facilities` conversions, creates the `Clinic`, and logs an auditable event.
- **Logic:**
    - Validates clinic fields (name, address, contact, operating hours, facilities, active status, hospital status).
    - Converts `operating_hours` from an array of start/end times to a more structured format, and filters empty `facilities`.
    - Creates a new `Clinic` record.
    - Dispatches an `AuditableEvent`.
    - Redirects to the clinic list with a success message.
- **Used?` Yes, after clinic creation form submission.`
- **Problems:** None.
- **Suggestions:** None.

#### `editClinic()`
- **Description:** Displays the form to edit an existing clinic.
- **Logic:**
    - Finds the `Clinic` by its ID.
    - Returns the `doctor.clinic-edit` view with the clinic data.
- **Used?` Yes, for editing clinic details.`
- **Problems:** None.
- **Suggestions:** None.

#### `updateClinic()`
- **Description:** Updates an existing clinic record. It validates the input, handles `operating_hours` and `facilities` conversions, updates the `Clinic`, and logs an auditable event.
- **Logic:**
    - Validates clinic fields (similar to `storeClinic`).
    - Converts `operating_hours` and `facilities`.
    - Updates the `Clinic` record.
    - Dispatches an `AuditableEvent`.
    - Redirects to the clinic list with a success message.
- **Used?` Yes, after clinic edit form submission.`
- **Problems:** None.
- **Suggestions:** None.

#### `deleteClinic()`
- **Description:** Deletes a clinic record.
- **Logic:**
    - Deletes the `Clinic` record.
    - Dispatches an `AuditableEvent`.
    - Redirects to the clinic list with a success message.
- **Used?` Yes, for deleting clinics.`
- **Problems:**
    - Does not check for dependencies (e.g., appointments, doctor availability linked to this clinic) before deleting. Deleting a clinic that has active appointments or doctors scheduled there could lead to data integrity issues or broken references.
- **Suggestions:**
    - Implement a check for related records (e.g., `DoctorAvailability`, `Appointment`) before allowing deletion. If dependencies exist, either prevent deletion with an error message or soft-delete the clinic, or offer to reassign related records.

#### `listAppointments()`
- **Description:** Displays a list of all appointments for the authenticated doctor.
- **Logic:**
    - Gets the authenticated doctor's ID.
    - Fetches all appointments for the doctor, eager loaded with patient and clinic details, ordered by `appointment_datetime` descending.
    - Returns the `doctor.appointments-list` view with the appointments.
- **Used?` Yes, for doctors to view their appointments.`
- **Problems:** None.
- **Suggestions:**
    - Add filtering and pagination options if the number of appointments can grow large.

#### `viewAppointment()`
- **Description:** Displays the details of a specific appointment. It ensures the appointment belongs to the authenticated doctor.
- **Logic:**
    - Finds the `Appointment` by its ID.
    - Checks if the appointment's `doctor_id` matches the authenticated doctor's ID. Aborts with a 403 error if not.
    - Returns the `doctor.appointment-details` view with the appointment.
- **Used?` Yes, to view individual appointment details.`
- **Problems:** None.
- **Suggestions:** None.

#### `cancelAppointment()`
- **Description:** Cancels a specific appointment. It ensures the appointment belongs to the authenticated doctor and requires a cancellation reason.
- **Logic:**
    - Checks if the appointment belongs to the authenticated doctor. Aborts with a 403 error if not.
    - Validates that a `cancellation_reason` is provided.
    - Updates the appointment's `status` to 'cancelled' and sets the `cancellation_reason`.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON response indicating success.
- **Used?` Yes, for doctors to cancel appointments.`
- **Problems:** None.
- **Suggestions:**
    - Consider notifying the patient of the cancellation (e.g., email, in-app notification).

#### `rescheduleAppointment()`
- **Description:** Reschedules an appointment to a new date and time. It includes validation to prevent conflicts with existing appointments.
- **Logic:**
    - Checks if the appointment belongs to the authenticated doctor. Aborts with a 403 error if not.
    - Validates `new_appointment_datetime` (required, date, not in the past) and `reschedule_reason`.
    - Includes a custom validation rule to check for time conflicts:
        - Calculates the end time of the new slot.
        - Queries for existing appointments for the same doctor that overlap with the new slot, excluding the appointment being rescheduled.
        - If conflicts are found, the validation fails.
    - Updates the appointment's `appointment_datetime` and `status` to 'rescheduled'.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON response indicating success.
- **Used?` Yes, for doctors to reschedule appointments.`
- **Problems:**
    - The `duration_minutes` is taken from the existing appointment. This is generally fine but implies that the duration of a rescheduled appointment cannot be changed. If changing duration is a requirement, it would need to be added to the validation.
    - The conflict check `appointment_datetime + (duration_minutes * interval \'1 minute\')` uses PostgreSQL specific `interval` syntax. This will break if the database is not PostgreSQL.
- **Suggestions:**
    - Use a database-agnostic way to calculate the end time in the conflict check, such as `DATE_ADD(appointment_datetime, INTERVAL duration_minutes MINUTE)` for MySQL or using Carbon directly to check overlaps in PHP if the dataset is small enough to fetch and process in memory.
    - Consider adding an option to change `duration_minutes` during rescheduling if needed.
    - Notify the patient of the rescheduled appointment.

#### `createAppointment()`
- **Description:** Prepares the view for a doctor to create an appointment for a specific patient.
- **Logic:**
    - Fetches all clinics.
    - Returns the `doctor.patient-view` view, passing the selected patient and clinics.
- **Used?` Yes, when a doctor wants to manually create an appointment for a patient.`
- **Problems:** None.
- **Suggestions:** None.

#### `storeAppointment()`
- **Description:** Stores a new appointment created by a doctor for a patient. It handles online appointment creation with Google Meet integration for the doctor.
- **Logic:**
    - Validates appointment details (patient ID, type, clinic ID, datetime, duration, subtype, chief complaint, notes, SOAP note ID).
    - Requires a `soap_note_id` for 'follow-up' subtypes.
    - Creates a new `Appointment` object.
    - **Google Meet Integration (Doctor side):**
        - If `type` is 'online', it attempts to create a Google Calendar event.
        - Similar to the patient's `storeAppointment`, it handles Google API client initialization, token retrieval (`doctor->google_access_token`), and token refreshing for the doctor.
        - Creates an `Event`, adds attendees (patient and doctor), and requests a `hangoutsMeet` conference.
        - Extracts the `meetLink`.
        - Includes error handling and logging for Google Meet generation.
    - Saves the `Appointment`.
    - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for doctors to create new appointments.`
- **Problems:**
    - **Google API Configuration:** Similar to the patient side, correct Google API client configuration is critical.
    - **Token Refresh Logic:** The same potential issue with `refresh_token` handling exists as in `PatientController::storeAppointment`.
    - **Time Zone Handling:** Similar timezone considerations apply for `Carbon::parse()` and `config('app.timezone')`.
- **Suggestions:**
    - Apply the same suggestions for Google API configuration, token refresh logic, and timezone handling as mentioned for `PatientController::storeAppointment`.
    - Ensure `Patient` model has a clear relationship to `User` and `User` has an `email` field.

#### `storeSoapNote()`
- **Description:** Stores a new SOAP (Subjective, Objective, Assessment, Plan) note for a patient. It validates the input, checks doctor's authorization for the patient, processes vital signs and file uploads, and logs an auditable event.
- **Logic:**
    - Validates numerous SOAP note fields, including vital signs and optional `lab_files`.
    - Retrieves the authenticated doctor and the target patient.
    - Authorizes the doctor: checks if the doctor is the patient's attending physician or an accepted receiving doctor for a shared case. Returns 403 if unauthorized.
    - Prepares `vitalSigns` data as a JSON object.
    - Creates a new `App\Consultation` record (which serves as the SOAP note).
    - Dispatches an `AuditableEvent`.
    - **Handles `lab_files` uploads:**
        - Stores each uploaded file in `storage/app/public/lab_results`.
        - Creates a `LabResult` entry for each file, optionally including text results from the form.
        - Creates a `FileAttachment` record to track the uploaded file, linking it to the `LabResult`.
    - Returns a JSON success response.
- **Used?` Yes, for doctors to create new SOAP notes.`
- **Problems:**
    - The `lab_files.*` validation allows `max:256000`, which translates to 256MB. This is a very large file size for web uploads and could lead to performance issues, timeouts, or excessive storage usage. Consider if this size is truly necessary.
    - The `vital_remarks` field is present in the `Consultation` model update but not explicitly validated in the `$validator` for `storeSoapNote`. It is present in `updateSoapNote`'s validator. This might be an oversight.
    - `remarks_note` is used for `notes` field in `LabResult` but is not part of the validator.
- **Suggestions:**
    - Re-evaluate the `max:256000` validation for `lab_files`. A more typical maximum for documents might be 2MB to 10MB. Adjust as per requirements and server capabilities.
    - Add `vital_remarks` to the validator for `storeSoapNote`.
    - Add `remarks_note` to the validator for `storeSoapNote` if it's meant to be a user-provided field.

#### `updateSoapNote()`
- **Description:** Updates an existing SOAP note. It validates the input, checks doctor's authorization, handles vital signs, and manages file additions/deletions.
- **Logic:**
    - Finds the `Consultation` (SOAP note) by ID.
    - Checks if the authenticated doctor is the author of the SOAP note. Returns 403 if not.
    - Validates numerous SOAP note fields, including vital signs, `lab_files` for new uploads, and `deleted_file_ids` for existing files to be removed.
    - Prepares `vitalSigns` data.
    - Updates the `Consultation` record.
    - Dispatches an `AuditableEvent`.
    - **Handles deleted lab files:**
        - Iterates through `deleted_file_ids`.
        - Finds the associated `FileAttachment` and `LabResult`.
        - Deletes the physical file from storage and then the `LabResult` and `FileAttachment` records.
    - **Handles new lab file uploads:**
        - Similar to `storeSoapNote`, it stores files, creates `LabResult` entries, and `FileAttachment` records.
    - Returns a JSON success response or an error message.
- **Used?` Yes, for doctors to edit existing SOAP notes.`
- **Problems:**
    - The `lab_files.*` validation still uses `max:256000` (256MB), which is very large.
    - The `deleted_file_ids.*` validation checks for `exists:lab_results,id|exists:file_attachments,entity_id`. This is good to ensure the IDs are valid.
    - The `file_attachments` related to `App\LabResult` are deleted. This assumes that `FileAttachment` records with `entity_type = 'App\\LabResult'` are solely managed by `LabResult`.
- **Suggestions:**
    - Re-evaluate the `max:256000` validation for `lab_files`.
    - Ensure that the logic for deleting `FileAttachment` and `LabResult` is robust and handles cases where a `LabResult` might have multiple `FileAttachment`s, though the current implementation suggests a 1:1 relationship between a `LabResult` and its file.

#### `viewPatients()`
- **Description:** Displays a list of patients associated with the authenticated doctor, with filtering options for 'my-patients' (attending physician or appointments) or 'shared-cases' (accepted shared cases). It also allows searching by patient name and selects a patient by default if `patient_id` is provided or if there are patients in the list.
- **Logic:**
    - Gets the authenticated doctor.
    - Determines the `filter` ('my-patients' or 'shared-cases').
    - Constructs a query to fetch patients based on the filter:
        - 'my-patients': Patients who have appointments with the doctor or have the doctor as their attending physician.
        - 'shared-cases': Patients for whom the doctor is an accepted receiving doctor in a shared case.
    - Applies a name search filter if `name` is provided.
    - Eager loads several relationships (`attendingPhysician.doctor`, `sharedCases`, `soapNotes`, `patientNotes`, `appointments`).
    - Handles `patient_id` parameter to pre-select a patient, fetching it directly if not found in the filtered list.
    - Fetches active clinics.
    - Passes all relevant data (`patients`, `selectedPatient`, `filter`, `doctor`, `name`, `clinics`, `prefillSubtype`, `prefillSoapNoteId`) to the `doctor.patient-view` view.
- **Used?` Yes, main patient management interface for doctors.`
- **Problems:**
    - The `ILIKE` operator is used for name search, which is PostgreSQL specific.
    - The variable `$patients` is initialized as `collect()` and then reassigned multiple times with query builder instances, and finally `->get()` is called. This might be confusing and could potentially overwrite a previously filtered `$q` if not careful. The logic `patients = $q -> get();` at [`1082`](app/Http/Controllers/DoctorController.php:1082) seems to correctly execute the query builder.
- **Suggestions:**
    - Replace `ILIKE` with database-agnostic `LIKE` and `LOWER()` for cross-database compatibility, if needed.
    - Ensure clarity in `$q` variable usage to avoid confusion with `$patients` which is initially a collection and then a query builder. The current structure works, but could be clearer.

#### `storeSharedCase()`
- **Description:** Creates a new shared case for a patient, allowing a doctor to invite another doctor to collaborate. It includes validation, checks to prevent sharing with oneself, and logs an auditable event.
- **Logic:**
    - Validates `patient_id`, `receiving_doctor_id`, `case_description`, `urgency`, `permissions`, and `expires_at`.
    - Retrieves the sharing doctor (authenticated) and the receiving doctor.
    - Prevents a doctor from sharing a case with themselves.
    - Creates a new `SharedCase` record with 'PENDING' status.
    - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for doctors to share patient cases.`
- **Problems:**
    - The `permissions` field is used twice in the `SharedCase::create` call (`'shared_data' => $request->input('permissions')` and `'permissions' => $request->input('permissions')`). This seems redundant or potentially an error if `shared_data` and `permissions` are intended to be different fields.
- **Suggestions:**
    - Clarify or correct the usage of `shared_data` and `permissions` fields during `SharedCase` creation. If they are the same, one should be removed.

#### `listSharedCases()`
- **Description:** Displays a list of shared cases involving the authenticated doctor (as sharing or receiving doctor), with various filtering options (PENDING, ACCEPTED, DECLINED, REVOKED, CANCELLED, ALL). It also includes urgency-based sorting and parses case descriptions for display.
- **Logic:**
    - Gets the authenticated doctor.
    - Defines a PostgreSQL-specific `urgencyCaseStatement` for custom sorting.
    - Creates a `baseQueryReceived` that fetches shared cases where the doctor is either the receiving or sharing doctor, eager loads patient and other doctor details, and selects the urgency order.
    - Calculates counts for different shared case statuses (pending, accepted, etc.) for display in the view header.
    - Applies the requested `filter` to the `baseQueryReceived`, sorts by `urgency_order` and `created_at`.
    - Iterates through the fetched `sharedCases`:
        - Calculates `patient_age`.
        - Parses `case_description` using regex to extract `symptoms`, `duration`, and `tests_done`, and assigns `urgency`.
    - Returns the `doctor.shared-cases.shared-cases` view with all the data.
- **Used?` Yes, for doctors to manage shared cases.`
- **Problems:**
    - The `urgencyCaseStatement` uses PostgreSQL-specific `CASE WHEN` and `DB::raw()` which makes the query non-portable to other database systems (e.g., MySQL, SQLite).
    - The parsing of `case_description` using `preg_match` is a fallback for backward compatibility. Ideally, `symptoms`, `duration`, and `tests_done` should be stored in structured fields to avoid string parsing.
- **Suggestions:**
    - For database portability, consider handling urgency ordering in PHP after fetching the results if the dataset is not excessively large, or implement database-specific solutions for different database drivers.
    - Migrate `symptoms`, `duration`, and `tests_done` to dedicated fields in the `SharedCase` model and update data entry to use these structured fields directly.

#### `acceptSharedCaseInvitation()`
- **Description:** Allows a receiving doctor to accept a pending shared case invitation.
- **Logic:**
    - Ensures the shared case is `PENDING` and the authenticated doctor is the `receiving_doctor_id`. Aborts with a 403 error if not.
    - Updates the `SharedCase` status to 'ACCEPTED'.
    - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for doctors to accept shared cases.`
- **Problems:** None.
- **Suggestions:** None.

#### `declineSharedCaseInvitation()`
- **Description:** Allows a receiving doctor to decline a pending shared case invitation.
- **Logic:**
    - Ensures the shared case is `PENDING` and the authenticated doctor is the `receiving_doctor_id`. Aborts with a 403 error if not.
    - Updates the `SharedCase` status to 'DECLINED'.
    - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for doctors to decline shared cases.`
- **Problems:** None.
- **Suggestions:** None.

#### `cancelSharedCaseInvitation()`
- **Description:** Allows the sharing doctor to cancel a pending shared case invitation.
- **Logic:**
    - Ensures the shared case is `PENDING` and the authenticated doctor is the `sharing_doctor_id`. Aborts with a 403 error if not.
    - Updates the `SharedCase` status to 'CANCELLED'.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for doctors to cancel pending shared cases they initiated.`
- **Problems:** None.
- **Suggestions:** None.

#### `searchDoctors()`
- **Description:** Searches for eligible doctors to share a case with, excluding the current doctor, the patient's attending physician, and doctors already involved in pending/accepted shared cases for that patient.
- **Logic:**
    - Retrieves `query` (search term) and `patientId` from the request, and the `currentDoctorId`.
    - Queries `Doctor` profiles, searching `first_name`, `last_name`, or `email` (using `ILIKE`, PostgreSQL specific).
    - Excludes the `currentDoctorId`.
    - Excludes doctors who are already the patient's attending physician (`whereDoesntHave('attendingPhysicians')`).
    - Excludes doctors who are already receiving or have accepted a shared case for the patient (`whereDoesntHave('sharedCasesAsReceiver')`).
    - Limits results to 10.
    - Returns a JSON response of eligible doctors.
- **Used?` Yes, for doctors to find other doctors to collaborate with.`
- **Problems:**
    - Uses `ILIKE` (PostgreSQL specific) for search.
- **Suggestions:**
    - Replace `ILIKE` with database-agnostic `LIKE` and `LOWER()` for cross-database compatibility, if needed.

#### `removeSharedCase()`
- **Description:** Revokes an accepted shared case, effectively removing the receiving doctor's access to the patient's shared records. Only the sharing doctor can perform this action.
- **Logic:**
    - Ensures the shared case is 'ACCEPTED' and the authenticated doctor is the `sharing_doctor_id`. Returns a 403 JSON error if not.
    - Updates the `SharedCase` status to 'REVOKED'.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for the primary doctor to end a shared case.`
- **Problems:** None.
- **Suggestions:**
    - Consider notifying the receiving doctor when their access is revoked.

#### `removeDeclinedSharedCase()`
- **Description:** Deletes a declined or cancelled shared case invitation from the list. Only the sharing doctor can perform this action.
- **Logic:**
    - Ensures the shared case is 'DECLINED' or 'CANCELLED' and the authenticated doctor is the `sharing_doctor_id`. Returns a 403 JSON error if not.
    - Deletes the `SharedCase` record.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for the sharing doctor to clean up old invitations.`
- **Problems:**
    - The authorization check `($sharedCase->status !== 'DECLINED' || $sharedCase->status !== 'CANCELLED')` is logically flawed. A status cannot be *both* 'DECLINED' and *not* 'CANCELLED' at the same time, or *both* 'CANCELLED' and *not* 'DECLINED'. This condition will always be true, making the `||` check ineffective. It should be `($sharedCase->status === 'DECLINED' || $sharedCase->status === 'CANCELLED')`.
- **Suggestions:**
    - Correct the authorization logic on line [`1401`](app/Http/Controllers/DoctorController.php:1401) to:
        ```php
        if ($sharedCase->sharing_doctor_id !== Auth::user()->doctor->id || !($sharedCase->status === 'DECLINED' || $sharedCase->status === 'CANCELLED')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action or invalid shared case status.'], 403);
        }
        ```

#### `getPatientAge()`
- **Description:** A private helper function to calculate a patient's age based on their birthdate.
- **Logic:**
    - Takes a birthdate string.
    - Returns `null` if the birthdate is empty.
    - Uses `Carbon::parse($birthdate)->age` to calculate the age.
- **Used?` Yes, internally by `listSharedCases`.`
- **Problems:** None.
- **Suggestions:** None.

#### `createSoapNote()`
- **Description:** Prepares the view for a doctor to create a new SOAP note for a specific patient.
- **Logic:**
    - Finds the `Patient` and the authenticated `Doctor`.
    - Currently uses `dd("Unauthorized")` or `dd("Patient not found")` for errors.
    - Returns the `doctor.components.soap-note-create` view.
- **Used?` Yes, to initiate SOAP note creation.`
- **Problems:**
    - The `dd()` calls for unauthorized access or patient not found are debugging leftovers and should be replaced with proper error handling (e.g., `abort(403)` or redirect with error).
- **Suggestions:**
    - Replace `dd("Unauthorized")` and `dd("Patient not found")` with appropriate responses, such as `abort(403, 'Unauthorized action.')` or `return redirect()->back()->with('error', 'Patient not found.');`.

#### `storePatientPrescription()`
- **Description:** Stores a new patient prescription.
- **Logic:**
    - Validates `patient_id`, `doctor_id`, `soap_note_id`, and `content`.
    - Creates a new `PatientPrescription` record.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for doctors to create prescriptions.`
- **Problems:**
    - While `doctor_id` is passed in the request, the currently authenticated doctor's ID (Auth::user()->doctor->id) should probably be used as the `doctor_id` for creating a prescription to prevent a doctor from creating a prescription on behalf of another doctor.
- **Suggestions:**
    - Set `doctor_id` from `Auth::user()->doctor->id` directly within the controller, instead of relying on the request input, to enforce that the authenticated doctor is the one issuing the prescription.

#### `storePatientTestRequest()`
- **Description:** Stores a new patient test request.
- **Logic:**
    - Validates `patient_id`, `doctor_id`, `soap_note_id`, and `content`.
    - Creates a new `PatientTestRequest` record.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for doctors to create test requests.`
- **Problems:**
    - Similar to `storePatientPrescription`, `doctor_id` from request input should ideally be replaced with `Auth::user()->doctor->id`.
- **Suggestions:**
    - Set `doctor_id` from `Auth::user()->doctor->id` directly within the controller.

#### `editSubscription()`
- **Description:** Displays the form to edit a patient's subscription. This is marked as an admin-only action.
- **Logic:**
    - Fetches all available `Plan` records.
    - Returns the `admin.subscriptions.edit` view with the `subscription` and `plans`.
- **Used?` Not directly by a doctor, but it's part of the `DoctorController` (suggesting admin role access to doctors). This naming is a bit confusing if it's truly admin-only.`
- **Problems:**
    - The function is located in `DoctorController` but marked as "Admin-only action". This can lead to confusion and incorrect authorization assumptions. It should ideally be in `AdminController` or a dedicated `SubscriptionController` if it's only for administration.
- **Suggestions:**
    - Move this function and `updateSubscription` to `AdminController` for better logical separation and to correctly enforce role-based access control.

#### `updateSubscription()`
- **Description:** Updates a patient's subscription details. This is marked as an admin-only action and includes logic to deactivate other active subscriptions if the updated one is set to 'ACTIVE'.
- **Logic:**
    - Validates `status`, `start_date`, `end_date`, and `plan_id`.
    - Uses a database transaction:
        - If the new `status` is 'ACTIVE', it deactivates any other active subscriptions for the same patient.
        - Updates the `Subscription` record.
        - Dispatches an `AuditableEvent`.
    - Redirects to the admin subscriptions list with a success message.
- **Used?` Not directly by a doctor, but likely through an admin interface.`
- **Problems:**
    - Similar to `editSubscription`, its placement in `DoctorController` is confusing for an "admin-only" action.
    - The logic to prevent multiple active subscriptions (`if ($exists) { DB::rollback(); return response()->json(['message' => 'Patient already has an active subscription.'], 400); }`) is problematic in an `update` function. If the goal is to *change* the active subscription, the current logic will fail if there's already an active one, even if the intention is to replace it. The preceding `update(['status' => 'INACTIVE'])` already handles deactivating others. This `if ($exists)` check seems redundant and potentially restrictive if the goal is to merely ensure only one active subscription *after* the update.
- **Suggestions:**
    - Move this function to `AdminController`.
    - Re-evaluate the `if ($exists)` check within the `DB::transaction`. The `update(['status' => 'INACTIVE'])` handles ensuring only one is active. The `if ($exists)` check might unintentionally prevent legitimate updates if the user is merely trying to change an existing active subscription.

---

## `app/Http/Controllers/AdminController.php`

This controller handles administrative tasks, including user management (listing, creating, updating, deleting users, patients, and doctors), appointment oversight, subscription management, payment processing, and audit log viewing.

#### `listUsers()`
- **Description:** Displays a paginated list of users, with filtering options by role, username (email), and active status.
- **Logic:**
    - Initializes a `User` query.
    - Applies filters based on `role`, `username` (using `ILIKE`), and `is_active` status from the request.
    - Paginates the results (10 items per page).
    - Retrieves unique roles for filter dropdown.
    - Returns the `admin.admin-user-management` view with users and roles.
- **Used?` Yes, for admin user management.`
- **Problems:**
    - Uses `ILIKE` (PostgreSQL specific) for username search.
- **Suggestions:**
    - Replace `ILIKE` with database-agnostic `LIKE` and `LOWER()` for cross-database compatibility, if needed.

#### `createUser()`
- **Description:** Displays the form to create a new user.
- **Logic:** Returns the `admin.create-user` view.
- **Used?` Yes, for admin to create new user accounts.`
- **Problems:** None.
- **Suggestions:** None.

#### `storeUser()`
- **Description:** Stores a new user account. It validates the input (including password confirmation), creates the `User` record, and logs an auditable event. If the user role is 'PATIENT' or 'DOCTOR', it redirects to fill in their specific details.
- **Logic:**
    - Validates user fields (email, password, role, active status).
    - Creates a new `User` record with hashed password.
    - Dispatches an `AuditableEvent`.
    - If the role is 'PATIENT' or 'DOCTOR', redirects to the `admin.users.edit` route for profile completion.
    - Otherwise, redirects back with a success message.
- **Used?` Yes, for admin to create new user accounts.`
- **Problems:** None.
- **Suggestions:** None.

#### `editUser()`
- **Description:** Displays the form to edit an existing user, including associated patient or doctor details if applicable.
- **Logic:**
    - Finds the `User` by ID. Returns 404 if not found.
    - Retrieves associated `Patient` or `Doctor` profile based on the user's role.
    - Logs debugging information about the retrieved profiles.
    - Returns the `admin.edit-user` view with user, patient, and doctor data.
- **Used?` Yes, for admin to edit user accounts.`
- **Problems:**
    - Logging sensitive information like "Patient data - Exists" can be fine for development but might be too verbose or reveal too much in production logs if not properly managed.
- **Suggestions:**
    - Ensure logging levels are configured appropriately for production environments to avoid excessive logging of non-critical information.

#### `updateUser()`
- **Description:** Updates an existing user account. It validates the input, updates user details (email, role, active status, password), and logs an auditable event.
- **Logic:**
    - Finds the `User` by ID. Returns 404 if not found.
    - Validates user fields. Email uniqueness excludes the current user's ID. Password is optional but confirmed if provided.
    - Updates `email`, `role`, and `is_active`. Hashes new password if provided.
    - Saves the `User` record.
    - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for admin to update user accounts.`
- **Problems:** None.
- **Suggestions:** None.

#### `deleteUser()`
- **Description:** Deletes a user account. It uses a database transaction to ensure atomicity and logs an auditable event.
- **Logic:**
    - Finds the `User` by ID. Redirects with an error if not found.
    - Uses a database transaction:
        - Captures old user values for auditing.
        - Deletes the `User` record.
        - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for admin to delete user accounts.`
- **Problems:**
    - Deleting a `User` record in a cascade scenario (e.g., if a patient or doctor profile is linked, or appointments are linked to the user indirectly) can lead to data integrity issues if foreign key constraints are not properly set up for cascading deletes, or if related data needs to be soft-deleted instead. The current method only deletes the `User` record itself. If `patient` or `doctor` records are tied to `user_id` with foreign keys set to `CASCADE ON DELETE`, they would be deleted too, but this isn't explicit in the controller code. If they are not cascaded, this could leave orphaned `patient` or `doctor` records.
- **Suggestions:**
    - Clearly define the cascade behavior or handle dependent record deletion explicitly for `Patient` and `Doctor` profiles when a `User` is deleted, to prevent orphaned data or unexpected data loss.

#### `storePatientDetails()`
- **Description:** Stores patient details associated with a given `user_id`. This is typically used by an admin to create a patient profile for an existing user account.
- **Logic:**
    - Finds the `User` by `user_id`.
    - Validates numerous patient profile fields.
    - Creates a new `Patient` record, linking it to the user.
    - Dispatches an `AuditableEvent`.
    - Redirects to the admin users list with a success message.
    - Includes a `try-catch` block for general exception handling.
- **Used?` Yes, for admin to create patient profiles for users.`
- **Problems:**
    - The validation for `address` has `max:255` which might be too restrictive for long addresses.
    - The `sex` validation `in:Male,Female,Other` is good, but `civilStatus` only allows `Single,Married,Widowed,Separated,Divorced`, which might miss other valid statuses.
- **Suggestions:**
    - Consider increasing `max` length for `address` if longer addresses are expected.
    - Review the `civilStatus` options to ensure they cover all necessary cases.

#### `updatePatientDetails()`
- **Description:** Updates patient details associated with a given `user_id`. This is typically used by an admin to modify an existing patient profile.
- **Logic:**
    - Finds the `User` by `user_id` and the `Patient` linked to that user.
    - Validates numerous patient profile fields (similar to `storePatientDetails`).
    - Updates the `Patient` record.
    - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for admin to update patient profiles.`
- **Problems:**
    - Similar validation concerns for `address` and `civilStatus` as in `storePatientDetails`.
- **Suggestions:**
    - Similar suggestions for `address` and `civilStatus` as in `storePatientDetails`.

#### `listPatients()`
- **Description:** Displays the admin patient management view.
- **Logic:** Simply returns the `admin.admin-patient-management` view. This view presumably handles the actual fetching and display of patients dynamically (e.g., via AJAX).
- **Used?` Yes, for admin to manage patients.`
- **Problems:** None.
- **Suggestions:** None.

#### `viewPatientDetails()`
- **Description:** Returns detailed information about a specific patient, including their user, medical backgrounds, visits, and appointments, as a JSON response.
- **Logic:**
    - Finds the `Patient` by ID, eager loading `user`, `medicalBackgrounds`, `patientVisits`, and `appointments.consultation`.
    - Returns 404 if patient not found.
    - Returns patient details as a JSON response.
- **Used?` Yes, likely for an admin interface to view full patient details.`
- **Problems:** None.
- **Suggestions:** None.

#### `listAllAppointments()`
- **Description:** Displays the admin appointment oversight view.
- **Logic:** Simply returns the `admin.admin-appointment-oversight` view. Similar to `listPatients()`, the actual data is likely loaded dynamically.
- **Used?` Yes, for admin to oversee all appointments.`
- **Problems:** None.
- **Suggestions:** None.

#### `filterAppointments()`
- **Description:** Filters and returns a list of appointments based on various criteria (status, type, doctor, patient, date range) as a JSON response.
- **Logic:**
    - Initializes an `Appointment` query, eager loading `patient.user` and `doctor.user`.
    - Applies filters for `status`, `type`, `doctor_id`, `patient_id`, and a date range (`start_date` and `end_date`).
    - Orders results by `appointment_datetime` descending.
    - Returns the filtered appointments as a JSON response.
- **Used?` Yes, likely by the `admin.admin-appointment-oversight` view.`
- **Problems:** None.
- **Suggestions:**
    - Add pagination for large datasets.

#### `cancelAppointment()`
- **Description:** Cancels an appointment. This is an admin action.
- **Logic:**
    - Finds the `Appointment` by ID. Returns 404 if not found.
    - Sets the appointment `status` to 'cancelled' and saves.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for admin to cancel appointments.`
- **Problems:** None.
- **Suggestions:**
    - Consider adding a `cancellation_reason` field for admin cancellations, similar to the doctor's cancellation function.
    - Notify affected patient and doctor.

#### `rescheduleAppointment()`
- **Description:** Reschedules an appointment to a new date and time. This is an admin action.
- **Logic:**
    - Finds the `Appointment` by ID. Returns 404 if not found.
    - Validates `appointment_datetime` (required, date, after now).
    - Updates the appointment `appointment_datetime` and sets `status` to 'rescheduled'.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for admin to reschedule appointments.`
- **Problems:**
    - Does not include conflict checking with other appointments for the new slot, unlike the doctor's `rescheduleAppointment` function. An admin could accidentally double-book a doctor.
- **Suggestions:**
    - Implement conflict checking for the new appointment slot, similar to the logic in `DoctorController::rescheduleAppointment`.
    - Notify affected patient and doctor.

#### `reassignAppointment()`
- **Description:** Reassigns an appointment to a different doctor. This is an admin action.
- **Logic:**
    - Finds the `Appointment` by ID. Returns 404 if not found.
    - Validates `new_doctor_id` (required, exists in doctors table).
    - Updates the appointment `doctor_id`.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for admin to reassign appointments.`
- **Problems:** None.
- **Suggestions:**
    - Consider adding a reason for reassigning the appointment for auditing purposes.
    - Notify the original doctor, the new doctor, and the patient about the reassignment.

#### `viewConsultationHistory()`
- **Description:** Returns the consultation history for a specific patient, including associated appointment, doctor, prescriptions, and lab requests, as a JSON response.
- **Logic:**
    - Finds the `Patient` by ID. Returns 404 if not found.
    - Fetches `Consultation` records, eager loading `appointment.doctor.user`, `appointment.patient.user`, `prescriptions`, and `labRequests`.
    - Filters consultations to those associated with the specified patient.
    - Orders by `consultation_datetime` descending.
    - Returns the consultations as a JSON response.
- **Used?` Yes, for admin to view a patient's consultation history.`
- **Problems:**
    - The code `->whereHas('appointment', function ($query) use ($id) { $query->where('patient_id', $id); })` is correct, but since `Consultation` likely has a direct `patient_id` or `appointment_id` that links to the patient, this `whereHas` could potentially be simplified depending on the exact model relationships. However, if a `Consultation` is always through an `Appointment`, then it's correct.
- **Suggestions:** None.

#### `listSubscriptions()`
- **Description:** Displays a paginated list of subscriptions, with filtering options by patient name and status.
- **Logic:**
    - Initializes a `Subscription` query, eager loading `patient.user` and `plan`.
    - Applies filters by `patient_name` (searching first/last name with `ILIKE`) and `status`.
    - Paginates results (10 items per page).
    - Defines example `statuses` for the view.
    - Returns the `admin.subscriptions.index` view with subscriptions and statuses.
- **Used?` Yes, for admin to manage subscriptions.`
- **Problems:**
    - Uses `ILIKE` (PostgreSQL specific) for patient name search.
- **Suggestions:**
    - Replace `ILIKE` with database-agnostic `LIKE` and `LOWER()` for cross-database compatibility, if needed.

#### `monitorTransactions()`
- **Description:** Displays the admin transactions monitoring view.
- **Logic:** Simply returns the `admin.admin-transactions` view.
- **Used?` Yes, for admin to monitor transactions.`
- **Problems:** None.
- **Suggestions:** None.

#### `viewAuditLogs()`
- **Description:** Displays a paginated list of audit logs, with filtering options by user email, action, and IP address.
- **Logic:**
    - Initializes an `AuditLog` query, eager loading `user`.
    - Applies filters for `user_email` (using `ILIKE`), `action` (using `ILIKE`), and `ip_address` (using `ILIKE`).
    - Paginates results (10 items per page).
    - Retrieves unique actions for filter dropdown.
    - Returns the `admin.admin-audit-log-viewer` view with audit logs and actions.
- **Used?` Yes, for admin to view system audit logs.`
- **Problems:**
    - Uses `ILIKE` (PostgreSQL specific) for email, action, and IP address search.
- **Suggestions:**
    - Replace `ILIKE` with database-agnostic `LIKE` and `LOWER()` for cross-database compatibility, if needed.

#### `dashboard()`
- **Description:** Displays the admin dashboard with various summary statistics (total users, doctors, patients, appointments, subscriptions, payments, revenue) and recent activity (recent users, upcoming appointments).
- **Logic:**
    - Counts `totalUsers`, `totalDoctors`, `totalPatients`, `totalAppointments`, `pendingAppointments`, `totalSubscriptions`, `activeSubscriptions`, `totalPayments`, and `totalRevenue`.
    - Fetches the 5 most recent `User` records.
    - Fetches the 5 upcoming `Appointment` records, eager loaded with patient and doctor user details.
    - Returns the `admin.admin-dashboard` view with all the calculated statistics and recent activity.
- **Used?` Yes, main dashboard for administrators.`
- **Problems:** None.
- **Suggestions:** None.

#### `editDoctor()`
- **Description:** Updates a doctor's profile details via an admin interface. It validates the input and updates the `Doctor` record, logging an auditable event.
- **Logic:**
    - Finds the `Doctor` by ID. Returns 404 if not found.
    - Validates doctor profile fields. `prc_license_number` and `ptr_license_number` uniqueness validation excludes the current doctor's ID.
    - Updates the `Doctor` record with the request data.
    - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for admin to edit doctor profiles.`
- **Problems:**
    - The `max:255` for various string fields like `specialization`, `certifications`, `affiliated_hospital`, and `training` might be too restrictive depending on the actual length of data expected.
- **Suggestions:**
    - Review `max` lengths for string fields and adjust as necessary based on expected data.

#### `deleteDoctor()`
- **Description:** Deletes a doctor's profile and the associated user account. It uses a database transaction and logs an auditable event.
- **Logic:**
    - Finds the `Doctor` by ID. Returns 404 if not found.
    - Uses a database transaction:
        - Captures old doctor values for auditing.
        - Deletes the `Doctor` record.
        - Deletes the associated `User` record.
        - Dispatches an `AuditableEvent`.
    - Returns a JSON success response.
- **Used?` Yes, for admin to delete doctor profiles.`
- **Problems:**
    - Similar to `deleteUser()`, this function directly deletes the `Doctor` and `User` records. If there are other dependent records (e.g., appointments, availability slots, shared cases) that are not set up for cascading deletes or should be handled differently (e.g., reassigned, soft-deleted), this could lead to data integrity issues.
- **Suggestions:**
    - Implement a more robust deletion strategy for doctors, considering all their linked data. This might involve:
        - Soft-deleting doctor and user records instead of hard-deleting.
        - Reassigning active appointments to another doctor or cancelling them.
        - Archiving or deactivating doctor availability.
        - Handling shared cases where the deleted doctor was involved.

#### `storeDoctorDetails()`
- **Description:** Stores new doctor details associated with a given `user_id`. This is typically used by an admin to create a doctor profile for an existing user account.
- **Logic:**
    - Finds the `User` by `user_id`. Returns an error if not found.
    - Validates numerous doctor profile fields (similar to `storeDoctorDetails` in `DoctorController`).
    - Creates a new `Doctor` record, linking it to the user.
    - Dispatches an `AuditableEvent`.
    - Redirects to the admin users list with a success message.
- **Used?` Yes, for admin to create doctor profiles for users.`
- **Problems:** None.
- **Suggestions:** None.

#### `updateDoctorDetails()`
- **Description:** Updates doctor details associated with a given `user_id`. This is typically used by an admin to modify an existing doctor profile.
- **Logic:**
    - Finds the `User` by `user_id` and the `Doctor` linked to that user.
    - Validates numerous doctor profile fields (similar to `updateDoctorDetails` in `DoctorController`).
    - Updates the `Doctor` record.
    - Dispatches an `AuditableEvent`.
    - Redirects back with a success message.
- **Used?` Yes, for admin to update doctor profiles.`
- **Problems:** None.
- **Suggestions:** None.

#### `viewDoctorPerformanceMetrics()`
- **Description:** Returns performance metrics for a specific doctor (total appointments, completed appointments, total earnings) as a JSON response.
- **Logic:**
    - Finds the `Doctor` by ID. Returns 404 if not found.
    - Counts `totalAppointments`, `completedAppointments` for the doctor.
    - Calculates `totalEarnings` from `Payment` records where the doctor is the payable and status is 'completed'.
    - Returns doctor and metrics as a JSON response.
- **Used?` Yes, for admin to view doctor performance.`
- **Problems:** None.
- **Suggestions:** None.

#### `listPayments()`
- **Description:** Displays a paginated list of payments, with filtering options by patient name and status.
- **Logic:**
    - Initializes a `Payment` query, eager loading `user`.
    - Applies filters for `patient_name` (searching `Patient` `first_name`/`last_name` through a polymorphic relationship, using `ILIKE`) and `status`.
    - Paginates results (10 items per page).
    - Defines example `statuses` for the view.
    - Returns the `admin.payments.index` view with payments and statuses.
- **Used?` Yes, for admin to manage payments.`
- **Problems:**
    - Uses `ILIKE` (PostgreSQL specific) for patient name search.
- **Suggestions:**
    - Replace `ILIKE` with database-agnostic `LIKE` and `LOWER()` for cross-database compatibility, if needed.

#### `updatePaymentStatus()`
- **Description:** Updates the status of a payment. If a payment for a 'MEMBERSHIP' plan is marked as 'PAID', it creates or updates a patient's subscription.
- **Logic:**
    - Validates `status` (PENDING, PAID, FAILED, REFUNDED).
    - Uses a database transaction:
        - Updates the `Payment` status.
        - Dispatches an `AuditableEvent`.
        - **Subscription Logic:** If the payment is 'PAID' and `payable_type` is 'MEMBERSHIP':
            - Retrieves the associated `Plan` and `Patient`.
            - **Problem:** Checks if the patient already has an active subscription (`Subscription::where('patient_id', $patient->id)->where('status', 'ACTIVE')->first();`). If one exists, it rolls back the transaction and returns an error "Patient already has an active subscription." This logic might be intended to prevent a patient from having two *concurrent* active subscriptions, but it also prevents an admin from reactivating a subscription if another (perhaps expired or inactive one) exists. The code then `updateOrCreate` a new subscription. This is inconsistent; if an active one exists, it should probably either update that one or allow a new one to be created but deactivate the old one. The prior `DoctorController::updateSubscription` handles deactivating others.
            - Creates or updates a `Subscription` for the patient.
    - Redirects back with a success message.
- **Used?` Yes, for admin to manage payment statuses.`
- **Problems:**
    - The logic to prevent multiple active subscriptions is flawed. If an active subscription exists, the transaction is rolled back and an error is returned, preventing the current payment from creating a new active subscription. However, if the intent is for a *new* payment to *replace* an existing active subscription or to update its status, this logic is too restrictive. It should rather deactivate existing active ones and then create/update the new one, as done in `DoctorController::updateSubscription`.
- **Suggestions:**
    - Revisit the logic for creating/updating subscriptions upon payment. Ensure it aligns with the desired business rules for concurrent subscriptions. A common pattern is to deactivate all existing active subscriptions for a patient before creating a new active one, or to simply update the existing active subscription's details.

#### `listPlans()`
- **Description:** Displays a list of all subscription plans.
- **Logic:**
    - Fetches all `Plan` records.
    - Returns the `admin.plans.index` view.
- **Used?` Yes, for admin to manage subscription plans.`
- **Problems:** None.
- **Suggestions:** None.

#### `createPlan()`
- **Description:** Displays the form to create a new subscription plan.
- **Logic:** Returns the `admin.plans.create` view.
- **Used?` Yes, for admin to create new subscription plans.`
- **Problems:** None.
- **Suggestions:** None.

#### `storePlan()`
- **Description:** Stores a new subscription plan. It validates the input, creates the `Plan`, and logs an auditable event.
- **Logic:**
    - Validates `name` (unique), `description`, and `price`.
    - Creates a new `App\Plan` record.
    - Dispatches an `AuditableEvent`.
    - Redirects to the admin plans list with a success message.
- **Used?` Yes, for admin to save new plans.`
- **Problems:** None.
- **Suggestions:** None.

#### `editPlan()`
- **Description:** Displays the form to edit an existing subscription plan.
- **Logic:**
    - Finds the `App\Plan` by its ID.
    - Returns the `admin.plans.edit` view.
- **Used?` Yes, for admin to edit subscription plans.`
- **Problems:** None.
- **Suggestions:** None.

#### `updatePlan()`
- **Description:** Updates an existing subscription plan. It validates the input, updates the `Plan`, and logs an auditable event.
- **Logic:**
    - Validates `name` (unique, excluding current plan's ID), `description`, and `price`.
    - Updates the `App\Plan` record.
    - Dispatches an `AuditableEvent`.
    - Redirects to the admin plans list with a success message.
- **Used?` Yes, for admin to update plans.`
- **Problems:** None.
- **Suggestions:** None.

#### `deletePlan()`
- **Description:** Deletes a subscription plan.
- **Logic:**
    - Deletes the `App\Plan` record.
    - Dispatches an `AuditableEvent`.
    - Redirects to the admin plans list with a success message.
- **Used?` Yes, for admin to delete plans.`
- **Problems:**
    - Deleting a plan that is currently associated with active subscriptions could lead to data integrity issues or broken foreign key references.
- **Suggestions:**
    - Implement a check for active subscriptions linked to this plan before allowing deletion. If subscriptions exist, prevent deletion with an error, or offer to reassign subscriptions to another plan, or soft-delete the plan.