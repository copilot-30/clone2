<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLabTestRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_test_requests', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('soap_note_id')->nullable();
            $table->uuid('patient_id')->nullable();
            $table->uuid('doctor_id')->nullable();
            $table->text('test_name')->nullable(false);
            $table->text('test_type')->nullable();
            $table->text('instructions')->nullable();
            $table->text('urgency')->nullable(false)->default('ROUTINE');
            $table->text('status')->nullable(false)->default('PENDING');
            $table->boolean('is_sent_to_patient')->nullable(false)->default(false);
            $table->date('requested_date')->nullable(false)->default(DB::raw('now()'));
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('soap_note_id')->references('id')->on('soap_notes');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_test_requests');
    }
}