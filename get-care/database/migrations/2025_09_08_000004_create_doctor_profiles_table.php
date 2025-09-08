<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique()->nullable();
            $table->text('specialization');
            $table->integer('years_of_experience')->nullable();
            $table->text('certifications')->nullable();
            $table->timestamps(); // created_at, updated_at
            $table->text('first_name');
            $table->text('middle_name')->nullable();
            $table->text('last_name');
            $table->text('sex')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('email');
            $table->text('prc_license_number');
            $table->text('ptr_license_number');
            $table->text('affiliated_hospital')->nullable();
            $table->text('training')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_profiles');
    }
}