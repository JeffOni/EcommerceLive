<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;
use Illuminate\Console\Command;

class DebugSpecificAssignment extends Command
{
    protected $signature = 'debug:specific-assignment';
    protected $description = 'Debug muy específico del proceso de asignación';

    public function handle()
    {
        $this->info("=== DEBUG ESPECÍFICO DE ASIGNACIÓN ===");

        $order = Order::find(1);
        $driver = DeliveryDriver::find(1);

        if (!$order || !$driver) {
            $this->error('Orden o conductor no encontrados');
            return 1;
        }

        $this->info("Orden #{$order->getKey()} - Estado: {$order->status}");
        $this->info("Conductor #{$driver->getKey()} - Nombre: {$driver->name}");

        // Verificar estado del envío actual
        if ($order->hasShipment()) {
            $shipment = $order->shipment()->first();
            $this->info("Envío existente #{$shipment->getKey()}:");
            $this->info("  - Estado: {$shipment->status->value}");
            $this->info("  - Conductor actual: " . ($shipment->delivery_driver_id ?? 'Ninguno'));
            $this->info("  - Puede ser asignado: " . ($shipment->canBeAssigned() ? 'Sí' : 'No'));
        } else {
            $this->info("La orden NO tiene envío");
        }

        $shipmentService = app(ShipmentService::class);

        $this->info("\nEjecutando createShipmentForOrderWithDriver...");

        $result = $shipmentService->createShipmentForOrderWithDriver($order, $driver);

        if ($result) {
            $this->info("✅ Método ejecutado exitosamente");

            // Verificar cambios
            $order->refresh();
            $this->info("Estado de orden después: {$order->status}");

            if ($order->hasShipment()) {
                $shipment = $order->shipment()->first();
                $this->info("Estado de envío después: {$shipment->status->value}");
                $this->info("Conductor asignado después: " . ($shipment->delivery_driver_id ?? 'Ninguno'));
            }
        } else {
            $this->error("❌ El método falló");
        }

        return 0;
    }
}
