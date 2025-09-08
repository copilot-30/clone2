<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMedicalBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_backgrounds', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('patient_id')->unique()->nullable();
            $table->text('known_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->text('previous_surgeries')->nullable();
            $table->text('family_history')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('supplements')->nullable();
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('now()'));
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
        Schema::dropIfExists('medical_backgrounds');
    }
}