<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Services\SimpleDriverAssignmentService;

class TestSimpleFlow extends Command
{
    protected $signature = 'test:simple-flow';
    protected $description = 'Probar el nuevo flujo simple de asignación de repartidores';

    public function handle()
    {
        $this->info('🚀 PROBANDO FLUJO SIMPLE DE ASIGNACIÓN');
        $this->newLine();

        // Crear nueva orden de prueba
        $order = Order::create([
            'user_id' => 1,
            'status' => 1, // PENDIENTE
            'payment_method' => \App\Enums\PaymentMethod::EFECTIVO->value,
            'subtotal' => 100.00,
            'tax' => 10.00,
            'total' => 110.00,
            'shipping_address' => json_encode([
                'province' => 'Santa Cruz',
                'city' => 'Santa Cruz de la Sierra',
                'address' => 'Dirección de prueba simple'
            ])
        ]);

        $this->info("✅ Orden creada: #{$order->getKey()}");
        $this->info("   Estado inicial: {$order->status} (PENDIENTE)");

        // Usar el servicio simple
        $service = new SimpleDriverAssignmentService();

        // Buscar repartidor disponible
        $driver = $service->findAvailableDriver();

        if ($driver) {
            $this->info("👨‍💼 Repartidor disponible: {$driver->getAttribute('name')}");

            // Asignar repartidor
            $result = $service->assignDriverToOrder($order, $driver);

            if ($result['success']) {
                $this->info("✅ {$result['message']}");

                // Verificar resultados
                $order->refresh();
                $this->info("   Estado de orden actualizado: {$order->status} (esperado: 4)");

                if ($order->hasShipment()) {
                    $shipment = $order->shipment()->first();
                    $this->info("   Envío creado: #{$shipment->getKey()}");
                    $this->info("   Estado del envío: {$shipment->status->value} (esperado: 2)");
                    $this->info("   Repartidor asignado: {$shipment->getAttribute('delivery_driver_id')}");
                    $this->info("   Fecha de asignación: {$shipment->assigned_at->format('Y-m-d H:i:s')}");
                } else {
                    $this->error("❌ No se creó el envío");
                }
            } else {
                $this->error("❌ {$result['message']}");
            }
        } else {
            $this->error("❌ No hay repartidores disponibles");
        }

        $this->newLine();
        $this->info('🎯 PRUEBA SIMPLE COMPLETADA');
    }
}
