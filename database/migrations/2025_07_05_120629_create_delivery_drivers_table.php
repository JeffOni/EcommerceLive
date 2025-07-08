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
        Schema::create('delivery_drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre completo del repartidor
            $table->string('email')->unique(); // Email único
            $table->string('phone'); // Teléfono de contacto
            $table->string('license_number')->unique(); // Número de licencia de conducir
            $table->string('vehicle_type'); // Tipo de vehículo (moto, auto, bicicleta, etc.)
            $table->string('vehicle_plate')->nullable(); // Placa del vehículo
            $table->string('identification_number')->unique(); // Cédula o documento de identidad
            $table->text('address')->nullable(); // Dirección del repartidor
            $table->decimal('delivery_fee', 8, 2)->default(0.00); // Tarifa por entrega
            $table->boolean('is_active')->default(true); // Estado activo/inactivo
            $table->integer('total_deliveries')->default(0); // Total de entregas realizadas
            $table->decimal('rating', 3, 2)->default(5.00); // Calificación promedio (1-5)
            $table->timestamp('last_delivery_at')->nullable(); // Última entrega realizada
            $table->json('emergency_contact')->nullable(); // Contacto de emergencia
            $table->timestamps();

            // Índices para mejorar performance
            $table->index('is_active');
            $table->index('vehicle_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_drivers');
    }
};
