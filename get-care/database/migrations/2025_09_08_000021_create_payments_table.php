<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuid('payable_id')->nullable();
            $table->text('payable_type')->nullable();
            $table->decimal('amount', 8, 2);
            $table->text('currency')->default('PHP');
            $table->text('payment_method')->nullable();
            $table->text('transaction_id')->nullable();
            $table->text('status')->default('PENDING');
            $table->timestamp('payment_date')->nullable();
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
        Schema::dropIfExists('payments');
    }
}