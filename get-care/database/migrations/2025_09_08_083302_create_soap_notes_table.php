<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSoapNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soap_notes', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('visit_id')->nullable();
            $table->uuid('patient_id')->nullable();
            $table->uuid('doctor_id')->nullable();
            $table->date('date')->nullable(false);
            $table->text('subjective')->nullable();
            $table->text('chief_complaint')->nullable();
            $table->text('history_of_illness')->nullable();
            $table->text('objective')->nullable();
            $table->jsonb('vital_signs')->nullable();
            $table->text('assessment')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('plan')->nullable();
            $table->text('prescription')->nullable();
            $table->text('test_request')->nullable();
            $table->text('remarks')->nullable();
            $table->text('remarks_note')->nullable();
            $table->text('remarks_template')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('visit_id')->references('id')->on('patient_visits');
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
        Schema::dropIfExists('soap_notes');
    }
}