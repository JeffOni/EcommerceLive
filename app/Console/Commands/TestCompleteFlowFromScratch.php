<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Http\Request;

class TestCompleteFlowFromScratch extends Command
{
    protected $signature = 'test:complete-flow-from-scratch';
    protected $description = 'Probar el flujo completo desde la creación de orden hasta asignación de repartidor';

    public function handle()
    {
        $this->info('🚀 PROBANDO FLUJO COMPLETO DESDE CERO');
        $this->newLine();

        // PASO 1: Crear orden como lo hace el checkout
        $this->info('📋 PASO 1: Creando orden como en el checkout...');

        $order = Order::create([
            'user_id' => 1,
            'status' => 1, // PENDIENTE
            'payment_method' => \App\Enums\PaymentMethod::EFECTIVO->value,
            'delivery_type' => 'delivery',
            'subtotal' => 100.00,
            'shipping_cost' => 5.00,
            'total' => 105.00,
            'content' => json_encode([
                ['name' => 'Producto Test', 'price' => 100, 'quantity' => 1]
            ]),
            'shipping_address' => json_encode([
                'province' => 'Santa Cruz',
                'city' => 'Santa Cruz de la Sierra',
                'address' => 'Dirección completa de prueba'
            ])
        ]);

        $this->info("✅ Orden creada: #{$order->getKey()}");
        $this->info("   Estado: {$order->status} (PENDIENTE)");
        $this->info("   Tipo de entrega: {$order->getAttribute('delivery_type')}");
        $this->info("   Total: Bs. " . (float) $order->total);

        // Verificar que NO tenga envío
        if ($order->hasShipment()) {
            $this->error("❌ ERROR: La orden ya tiene un envío (no debería)");
            return;
        } else {
            $this->info("✅ Correcto: La orden NO tiene envío automático");
        }

        $this->newLine();

        // PASO 2: Simular asignación desde el admin
        $this->info('👨‍💼 PASO 2: Asignando repartidor desde el admin...');

        // Buscar repartidor con menos de 5 envíos activos
        $drivers = DeliveryDriver::where('is_active', true)->get();
        $driver = null;

        foreach ($drivers as $d) {
            $activeShipments = \App\Models\Shipment::where('delivery_driver_id', $d->getKey())
                ->whereIn('status', [1, 2, 4])
                ->count();
            if ($activeShipments < 5) {
                $driver = $d;
                break;
            }
        }

        if (!$driver) {
            $this->error("❌ No hay repartidores disponibles (todos tienen 5 envíos activos)");
            return;
        }

        $this->info("Repartidor seleccionado: {$driver->getAttribute('name')}");

        // Simular request HTTP
        $request = new Request();
        $request->merge(['delivery_driver_id' => $driver->getKey()]);

        // Usar el controlador real (sin servicio porque no lo necesita)
        $controller = new OrderController(new \App\Services\ShipmentService());
        $response = $controller->assignDriver($request, $order);
        $responseData = $response->getData(true);

        if ($responseData['success']) {
            $this->info("✅ {$responseData['message']}");

            // PASO 3: Verificar resultados
            $this->newLine();
            $this->info('🔍 PASO 3: Verificando resultados...');

            $order->refresh();
            $this->info("Estado de orden: {$order->status} (esperado: 4 - ASIGNADO)");

            if ($order->hasShipment()) {
                $shipment = $order->shipment()->first();
                $this->info("✅ Envío creado: #{$shipment->getKey()}");
                $this->info("   Estado del envío: {$shipment->status->value} (esperado: 2 - ASSIGNED)");
                $this->info("   Repartidor asignado: {$shipment->getAttribute('delivery_driver_id')}");
                $this->info("   Fecha de asignación: {$shipment->assigned_at->format('Y-m-d H:i:s')}");
                $this->info("   Número de seguimiento: {$shipment->getAttribute('tracking_number')}");
            } else {
                $this->error("❌ ERROR: No se creó el envío");
            }
        } else {
            $this->error("❌ Error en asignación: {$responseData['message']}");
        }

        $this->newLine();
        $this->info('🎯 PRUEBA COMPLETA TERMINADA');
    }
}
