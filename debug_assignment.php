<?php

require_once 'vendor/autoload.php';

// Bootstrapear Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Models\Shipment;
use App\Services\ShipmentService;

echo "🔍 DIAGNÓSTICO DEL BUG DE ASIGNACIÓN\n";
echo "=====================================\n\n";

// Crear una orden de prueba en estado "Preparando" (3)
$order = new Order();
$order->user_id = 1;
$order->status = 3; // Preparando
$order->total = 25.50;
$order->content = [['id' => 1, 'name' => 'Test Product', 'price' => 25.50, 'quantity' => 1]];
$order->shipping_address = [
    'province' => 'Pichincha',
    'canton' => 'Quito',
    'address' => 'Test Address 123'
];
$order->save();

echo "✅ Orden de prueba creada: ID #{$order->id}\n";
echo "   Estado inicial: {$order->status}\n\n";

// Obtener un repartidor de prueba
$driver = DeliveryDriver::first();
if (!$driver) {
    echo "❌ No hay repartidores disponibles\n";
    exit;
}

echo "✅ Repartidor encontrado: {$driver->id}\n\n";

// Simular el proceso de asignación paso a paso
echo "🚀 PASO 1: Verificando estado antes de asignación\n";
$order->refresh();
echo "   Estado actual: {$order->status}\n\n";

echo "🚀 PASO 2: Creando envío con repartidor\n";
$shipmentService = app(ShipmentService::class);

try {
    $shipment = $shipmentService->createShipmentForOrderWithDriver($order, $driver);

    echo "   ✅ Envío creado: {$shipment->id}\n";
    echo "   Estado del envío: {$shipment->status->value}\n\n";

    echo "🚀 PASO 3: Verificando estado después de crear envío\n";
    $order->refresh();
    echo "   Estado actual de la orden: {$order->status}\n\n";

    echo "🚀 PASO 4: Actualizando orden a estado 'Asignado' (4)\n";
    $order->update(['status' => 4]);

    echo "🚀 PASO 5: Verificando estado final\n";
    $order->refresh();
    echo "   Estado final: {$order->status}\n\n";

    if ($order->status != 4) {
        echo "🚨 BUG CONFIRMADO!\n";
        echo "   ❌ El estado NO es 4 (Asignado)\n";
        echo "   ❌ Cambió automáticamente a: {$order->status}\n\n";

        echo "🔍 Investigando causa...\n";

        // Verificar si hay algún observer o evento
        echo "   - Checking for observers...\n";
        echo "   - Checking for automatic processes...\n";

    } else {
        echo "✅ Estado correcto mantenido\n";
    }

} catch (Exception $e) {
    echo "❌ Error durante el proceso: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n";
}

// Limpiar - eliminar orden de prueba
$order->delete();
echo "\n🧹 Orden de prueba eliminada\n";

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
