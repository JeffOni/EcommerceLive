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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Clave única para la configuración
            $table->text('value')->nullable(); // Valor de la configuración
            $table->string('type')->default('text'); // Tipo de dato (text, json, boolean, etc.)
            $table->text('description')->nullable(); // Descripción de la configuración
            $table->boolean('is_public')->default(false); // Si es visible para usuarios públicos
            $table->timestamps();

            // Índices para mejorar performance
            $table->index('key');
            $table->index('is_public');
        });

        // Insertar configuraciones predeterminadas
        DB::table('system_settings')->insert([
            [
                'key' => 'pickup_address',
                'value' => json_encode([
                    'name' => 'Tienda Principal',
                    'address' => 'Dirección de la tienda - Actualizar en configuración',
                    'city' => 'Ciudad',
                    'phone' => '+593 99 999 9999',
                    'hours' => 'Lunes a Viernes: 9:00 AM - 6:00 PM, Sábados: 9:00 AM - 1:00 PM',
                    'coordinates' => [
                        'lat' => -0.1807,
                        'lng' => -78.4678
                    ]
                ]),
                'type' => 'json',
                'description' => 'Dirección física para retiro en tienda',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'delivery_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Habilitar servicio de entrega a domicilio',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'pickup_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Habilitar retiro en tienda',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'min_order_delivery',
                'value' => '25.00',
                'type' => 'decimal',
                'description' => 'Monto mínimo para entrega a domicilio',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
