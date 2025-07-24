<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrackOrderStatusChanges extends Command
{
    protected $signature = 'track:order-status {orderId} {driverId}';
    protected $description = 'Rastrea cambios de estado en una orden después de asignar conductor';

    public function handle()
    {
        $orderId = $this->argument('orderId');
        $driverId = $this->argument('driverId');

        $order = Order::find($orderId);
        $driver = DeliveryDriver::find($driverId);

        if (!$order || !$driver) {
            $this->error('Orden o conductor no encontrado');
            return 1;
        }

        $this->info("=== RASTREANDO ORDEN #{$order->getKey()} ===");
        $this->info("Estado inicial: {$order->status}");

        // Habilitar log de consultas SQL
        DB::enableQueryLog();

        // Crear observer temporal para rastrear cambios
        $this->setupOrderTracking($order);

        $shipmentService = app(ShipmentService::class);

        $this->info("Asignando conductor...");

        try {
            // Crear envío y asignar conductor
            $shipment = $shipmentService->createShipmentForOrderWithDriver($order, $driver);
            $result = ($shipment !== null);

            if ($result) {
                $this->info("✅ Conductor asignado exitosamente");
            } else {
                $this->error("❌ Error al asignar conductor");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        // Esperar un momento para cualquier proceso asíncrono
        sleep(2);

        // Recargar orden para ver estado final
        $order->refresh();
        $this->info("Estado final: {$order->status}");

        // Mostrar todas las consultas SQL ejecutadas
        $queries = DB::getQueryLog();
        $this->info("\n=== CONSULTAS SQL EJECUTADAS ===");
        foreach ($queries as $query) {
            if (str_contains($query['query'], 'orders') && str_contains($query['query'], 'update')) {
                $this->warn("UPDATE en orders: " . $query['query']);
                $this->info("Bindings: " . json_encode($query['bindings']));
            }
        }

        return 0;
    }

    private function setupOrderTracking(Order $order)
    {
        // Usar eventos de Eloquent para rastrear cambios
        Order::updating(function ($model) use ($order) {
            if ($model->getKey() === $order->getKey() && $model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;

                $this->warn("⚠️  CAMBIO DE ESTADO DETECTADO: {$oldStatus} → {$newStatus}");

                // Obtener stack trace para ver qué causó el cambio
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
                $this->info("Stack trace del cambio:");
                foreach ($trace as $i => $frame) {
                    if (isset($frame['file']) && isset($frame['line'])) {
                        $file = basename($frame['file']);
                        $this->info("  #{$i} {$file}:{$frame['line']} - {$frame['function']}");
                    }
                }
            }
        });

        Order::updated(function ($model) use ($order) {
            if ($model->getKey() === $order->getKey()) {
                $this->info("✓ Orden actualizada - Estado actual: {$model->status}");
            }
        });
    }
}
