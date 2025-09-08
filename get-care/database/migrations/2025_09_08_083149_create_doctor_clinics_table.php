<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('doctor_id')->nullable();
            $table->uuid('clinic_id')->nullable();
            $table->boolean('is_primary')->nullable(false)->default(false);
            $table->json('available_days')->nullable();
            $table->text('start_time')->nullable();
            $table->text('end_time')->nullable();
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('clinic_id')->references('id')->on('clinics');
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
        Schema::dropIfExists('doctor_clinics');
    }
}