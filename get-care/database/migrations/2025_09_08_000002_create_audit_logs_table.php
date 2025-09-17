<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->text('action'); // From AuditListener
            $table->uuid('auditable_id')->nullable(); // From AuditListener
            $table->string('auditable_type')->nullable(); // From AuditListener
            $table->jsonb('old_values')->nullable(); // From AuditListener
            $table->jsonb('new_values')->nullable(); // From AuditListener
            $table->string('url')->nullable(); // From AuditListener
            $table->string('ip_address')->nullable(); // From AuditListener
            $table->text('user_agent')->nullable(); // From AuditListener
            $table->jsonb('tags')->nullable(); // From AuditListener
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}