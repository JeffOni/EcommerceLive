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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique(); // Número de seguimiento único
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Relación con orden
            $table->foreignId('delivery_driver_id')->nullable()->constrained()->onDelete('set null'); // Repartidor asignado

            // Estados del envío
            $table->integer('status')->default(1); // 1:Pendiente, 2:Asignado, 3:En camino, 4:Entregado, 5:Fallido

            // Información de entrega
            $table->json('pickup_address'); // Dirección de recogida (tienda)
            $table->json('delivery_address'); // Dirección de entrega (cliente)
            $table->decimal('delivery_fee', 8, 2)->default(0.00); // Costo de envío
            $table->decimal('distance_km', 8, 2)->nullable(); // Distancia estimada en KM
            $table->integer('estimated_time_minutes')->nullable(); // Tiempo estimado en minutos

            // Fechas importantes
            $table->timestamp('assigned_at')->nullable(); // Cuando se asignó al repartidor
            $table->timestamp('picked_up_at')->nullable(); // Cuando se recogió el paquete
            $table->timestamp('in_transit_at')->nullable(); // Cuando salió para entrega
            $table->timestamp('delivered_at')->nullable(); // Cuando se entregó
            $table->timestamp('failed_at')->nullable(); // Si falló la entrega

            // Información adicional
            $table->text('delivery_notes')->nullable(); // Notas del repartidor
            $table->text('failure_reason')->nullable(); // Razón del fallo si aplica
            $table->json('delivery_proof')->nullable(); // Foto, firma, etc.
            $table->decimal('customer_rating', 3, 2)->nullable(); // Calificación del cliente (1-5)
            $table->text('customer_feedback')->nullable(); // Comentarios del cliente

            // Coordenadas GPS (para tracking en tiempo real)
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('location_updated_at')->nullable();

            $table->timestamps();

            // Índices para mejorar performance
            $table->index('tracking_number');
            $table->index('status');
            $table->index(['delivery_driver_id', 'status']);
            $table->index('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
