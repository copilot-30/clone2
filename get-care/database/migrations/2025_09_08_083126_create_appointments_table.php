<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('patient_id')->nullable();
            $table->uuid('doctor_id')->nullable();
            $table->uuid('clinic_id')->nullable();
            $table->date('appointment_date')->nullable(false);
            $table->text('appointment_time')->nullable(false);
            $table->integer('duration_minutes')->nullable(false)->default(30);
            $table->text('type')->nullable(false);
            $table->text('status')->nullable(false)->default('PENDING');
            $table->boolean('is_online')->nullable(false)->default(false);
            $table->text('meet_link')->nullable();
            $table->text('chief_complaint')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('clinic_id')->references('id')->on('clinics');
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles');
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}