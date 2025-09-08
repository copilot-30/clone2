<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id')->unique()->nullable();
            $table->text('specialization')->nullable(false);
            $table->integer('years_of_experience')->nullable();
            $table->text('certifications')->nullable();
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('now()'));
            $table->text('first_name')->nullable(false);
            $table->text('middle_name')->nullable();
            $table->text('last_name')->nullable(false);
            $table->text('sex')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('email')->nullable(false);
            $table->text('prc_license_number')->nullable(false);
            $table->text('ptr_license_number')->nullable(false);
            $table->text('affiliated_hospital')->nullable();
            $table->text('training')->nullable();
            $table->foreign('user_id')->references('id')->on('auth.users');
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