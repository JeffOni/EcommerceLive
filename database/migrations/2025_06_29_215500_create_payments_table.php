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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('payment_method', ['payphone', 'bank_transfer', 'cash_on_delivery', 'qr_deuna']);
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'pending_verification', 'approved', 'rejected', 'pending_delivery'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('receipt_path')->nullable();
            $table->text('comments')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
