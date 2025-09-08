<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('soap_note_id')->nullable();
            $table->uuid('patient_id')->nullable();
            $table->uuid('doctor_id')->nullable();
            $table->text('medication_name')->nullable(false);
            $table->text('dosage')->nullable();
            $table->text('frequency')->nullable();
            $table->text('duration')->nullable();
            $table->text('quantity')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_sent_to_patient')->nullable(false)->default(false);
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles');
            $table->foreign('soap_note_id')->references('id')->on('soap_notes');
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
        Schema::dropIfExists('prescriptions');
    }
}