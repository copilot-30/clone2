<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->primary();
            $table->uuid('visit_id')->nullable();
            $table->uuid('patient_id')->nullable();
            $table->uuid('doctor_id')->nullable();
            $table->date('date');
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
            $table->timestamps(); // created_at, updated_at
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