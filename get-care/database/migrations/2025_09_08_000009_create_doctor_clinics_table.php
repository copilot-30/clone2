<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_clinics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('doctor_id')->nullable();
            $table->uuid('clinic_id')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->jsonb('available_days')->nullable(); // Changed from ARRAY to jsonb
            $table->text('start_time')->nullable();
            $table->text('end_time')->nullable();
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
        Schema::dropIfExists('doctor_clinics');
    }
}