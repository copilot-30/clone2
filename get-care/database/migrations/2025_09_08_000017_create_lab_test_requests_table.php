<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->primary();
            $table->uuid('soap_note_id')->nullable();
            $table->uuid('patient_id')->nullable();
            $table->uuid('doctor_id')->nullable();
            $table->text('test_name');
            $table->text('test_type')->nullable();
            $table->text('instructions')->nullable();
            $table->text('urgency')->default('ROUTINE');
            $table->text('status')->default('PENDING');
            $table->boolean('is_sent_to_patient')->default(false);
            $table->date('requested_date')->useCurrent();
            $table->timestamps(); // created_at (updated_at is not in base.sql but Laravel adds it by default)
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