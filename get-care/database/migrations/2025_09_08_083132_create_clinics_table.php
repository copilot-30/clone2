<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('name')->nullable(false);
            $table->text('address')->nullable(false);
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('postal_code')->nullable();
            $table->text('phone')->nullable();
            $table->text('email')->nullable();
            $table->jsonb('operating_hours')->nullable();
            $table->json('facilities')->nullable();
            $table->boolean('is_active')->nullable(false)->default(true);
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('now()'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clinics');
    }
}