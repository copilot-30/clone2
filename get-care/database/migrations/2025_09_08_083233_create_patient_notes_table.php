<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePatientNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_notes', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('patient_id')->nullable();
            $table->uuid('doctor_id')->nullable();
            $table->text('subject')->nullable();
            $table->text('content')->nullable(false);
            $table->text('note_type')->nullable(false)->default('general');
            $table->text('visibility')->nullable(false)->default('ALL');
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('doctor_id')->references('id')->on('doctor_profiles');
            $table->foreign('patient_id')->references('id')->on('patient_profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_notes');
    }
}