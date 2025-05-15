<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->string('channel_id');
            $table->string('sp_code');
            $table->string('request_type');
            $table->timestamp('timestamp');
            $table->string('user_id');
            $table->string('branch_code');
            $table->string('channel_ref')->unique();
            $table->string('bill_ref');
            $table->decimal('amount', 15, 2);
            $table->string('credit_account');
            $table->string('credit_currency');
            $table->string('payment_type');
            $table->string('channel_code');
            $table->string('payer_name');
            $table->string('payer_phone');
            $table->string('payer_email');
            $table->text('narration');
            $table->string('gateway_ref')->unique();
            $table->string('biller_receipt')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->string('accounting_status')->nullable();
            $table->string('biller_notified')->nullable();
            $table->string('status')->default('pending');
            $table->json('extra_fields')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('channel_ref');
            $table->index('bill_ref');
            $table->index('gateway_ref');
            $table->index('biller_receipt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_payments');
    }
};
