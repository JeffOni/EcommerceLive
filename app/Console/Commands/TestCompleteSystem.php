<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;
use Illuminate\Console\Command;

class TestCompleteSystem extends Command
{
    protected $signature = 'test:complete-system';
    protected $description = 'Prueba completa del sistema de órdenes y envíos';

    public function handle()
    {
        $this->info("🧪 === PRUEBA COMPLETA DEL SISTEMA ===");

        // 1. Probar asignación desde ShipmentService
        $this->info("\n1️⃣ Probando ShipmentService...");
        $this->testShipmentService();

        // 2. Probar asignación desde ShipmentController
        $this->info("\n2️⃣ Probando ShipmentController...");
        $this->testShipmentController();

        // 3. Probar asignación desde OrderController  
        $this->info("\n3️⃣ Probando OrderController...");
        $this->testOrderController();

        $this->info("\n✅ === PRUEBAS COMPLETADAS ===");

        return 0;
    }

    private function testShipmentService()
    {
        $order = Order::find(1);
        $driver = DeliveryDriver::find(1);

        if (!$order || !$driver) {
            $this->error('No hay orden o conductor disponible');
            return;
        }

        // Reset orden
        $order->update(['status' => 3]);

        $this->info("Estado inicial de orden: {$order->status}");

        $shipmentService = app(ShipmentService::class);
        $result = $shipmentService->createShipmentForOrderWithDriver($order, $driver);

        if ($result) {
            $order->refresh();
            if ($order->status == 4) {
                $this->info("✅ ShipmentService: CORRECTO - Estado final: {$order->status}");
            } else {
                $this->error("❌ ShipmentService: FALLO - Estado final: {$order->status}");
            }
        } else {
            $this->error("❌ ShipmentService: FALLO - No se pudo crear/asignar envío");
        }
    }

    private function testShipmentController()
    {
        $order = Order::find(1);
        $driver = DeliveryDriver::find(1);

        if (!$order || !$driver || !$order->hasShipment()) {
            $this->warn('No hay datos suficientes para probar ShipmentController');
            return;
        }

        // Reset orden
        $order->update(['status' => 3]);

        $this->info("Estado inicial de orden: {$order->status}");

        $shipment = $order->shipment()->first();
        $controller = app(\App\Http\Controllers\Admin\ShipmentController::class);

        $request = new \Illuminate\Http\Request();
        $request->merge(['delivery_driver_id' => $driver->getKey()]);

        try {
            $response = $controller->assignDriver($request, $shipment);
            $responseData = $response->getData(true);

            if ($responseData['success'] ?? false) {
                $order->refresh();
                if ($order->status == 4) {
                    $this->info("✅ ShipmentController: CORRECTO - Estado final: {$order->status}");
                } else {
                    $this->error("❌ ShipmentController: FALLO - Estado final: {$order->status}");
                }
            } else {
                $this->error("❌ ShipmentController: FALLO - Respuesta negativa");
            }
        } catch (\Exception $e) {
            $this->error("❌ ShipmentController: ERROR - " . $e->getMessage());
        }
    }

    private function testOrderController()
    {
        $order = Order::find(1);
        $driver = DeliveryDriver::find(1);

        if (!$order || !$driver) {
            $this->warn('No hay datos suficientes para probar OrderController');
            return;
        }

        // Reset orden
        $order->update(['status' => 3]);

        $this->info("Estado inicial de orden: {$order->status}");

        $controller = app(\App\Http\Controllers\Admin\OrderController::class);

        $request = new \Illuminate\Http\Request();
        $request->merge(['delivery_driver_id' => $driver->getKey()]);

        try {
            $response = $controller->assignDriver($request, $order);

            if ($response->getStatusCode() == 200) {
                $order->refresh();
                if ($order->status == 4) {
                    $this->info("✅ OrderController: CORRECTO - Estado final: {$order->status}");
                } else {
                    $this->error("❌ OrderController: FALLO - Estado final: {$order->status}");
                }
            } else {
                $this->error("❌ OrderController: FALLO - Código de respuesta: " . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            $this->error("❌ OrderController: ERROR - " . $e->getMessage());
        }
    }
}
