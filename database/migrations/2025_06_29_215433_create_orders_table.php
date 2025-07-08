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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('pdf_path')->nullable(); // Ruta del PDF de la orden
            $table->json('content')->nullable(); // Contenido del carrito (productos, cantidades, etc.)
            $table->tinyInteger('status')->default(1); // 1=pending, 2=paid, 3=processing, 4=shipped, 5=delivered, 6=cancelled, 7=confirmed_cash_delivery
            $table->integer('payment_method')->nullable(); // 1=payphone, 2=transfer, 3=cash, 4=qr
            $table->unsignedBigInteger('payment_id')->nullable(); // Relación opcional con tabla payments
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->json('shipping_address'); // Toda la info de dirección y receptor
            $table->text('notes')->nullable();
            $table->timestamps();

            // Índices para mejor performance
            $table->index(['user_id', 'status']);
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
