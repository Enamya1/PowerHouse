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
// id 
// user_id
// membership_type_id
// amount
// qr_key
// status
// expires_at
// confirmed_at
// confirmed_by
// created_at
// updated_at
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('membership_type_id')->constrained('membership_type');
            $table->decimal('amount', 8, 2);
            $table->string('qr_key',64)->unique();
            $table->enum('status',['pending','confirmed','rejected'])->default('pending');
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
