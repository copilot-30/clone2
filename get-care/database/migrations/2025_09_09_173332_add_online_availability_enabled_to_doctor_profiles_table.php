<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnlineAvailabilityEnabledToDoctorProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->boolean('online_availability_enabled')->default(false); // Add after an appropriate column, e.g., 'description'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->dropColumn('online_availability_enabled');
        });
    }
}
