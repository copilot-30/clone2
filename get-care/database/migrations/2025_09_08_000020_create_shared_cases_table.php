<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->primary();
            $table->uuid('patient_id')->nullable();
            $table->uuid('sharing_doctor_id')->nullable();
            $table->uuid('receiving_doctor_id')->nullable();
            $table->text('case_description')->nullable();
            $table->jsonb('shared_data')->nullable();
            $table->jsonb('permissions')->nullable();
            $table->text('status')->default('PENDING');
            $table->timestamp('expires_at')->nullable();
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
        Schema::dropIfExists('shared_cases');
    }
}