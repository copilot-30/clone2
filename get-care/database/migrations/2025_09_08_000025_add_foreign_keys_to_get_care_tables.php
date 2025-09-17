<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGetCareTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
        });

        Schema::table('doctor_availability', function (Blueprint $table) {
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
        });

        Schema::table('doctor_clinics', function (Blueprint $table) {
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
        });

        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('file_attachments', function (Blueprint $table) {
            $table->foreign('uploaded_by_id')->references('id')->on('users')->onDelete('set null');
        });

    

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('patient_notes', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('lab_results', function (Blueprint $table) { 
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

 

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->foreign('soap_note_id')->references('id')->on('soap_notes')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
        });

        Schema::table('shared_cases', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('sharing_doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
            $table->foreign('receiving_doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
        });

        Schema::table('soap_notes', function (Blueprint $table) { 
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

        // Add foreign key for payments to users (assuming user_id refers to users table)
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['clinic_id']);
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
        });

        Schema::table('doctor_availability', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['clinic_id']);
        });

        Schema::table('doctor_clinics', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['clinic_id']);
        });

        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('file_attachments', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by_id']);
        });
 

        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropForeign(['sender_id']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('patient_notes', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });


        Schema::table('lab_results', function (Blueprint $table) { 
            $table->dropForeign(['patient_id']);
        });
 

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['soap_note_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
        });

        Schema::table('shared_cases', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['sharing_doctor_id']);
            $table->dropForeign(['receiving_doctor_id']);
        });

        Schema::table('soap_notes', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
}