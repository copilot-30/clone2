# Duplicate AuditableEvent Findings

This document outlines functions found in `app/Http/Controllers/DoctorController.php` and `app/Http/Controllers/AdminController.php` that contain duplicate save, update, or delete operations, which are subsequently being tracked by `AuditableEvent`.

---

### `app/Http/Controllers/DoctorController.php`

**1. `storePatientPrescription()`**
   *   **Lines 1465-1466**:
       ```php
       1465 | PatientPrescription::create($validator->validated());
       1466 | $prescription = PatientPrescription::create($validator->validated());
       ```
   *   **Issue**: The `PatientPrescription::create()` method is called twice in a row. The first call creates a record but assigns the result to nothing, and the second call creates another identical record and assigns it to `$prescription`. This results in **duplicate saving** of the `PatientPrescription`. Only the second `create()` call is necessary.

---

### `app/Http/Controllers/AdminController.php`

**1. `updateUser()`**
   *   **Lines 143-146**:
       ```php
       143 |         $user->save();
       144 | 
       145 |         $oldUserValues = $user->getOriginal();
       146 |         $user->save();
       ```
   *   **Issue**: The `$user->save()` method is called twice. The first `save()` persists the changes to the database. The second `save()` call on line 146 is redundant as the user object is already saved. The `$oldUserValues` are captured between the two `save()` calls, but this is still a duplicate save operation.

**2. `deleteUser()`**
   *   **Lines 168-175**:
       ```php
       168 |             $user_id = $user->id;
       169 |             $email = $user->email;
       170 |             $user->delete();
       171 | 
       172 |             $oldUserValues = $user->toArray(); // Capture values before deletion
       173 |             $user_id = $user->id; // Ensure $user_id is available after $user->toArray()
       174 |             $email = $user->email; // Ensure $email is available after $user->toArray()
       175 |             $user->delete();
       ```
   *   **Issue**: The `$user->delete()` method is called twice. The first call on line 170 deletes the user. The second call on line 175 attempts to delete an already deleted user, which is redundant.

**3. `editDoctor()` (AdminController's version)**
   *   **Lines 597-600**:
       ```php
       597 |         $doctor->update($request->all());
       598 | 
       599 |         $oldDoctorValues = $doctor->toArray(); // Capture old values
       600 |         $doctor->update($request->all());
       ```
   *   **Issue**: The `$doctor->update($request->all());` method is called twice. The first call on line 597 updates the doctor, and the second call on line 600 is redundant.

**4. `deleteDoctor()` (AdminController's version)**
   *   **Lines 625-634**:
       ```php
       625 |             $user_id = $doctor->user_id;
       626 |             $email = $doctor->email;
       627 |             $doctor->delete();
       628 |             User::where('id', $user_id)->delete();
       629 | 
       630 |             $oldDoctorValues = $doctor->toArray(); // Capture values before deletion
       631 |             $user_id = $doctor->user_id; // Ensure $user_id is available
       632 |             $email = $doctor->email; // Ensure $email is available
       633 |             $doctor->delete();
       634 |             User::where('id', $user_id)->delete();
       ```
   *   **Issue**: The `$doctor->delete()` method is called twice (lines 627 and 633), and `User::where('id', $user_id)->delete()` is also called twice (lines 628 and 634). This results in duplicate deletion operations.