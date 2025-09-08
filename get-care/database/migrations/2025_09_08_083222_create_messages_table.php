<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('conversation_id')->nullable();
            $table->uuid('sender_id')->nullable();
            $table->text('message_type')->nullable(false)->default('TEXT');
            $table->text('content')->nullable();
            $table->text('file_url')->nullable();
            $table->text('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('is_read')->nullable(false)->default(false);
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('now()'));
            $table->foreign('conversation_id')->references('id')->on('conversations');
            $table->foreign('sender_id')->references('id')->on('users');
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