<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendingPhysiciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attending_physicians', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Using UUID for consistency with Doctor model
            $table->foreignUuid('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignUuid('doctor_id')->constrained('doctor_profiles')->onDelete('cascade');
            $table->date('start_date')->nullable(); 
            $table->timestamps();
            $table->unique(['patient_id', 'doctor_id']); // Ensure unique patient-doctor pair
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attending_physicians');
    }
}
