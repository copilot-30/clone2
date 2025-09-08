<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique()->nullable();
            $table->text('blood_type')->nullable();
            $table->text('civil_status')->nullable();
            $table->text('philhealth_no')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->text('surgeries')->nullable();
            $table->text('family_history')->nullable();
            $table->text('medications')->nullable();
            $table->text('supplements')->nullable();
            $table->text('tag')->default('ongoing');
            $table->timestamps(); // created_at, updated_at
            $table->text('first_name');
            $table->text('last_name');
            $table->text('suffix')->nullable();
            $table->text('nickname')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->text('sex')->nullable();
            $table->text('primary_mobile')->nullable();
            $table->text('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}