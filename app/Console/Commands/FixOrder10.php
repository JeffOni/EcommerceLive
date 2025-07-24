<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Models\Shipment;
use App\Services\ShipmentService;

class FixOrder10 extends Command
{
    protected $signature = 'fix:order10';
    protected $description = 'Corregir la orden 10 y asignar repartidor correctamente';

    public function handle()
    {
        $this->info('🔧 CORRIGIENDO ORDEN 10');
        $this->newLine();

        // 1. Obtener la orden 10
        $order = Order::find(10);
        if (!$order) {
            $this->error('❌ Orden 10 no encontrada');
            return;
        }

        $this->info("✅ Orden encontrada: #{$order->getKey()}");
        $this->info("   Estado actual: {$order->status}");
        $this->info("   Método de pago: {$order->payment_method}");

        // 2. Verificar envío existente
        if ($order->hasShipment()) {
            $shipment = $order->shipment()->first();
            $this->info("📦 Envío existente: #{$shipment->getKey()}");
            $this->info("   Estado: {$shipment->status->value}");
            $this->info("   Repartidor: " . ($shipment->getAttribute('delivery_driver_id') ?? 'Sin asignar'));
        }

        // 3. Obtener repartidor disponible
        $driver = DeliveryDriver::where('is_active', true)->first();
        if (!$driver) {
            $this->error('❌ No hay repartidores activos');
            return;
        }

        $this->info("👨‍💼 Repartidor disponible: {$driver->getAttribute('name')}");

        // 4. Intentar asignar usando el servicio
        $shipmentService = app(ShipmentService::class);

        try {
            $this->info("\n🔄 Asignando repartidor...");
            $success = $shipmentService->assignDriverWithLimit($order, $driver);

            if ($success) {
                $this->info("✅ Asignación exitosa");

                // Verificar cambios
                $order->refresh();
                $this->info("   Nuevo estado de orden: {$order->status}");

                if ($order->hasShipment()) {
                    $shipment = $order->shipment()->first();
                    $shipment->refresh();
                    $this->info("   Estado del envío: {$shipment->status->value}");
                    $this->info("   Repartidor asignado: {$shipment->getAttribute('delivery_driver_id')}");
                }
            } else {
                $this->error("❌ Falló la asignación");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("   Archivo: " . $e->getFile() . ":" . $e->getLine());
        }

        $this->newLine();
        $this->info('🎯 PROCESO COMPLETADO');
    }
}
