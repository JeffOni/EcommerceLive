<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;
use Illuminate\Console\Command;

class TestFixedBug extends Command
{
    protected $signature = 'test:fixed-bug';
    protected $description = 'Probar que el bug está solucionado';

    public function handle()
    {
        $this->info("=== PROBANDO LA SOLUCIÓN DEL BUG ===");

        $order = Order::find(1);
        $driver = DeliveryDriver::find(1);

        if (!$order || !$driver) {
            $this->error('Orden o conductor no encontrados');
            return 1;
        }

        // Reset inicial
        $order->update(['status' => 3]);
        $this->info("✓ Orden reseteada a estado 3 (En Preparación)");

        $shipmentService = app(ShipmentService::class);

        $this->info("Ejecutando asignación de conductor...");
        $result = $shipmentService->createShipmentForOrderWithDriver($order, $driver);

        if ($result) {
            $order->refresh();

            if ($order->status == 4) {
                $this->info("✅ ÉXITO: La orden ahora está en estado 4 (Asignado)");
                $this->info("✅ El bug ha sido solucionado");

                // Verificar que no avance automáticamente a estado 5
                sleep(2);
                $order->refresh();

                if ($order->status == 4) {
                    $this->info("✅ Perfecto: El estado se mantiene en 4, no avanza automáticamente a 5");
                } else if ($order->status == 5) {
                    $this->error("❌ Aún hay un problema: La orden avanzó automáticamente a estado 5");
                } else {
                    $this->warn("⚠️ Estado inesperado: {$order->status}");
                }

            } else {
                $this->error("❌ El bug persiste: Estado final {$order->status}");
            }
        } else {
            $this->error("❌ Error al ejecutar el método");
        }

        return 0;
    }
}
