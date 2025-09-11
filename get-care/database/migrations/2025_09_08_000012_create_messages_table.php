<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('conversation_id')->nullable();
            $table->uuid('sender_id')->nullable();
            $table->text('message_type')->default('TEXT');
            $table->text('content')->nullable();
            $table->text('file_url')->nullable();
            $table->text('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('is_read')->default(false);
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
        Schema::dropIfExists('messages');
    }
}