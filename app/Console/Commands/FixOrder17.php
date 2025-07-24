<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Services\SimpleDriverAssignmentService;

class FixOrder17 extends Command
{
    protected $signature = 'fix:order17';
    protected $description = 'Corregir la orden 17 usando el nuevo flujo simple';

    public function handle()
    {
        $this->info('🔧 CORRIGIENDO ORDEN 17 CON FLUJO SIMPLE');
        $this->newLine();

        $order = Order::find(17);
        if (!$order) {
            $this->error('Orden 17 no encontrada');
            return;
        }

        $this->info("📋 Orden 17 encontrada:");
        $this->info("   Estado actual: {$order->status}");

        if ($order->hasShipment()) {
            $shipment = $order->shipment()->first();
            $this->info("   Envío existente: #{$shipment->getKey()}");
            $this->info("   Repartidor actual: " . ($shipment->getAttribute('delivery_driver_id') ?? 'Sin asignar'));
        }

        // Usar el servicio simple
        $service = new SimpleDriverAssignmentService();

        // Buscar repartidor disponible
        $driver = $service->findAvailableDriver();

        if ($driver) {
            $this->info("👨‍💼 Asignando repartidor: {$driver->getAttribute('name')}");

            $result = $service->assignDriverToOrder($order, $driver);

            if ($result['success']) {
                $this->info("✅ {$result['message']}");

                // Verificar resultados
                $order->refresh();
                $this->info("   Estado de orden: {$order->status}");

                if ($order->hasShipment()) {
                    $shipment = $order->shipment()->first();
                    $shipment->refresh();
                    $this->info("   Estado del envío: {$shipment->status->value}");
                    $this->info("   Repartidor asignado: {$shipment->getAttribute('delivery_driver_id')}");
                }
            } else {
                $this->error("❌ {$result['message']}");
            }
        } else {
            $this->error("❌ No hay repartidores disponibles");
        }

        $this->newLine();
        $this->info('🎯 CORRECCIÓN COMPLETADA');
    }
}
