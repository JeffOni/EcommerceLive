<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;
use App\Enums\ShipmentStatus;
use Illuminate\Console\Command;

class DemonstrateBug extends Command
{
    protected $signature = 'demonstrate:bug';
    protected $description = 'Demuestra el bug de progresión automática de estado';

    public function handle()
    {
        $this->info("=== DEMOSTRACIÓN DEL BUG ===");

        $order = Order::find(1);
        $driver = DeliveryDriver::find(1);

        if (!$order || !$driver) {
            $this->error('Orden o conductor no encontrados');
            return 1;
        }

        $this->info("Escenario: Orden con envío ya asignado");
        $this->info("Estado inicial de orden: {$order->status}");

        if ($order->hasShipment()) {
            $shipment = $order->shipment()->first();
            $this->info("Estado inicial de envío: {$shipment->status->value} ({$shipment->status->label()})");
            $this->info("Conductor actual: {$shipment->delivery_driver_id}");
        }

        $this->info("\nPasos del flujo problemático:");
        $this->info("1. Usuario intenta asignar conductor a orden en estado 3");
        $this->info("2. La orden ya tiene un envío en estado ASSIGNED");
        $this->info("3. ShipmentService::createShipmentForOrderWithDriver es llamado");
        $this->info("4. Como ya hay envío, se llama assignDriverToShipment");
        $this->info("5. assignDriverToShipment falla porque canBeAssigned() = false");
        $this->info("6. El estado de la orden NO se actualiza a 4");
        $this->info("7. ¿Algo más cambia el estado automáticamente?");

        $shipmentService = app(ShipmentService::class);

        $this->info("\nEjecutando el flujo...");
        $result = $shipmentService->createShipmentForOrderWithDriver($order, $driver);

        $order->refresh();
        $this->info("Estado final de orden: {$order->status}");

        if ($order->status == 3) {
            $this->warn("⚠️ Como se esperaba: El estado se quedó en 3 (no se actualizó a 4)");
            $this->info("Esto significa que el usuario ve que 'asignó' el conductor pero la orden sigue en preparación");
            $this->info("Posiblemente después algo automático la cambie a estado 5");
        }

        // Simular lo que podría pasar después...
        $this->info("\n🔍 Investigando qué podría cambiar el estado después...");

        // Verificar si hay algún proceso que verifique órdenes con envíos asignados
        $this->info("Órdenes en estado 3 con envíos asignados:");
        $problematicOrders = Order::where('status', 3)
            ->whereHas('shipment', function ($query) {
                $query->where('status', ShipmentStatus::ASSIGNED);
            })
            ->get();

        foreach ($problematicOrders as $po) {
            $this->warn("  - Orden #{$po->getKey()}: Estado {$po->status}, envío asignado");
        }

        return 0;
    }
}
