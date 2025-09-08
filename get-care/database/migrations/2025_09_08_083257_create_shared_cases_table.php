<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSharedCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_cases', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('patient_id')->nullable();
            $table->uuid('sharing_doctor_id')->nullable();
            $table->uuid('receiving_doctor_id')->nullable();
            $table->text('case_description')->nullable();
            $table->jsonb('shared_data')->nullable();
            $table->jsonb('permissions')->nullable();
            $table->text('status')->nullable(false)->default('PENDING');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('sharing_doctor_id')->references('id')->on('doctor_profiles');
            $table->foreign('receiving_doctor_id')->references('id')->on('doctor_profiles');
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_cases');
    }
}