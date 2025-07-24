<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Models\User;
use App\Services\ShipmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SimulateFullOrderFlow extends Command
{
    protected $signature = 'simulate:order-flow';
    protected $description = 'Simula el flujo completo de una orden para detectar progresión automática';

    public function handle()
    {
        $this->info("=== SIMULANDO FLUJO COMPLETO DE ORDEN ===");

        // Usar una orden existente para la prueba en lugar de crear una nueva
        $order = Order::where('status', '!=', 5)->first();

        if (!$order) {
            $this->error('No hay órdenes disponibles para la prueba');
            return 1;
        }

        $this->info("✓ Usando orden existente: #{$order->getKey()} - Estado inicial: {$order->status}");

        // Resetear orden a estado 3 para la prueba
        $this->info("Reseteando orden a estado 3 (En Preparación)...");
        $order->update(['status' => 3]);
        $order->refresh();
        $this->info("✓ Estado actual: {$order->status}");

        // Obtener un conductor para asignar
        $driver = DeliveryDriver::first();
        if (!$driver) {
            $this->error('No hay conductores disponibles');
            return 1;
        }

        $this->info("Asignando conductor #{$driver->getKey()}...");

        // Habilitar log detallado
        DB::enableQueryLog();

        // Setup tracking
        $this->setupDetailedTracking($order);

        $shipmentService = app(ShipmentService::class);
        $shipment = $shipmentService->createShipmentForOrderWithDriver($order, $driver);

        if ($shipment) {
            $this->info("✅ Envío creado y conductor asignado");
        } else {
            $this->error("❌ Error al crear envío");
            return 1;
        }

        // Esperar para cualquier proceso asíncrono
        $this->info("Esperando posibles procesos asíncronos...");
        sleep(3);

        // Recargar y verificar estado final
        $order->refresh();
        $this->info("Estado final: {$order->status}");

        if ($order->status == 5) {
            $this->error("🚨 BUG DETECTADO: La orden avanzó automáticamente a estado 5!");
        } else if ($order->status == 4) {
            $this->info("✅ Estado correcto: La orden se mantiene en estado 4");
        } else {
            $this->warn("⚠️ Estado inesperado: {$order->status}");
        }

        // Mostrar consultas SQL relevantes
        $queries = DB::getQueryLog();
        $this->info("\n=== CONSULTAS SQL DE UPDATE EN ORDERS ===");
        foreach ($queries as $query) {
            if (
                str_contains($query['query'], 'orders') &&
                (str_contains($query['query'], 'update') || str_contains($query['query'], 'UPDATE'))
            ) {
                $this->warn("SQL: " . $query['query']);
                $this->info("Bindings: " . json_encode($query['bindings']));
                $this->info("---");
            }
        }

        return 0;
    }

    private function setupDetailedTracking(Order $order)
    {
        Order::updating(function ($model) use ($order) {
            if ($model->getKey() === $order->getKey() && $model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;

                echo "\n🔍 CAMBIO DE ESTADO: {$oldStatus} → {$newStatus}\n";

                // Stack trace simplificado
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 15);
                echo "📋 Llamado desde:\n";
                foreach ($trace as $i => $frame) {
                    if (isset($frame['file']) && isset($frame['line'])) {
                        $file = str_replace(base_path(), '', $frame['file']);
                        if (str_contains($file, 'app/') || str_contains($file, 'vendor/')) {
                            echo "  #{$i} {$file}:{$frame['line']} - {$frame['function']}\n";
                        }
                    }
                }
                echo "\n";
            }
        });
    }
}
