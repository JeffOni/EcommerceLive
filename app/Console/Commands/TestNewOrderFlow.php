<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Http\Request;

class TestNewOrderFlow extends Command
{
    protected $signature = 'test:new-order-flow';
    protected $description = 'Probar el nuevo flujo de órdenes y asignación de repartidores';

    public function handle()
    {
        $this->info('🧪 PROBANDO NUEVO FLUJO DE ÓRDENES');
        $this->newLine();

        // 1. Crear orden de prueba en estado PENDIENTE (efectivo)
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

        $this->info("✅ Orden creada: #{$order->getKey()} - Estado: {$order->status} (PENDIENTE)");

        // 2. Verificar que la orden puede ser asignada directamente (efectivo)
        $driver = DeliveryDriver::where('is_active', true)->first();
        if (!$driver) {
            $this->error('❌ No hay repartidores activos disponibles');
            return;
        }

        $this->info("📋 Repartidor disponible: {$driver->getAttribute('name')}");

        // 3. Simular asignación de repartidor
        $shipmentService = app(ShipmentService::class);
        $success = $shipmentService->assignDriverWithLimit($order, $driver);

        if ($success) {
            $order->refresh();
            $this->info("✅ Repartidor asignado exitosamente");
            $this->info("   Estado de orden actualizado: {$order->status} (ASIGNADO)");
        } else {
            $this->error("❌ Error al asignar repartidor");
            return;
        }

        // 4. Verificar envío creado
        if ($order->hasShipment()) {
            $shipment = $order->shipment()->first();
            $this->info("✅ Envío creado: #{$shipment->getKey()}");
            $this->info("   Estado del envío: {$shipment->status->value}");
            $this->info("   Repartidor asignado: {$shipment->getAttribute('delivery_driver_id')}");
        } else {
            $this->error("❌ No se creó el envío");
        }

        // 5. Simular cambio a "En Camino"
        $controller = new OrderController($shipmentService);
        $request = new Request();

        $response = $controller->markAsInTransit($request, $order);
        $responseData = $response->getData(true);

        if ($responseData['success']) {
            $order->refresh();
            $this->info("✅ Orden marcada como 'En Camino'");
            $this->info("   Estado de orden: {$order->status} (ENVIADO)");

            if ($order->hasShipment()) {
                $shipment = $order->shipment()->first();
                $shipment->refresh();
                $this->info("   Estado del envío: {$shipment->status->value} (IN_TRANSIT)");
            }
        } else {
            $this->error("❌ Error al marcar como 'En Camino': " . $responseData['message']);
        }

        $this->newLine();
        $this->info('🎉 FLUJO COMPLETADO EXITOSAMENTE');
    }
}
