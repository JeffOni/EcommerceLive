<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable(); // Nullable porque la orden puede crearse después
            $table->string('payment_method'); // 'bank_transfer', 'qr_deuna', 'cash_on_delivery', etc.
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'pending_verification', 'approved', 'rejected', 'confirmed'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('receipt_path')->nullable(); // Ruta del comprobante subido
            $table->text('comments')->nullable();
            $table->json('cart_data')->nullable(); // Datos del carrito al momento del pago
            $table->json('response_data')->nullable(); // Respuesta de APIs de pago
            $table->timestamp('verified_at')->nullable(); // Cuando se verificó el pago
            $table->unsignedBigInteger('verified_by')->nullable(); // Admin que verificó
            $table->timestamps();

            // Relaciones opcionales (porque el orden puede cambiar)
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');

            // Índices
            $table->index(['user_id', 'status']);
            $table->index('order_id');
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
