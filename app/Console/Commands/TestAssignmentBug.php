<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;

class TestAssignmentBug extends Command
{
    protected $signature = 'test:assignment-bug';
    protected $description = 'Test the assignment bug where orders jump to status 5';

    public function handle()
    {
        $this->info('🔍 DIAGNÓSTICO DEL BUG DE ASIGNACIÓN');
        $this->info('=====================================');
        $this->newLine();

        // Buscar una orden en estado preparando (3) o crear/modificar una existente
        $order = Order::where('status', 3)->first();

        if (!$order) {
            // Si no hay órdenes en estado 3, buscar cualquier orden y cambiar su estado
            $order = Order::first();
            if (!$order) {
                $this->error('❌ No hay órdenes en la base de datos para probar');
                return;
            }

            $this->info("📝 Usando orden existente #{$order->id}");
            $this->info("   Estado original: {$order->status}");

            // Cambiar temporalmente a estado "Preparando"
            $order->update(['status' => 3]);
            $this->info("   Estado cambiado a: 3 (Preparando)");
        } else {
            $this->info("✅ Orden encontrada: #{$order->id}");
            $this->info("   Estado inicial: {$order->status}");
        }

        $this->newLine();

        // Buscar un repartidor activo
        $driver = DeliveryDriver::where('is_active', true)->first();
        if (!$driver) {
            $this->error('❌ No hay repartidores activos para probar');
            return;
        }

        $this->info("✅ Repartidor encontrado: {$driver->name}");
        $this->newLine();

        try {
            $shipmentService = app(ShipmentService::class);

            $this->info('🚀 PASO 1: Estado antes de asignación');
            $order->refresh();
            $this->info("   Estado actual: {$order->status}");
            $this->newLine();

            $this->info('🚀 PASO 2: Verificando si ya tiene envío');
            if ($order->hasShipment()) {
                $this->info("   ✅ Ya tiene envío");
                $shipment = $order->shipment()->first();
                $success = $shipmentService->assignDriverToShipment($shipment, $driver);
            } else {
                $this->info("   ℹ️ Creando nuevo envío con repartidor");
                $shipment = $shipmentService->createShipmentForOrderWithDriver($order, $driver);
                $success = $shipment ? true : false;
            }

            if (!$success || !$shipment) {
                $this->error('❌ No se pudo crear/asignar el envío');
                return;
            }

            $this->info("   ✅ Envío procesado: {$shipment->tracking_number}");
            $this->info("   Estado del envío: {$shipment->status->value}");
            $this->newLine();

            $this->info('🚀 PASO 3: Estado después de procesar envío');
            $order->refresh();
            $this->info("   Estado de la orden: {$order->status}");
            $this->newLine();

            $this->info('🚀 PASO 4: Actualizando orden a "Asignado" (4)');
            $order->update(['status' => 4]);

            $this->info('🚀 PASO 5: Verificando estado final');
            $order->refresh();
            $this->info("   Estado final: {$order->status}");
            $this->newLine();

            if ($order->status != 4) {
                $this->error('🚨 BUG CONFIRMADO!');
                $this->error("   ❌ El estado NO es 4 (Asignado)");
                $this->error("   ❌ Cambió automáticamente a: {$order->status}");

                // Verificar el enum
                $this->newLine();
                $this->info('🔍 Verificando OrderStatus enum...');
                $this->info("   Estado 4 en enum: " . \App\Enums\OrderStatus::from(4)->label());
                $this->info("   Estado 5 en enum: " . \App\Enums\OrderStatus::from(5)->label());

            } else {
                $this->info('✅ Estado correcto mantenido');
            }

        } catch (\Exception $e) {
            $this->error('❌ Error durante el proceso: ' . $e->getMessage());
            $this->error('   Archivo: ' . $e->getFile());
            $this->error('   Línea: ' . $e->getLine());
            $this->newLine();
            $this->error('   Stack trace:');
            $this->error($e->getTraceAsString());
        }

        $this->newLine();
        $this->info('=== FIN DEL DIAGNÓSTICO ===');
    }
}
