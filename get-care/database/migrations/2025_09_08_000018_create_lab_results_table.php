<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_results', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->uuid('patient_id')->nullable();
            $table->text('result_data')->nullable();
            $table->text('result_file_url')->nullable();
            $table->date('result_date')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('lab_results');
    }
}