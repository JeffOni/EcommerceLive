<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Models\Shipment;
use App\Services\ShipmentService;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Http\Request;

class TestCompleteOrderFlow extends Command
{
    protected $signature = 'test:complete-order-flow';
    protected $description = 'Probar el flujo completo de órdenes desde la creación hasta la entrega';

    public function handle()
    {
        $this->info('🧪 PROBANDO FLUJO COMPLETO DE ÓRDENES');
        $this->newLine();

        // Test 1: Orden con pago en efectivo
        $this->testCashOrder();
        $this->newLine();

        // Test 2: Orden con transferencia
        $this->testTransferOrder();
        $this->newLine();

        // Test 3: Verificar límite de repartidores
        $this->testDriverLimit();

        $this->newLine();
        $this->info('🎉 TODAS LAS PRUEBAS COMPLETADAS');
    }

    private function testCashOrder()
    {
        $this->info('📋 TEST 1: ORDEN CON PAGO EN EFECTIVO');

        // Crear orden
        $order = Order::create([
            'user_id' => 1,
            'status' => 1, // PENDIENTE
            'payment_method' => \App\Enums\PaymentMethod::EFECTIVO->value,
            'subtotal' => 90.00,
            'tax' => 10.00,
            'total' => 100.00,
            'shipping_address' => json_encode([
                'province' => 'Santa Cruz',
                'city' => 'Santa Cruz de la Sierra',
                'address' => 'Calle de prueba 123'
            ])
        ]);

        $this->info("✅ Orden creada: #{$order->getKey()} (Estado: {$order->status})");

        // Asignar repartidor automáticamente
        $shipmentService = app(ShipmentService::class);
        $success = $shipmentService->autoAssignDriver($order);

        if ($success) {
            $order->refresh();
            $shipment = $order->shipment()->first();

            $this->info("✅ Repartidor asignado:");
            $this->info("   - Estado orden: {$order->status} (esperado: 4)");
            $this->info("   - Estado envío: {$shipment->status->value} (esperado: 2)");
            $this->info("   - Repartidor: {$shipment->getAttribute('delivery_driver_id')}");

            // Marcar como en tránsito
            $controller = new OrderController($shipmentService);
            $request = new Request();
            $response = $controller->markAsInTransit($request, $order);
            $responseData = $response->getData(true);

            if ($responseData['success']) {
                $order->refresh();
                $shipment->refresh();
                $this->info("✅ Marcado como en tránsito:");
                $this->info("   - Estado orden: {$order->status} (esperado: 5)");
                $this->info("   - Estado envío: {$shipment->status->value} (esperado: 4)");
            }
        } else {
            $this->error("❌ Error asignando repartidor");
        }
    }

    private function testTransferOrder()
    {
        $this->info('📋 TEST 2: ORDEN CON TRANSFERENCIA');

        // Crear orden
        $order = Order::create([
            'user_id' => 1,
            'status' => 1, // PENDIENTE
            'payment_method' => \App\Enums\PaymentMethod::TRANSFERENCIA->value,
            'subtotal' => 150.00,
            'tax' => 15.00,
            'total' => 165.00,
            'shipping_address' => json_encode([
                'province' => 'Santa Cruz',
                'city' => 'Santa Cruz de la Sierra',
                'address' => 'Otra calle 456'
            ])
        ]);

        $this->info("✅ Orden creada: #{$order->getKey()} (Estado: {$order->status})");

        // Simular verificación de pago
        $order->update(['status' => 2]); // PAGADO
        $this->info("✅ Pago verificado (Estado: {$order->status})");

        // Asignar repartidor automáticamente
        $shipmentService = app(ShipmentService::class);
        $success = $shipmentService->autoAssignDriver($order);

        if ($success) {
            $order->refresh();
            $shipment = $order->shipment()->first();

            $this->info("✅ Repartidor asignado:");
            $this->info("   - Estado orden: {$order->status} (esperado: 4)");
            $this->info("   - Estado envío: {$shipment->status->value} (esperado: 2)");
        }
    }

    private function testDriverLimit()
    {
        $this->info('📋 TEST 3: VERIFICAR LÍMITE DE REPARTIDORES');

        $driver = DeliveryDriver::where('is_active', true)->first();

        // Contar envíos activos actuales
        $activeShipments = Shipment::where('delivery_driver_id', $driver->getKey())
            ->whereIn('status', [
                \App\Enums\ShipmentStatus::PENDING->value,
                \App\Enums\ShipmentStatus::ASSIGNED->value,
                \App\Enums\ShipmentStatus::IN_TRANSIT->value
            ])
            ->count();

        $this->info("📊 Repartidor {$driver->getAttribute('name')} tiene {$activeShipments} envíos activos");

        if ($activeShipments < 5) {
            $this->info("✅ Puede recibir más envíos (límite: 5)");
        } else {
            $this->info("⚠️  Ha alcanzado el límite máximo de envíos");
        }
    }
}
