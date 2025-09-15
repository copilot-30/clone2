<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToLabResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_results', function (Blueprint $table) {
            $table->uuid('soap_note_id')->nullable()->after('patient_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lab_results', function (Blueprint $table) { 
            $table->dropColumn('soap_note_id');
        });
    }
}
