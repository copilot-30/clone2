<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_availability', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('doctor_id')->nullable();
            $table->integer('day_of_week');
            $table->text('start_time');
            $table->text('end_time');
            $table->uuid('clinic_id')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('doctor_availability');
    }
}