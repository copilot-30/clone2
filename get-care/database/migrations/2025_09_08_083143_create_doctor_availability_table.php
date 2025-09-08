<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('doctor_id')->nullable();
            $table->integer('day_of_week')->nullable(false);
            $table->text('start_time')->nullable(false);
            $table->text('end_time')->nullable(false);
            $table->uuid('clinic_id')->nullable();
            $table->boolean('is_active')->nullable(false)->default(true);
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles');
            $table->foreign('clinic_id')->references('id')->on('clinics');
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