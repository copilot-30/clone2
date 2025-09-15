<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_prescriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('soap_note_id')->nullable();
            $table->uuid('patient_id');
            $table->uuid('doctor_id');
            $table->text('content');
            $table->timestamps();

            $table->foreign('soap_note_id')->references('id')->on('soap_notes')->onDelete('set null');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_prescriptions');
    }
}
