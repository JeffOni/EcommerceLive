<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DebugOrderAssignmentFlow extends Command
{
    protected $signature = 'debug:assignment-flow';
    protected $description = 'Debug específico del flujo de asignación de conductores';

    public function handle()
    {
        $this->info("=== DEBUG DEL FLUJO DE ASIGNACIÓN ===");

        $driver = DeliveryDriver::first();
        if (!$driver) {
            $this->error('No hay conductores disponibles');
            return 1;
        }

        $shipmentService = app(ShipmentService::class);

        // CASO 1: Orden sin envío existente
        $this->info("\n🔍 CASO 1: Orden SIN envío existente");
        $order1 = Order::whereDoesntHave('shipment')->where('status', 3)->first();

        if ($order1) {
            $this->info("Orden #{$order1->getKey()} - Estado inicial: {$order1->status}");
            $this->setupTracking($order1, "CASO-1");

            DB::enableQueryLog();
            $shipment1 = $shipmentService->createShipmentForOrderWithDriver($order1, $driver);
            $queries1 = DB::getQueryLog();

            $order1->refresh();
            $this->info("Estado final: {$order1->status}");
            $this->showQueries($queries1, "CASO-1");

            // Esperar para ver si hay cambios asíncronos
            sleep(2);
            $order1->refresh();
            if ($order1->status == 5) {
                $this->error("🚨 CASO 1: Cambió a estado 5!");
            }
        } else {
            $this->warn("No hay órdenes sin envío en estado 3");
        }

        // CASO 2: Orden con envío existente
        $this->info("\n🔍 CASO 2: Orden CON envío existente");
        $order2 = Order::whereHas('shipment')->where('status', 3)->first();

        if ($order2) {
            $this->info("Orden #{$order2->getKey()} - Estado inicial: {$order2->status}");
            $this->setupTracking($order2, "CASO-2");

            DB::enableQueryLog();
            $shipment2 = $shipmentService->createShipmentForOrderWithDriver($order2, $driver);
            $queries2 = DB::getQueryLog();

            $order2->refresh();
            $this->info("Estado final: {$order2->status}");
            $this->showQueries($queries2, "CASO-2");

            // Esperar para ver si hay cambios asíncronos
            sleep(2);
            $order2->refresh();
            if ($order2->status == 5) {
                $this->error("🚨 CASO 2: Cambió a estado 5!");
            }
        } else {
            $this->warn("No hay órdenes con envío en estado 3");
        }

        // CASO 3: Probar el controlador directamente
        $this->info("\n🔍 CASO 3: Usando OrderController::assignDriver");
        $order3 = Order::where('status', 3)->first();

        if ($order3) {
            $this->info("Orden #{$order3->getKey()} - Estado inicial: {$order3->status}");
            $this->setupTracking($order3, "CASO-3");

            DB::enableQueryLog();

            // Simular la llamada del controlador
            $controller = app(\App\Http\Controllers\Admin\OrderController::class);
            $request = new \Illuminate\Http\Request();
            $request->merge(['delivery_driver_id' => $driver->getKey()]);

            try {
                $response = $controller->assignDriver($request, $order3);
                $this->info("Respuesta del controlador: " . ($response->getStatusCode() == 200 ? 'Éxito' : 'Error'));
            } catch (\Exception $e) {
                $this->error("Error en controlador: " . $e->getMessage());
            }

            $queries3 = DB::getQueryLog();

            $order3->refresh();
            $this->info("Estado final: {$order3->status}");
            $this->showQueries($queries3, "CASO-3");

            // Esperar para ver si hay cambios asíncronos
            sleep(2);
            $order3->refresh();
            if ($order3->status == 5) {
                $this->error("🚨 CASO 3: Cambió a estado 5!");
            }
        }

        return 0;
    }

    private function setupTracking(Order $order, string $case)
    {
        Order::updating(function ($model) use ($order, $case) {
            if ($model->getKey() === $order->getKey() && $model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;

                echo "\n🔍 [{$case}] CAMBIO DE ESTADO: {$oldStatus} → {$newStatus}\n";

                // Stack trace compacto
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 8);
                foreach ($trace as $i => $frame) {
                    if (isset($frame['file']) && isset($frame['line']) && str_contains($frame['file'], 'app/')) {
                        $file = str_replace(base_path() . '/app/', '', $frame['file']);
                        echo "  #{$i} {$file}:{$frame['line']} - {$frame['function']}\n";
                    }
                }
            }
        });
    }

    private function showQueries(array $queries, string $case)
    {
        foreach ($queries as $query) {
            if (
                str_contains($query['query'], 'orders') &&
                (str_contains($query['query'], 'update') || str_contains($query['query'], 'UPDATE'))
            ) {
                $this->warn("[{$case}] SQL: " . $query['query']);
                $this->info("[{$case}] Bindings: " . json_encode($query['bindings']));
            }
        }
    }
}
