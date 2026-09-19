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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_uuid')->unique()->index();
            $table->foreignId('user_id')->index()->cascadeOnDelete()->constrained('users');
            $table->foreignId('membership_id')->nullable()->index()->cascadeOnDelete()->constrained('membership');
            $table->foreignId('membership_type_id')->nullable()->cascadeOnDelete()->constrained('membership_type');
            $table->foreignId('staff_id')->nullable()->index()->nullOnDelete()->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('MAD');
            $table->enum('transaction_type', [
                'membership_purchase',
                'membership_renewal',
                'membership_upgrade',
                'membership_downgrade',
                'refund',
                'product_sale',
                'service_fee',
                'penalty',
                'adjustment',
                'other',
            ])->default('membership_purchase');
            $table->enum('payment_method', [
                'cash',
                'credit_card',
                'debit_card',
                'bank_transfer',
                'mobile_money',
                'check',
                'online_gateway',
                'other',
            ])->default('cash');
            $table->string('payment_gateway', 50)->nullable();
            $table->string('gateway_transaction_id')->nullable()->index();
            $table->enum('status', [
                'pending',
                'completed',
                'failed',
                'refunded',
                'partially_refunded',
                'cancelled',
                'on_hold',
            ])->default('pending');
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('transaction_date')->useCurrent()->index();
            $table->timestamps();
            $table->index(['user_id', 'status', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
