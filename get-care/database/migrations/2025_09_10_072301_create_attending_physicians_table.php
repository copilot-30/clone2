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
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->unique()->constrained('patients')->onDelete('cascade');
            $table->foreignUuid('doctor_id')->constrained('doctor_profiles')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable(); // Add end_date back for historical tracking as per original migration
            $table->timestamps();
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
