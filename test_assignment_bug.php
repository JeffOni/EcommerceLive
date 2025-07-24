<?php

require_once 'vendor/autoload.php';

// Bootstrapear Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Services\ShipmentService;

echo "🧪 TEST: Reproducir bug de asignación automática\n";
echo "================================================\n\n";

// Buscar una orden en estado "Preparando" (3)
$order = Order::where('status', 3)->first();

if (!$order) {
    echo "❌ No hay órdenes en estado 'Preparando' para probar\n";
    exit;
}

echo "✅ Orden encontrada: #{$order->id}\n";
echo "   Estado inicial: {$order->status}\n";
echo "   Estado nombre: " . $order->status->label() . "\n\n";

// Buscar un repartidor activo
$driver = DeliveryDriver::where('is_active', true)->first();

if (!$driver) {
    echo "❌ No hay repartidores activos para probar\n";
    exit;
}

echo "✅ Repartidor encontrado: {$driver->name}\n\n";

// Obtener el servicio de envíos
$shipmentService = app(ShipmentService::class);

echo "🚀 Asignando repartidor...\n";

try {
    // Simular la asignación como lo hace el controlador
    if ($order->hasShipment()) {
        $shipment = $order->shipment()->first();
        echo "   Orden ya tiene envío: {$shipment->tracking_number}\n";
        $success = $shipmentService->assignDriverToShipment($shipment, $driver);
    } else {
        echo "   Creando envío con repartidor...\n";
        $shipment = $shipmentService->createShipmentForOrderWithDriver($order, $driver);
        $success = $shipment ? true : false;
    }

    if ($success) {
        // Actualizar el estado de la orden a "Asignado" (4) como hace el controlador
        $order->update(['status' => 4]);

        echo "✅ Asignación exitosa!\n";

        // Recargar la orden para ver el estado actual
        $order->refresh();

        echo "\n📊 RESULTADO:\n";
        echo "   Estado final: {$order->status}\n";
        echo "   Estado nombre: " . $order->status->label() . "\n";

        // Verificar si cambió automáticamente
        if ($order->status != 4) {
            echo "🚨 BUG DETECTADO! El estado NO es 4 (Asignado)\n";
            echo "   Se cambió automáticamente a: {$order->status}\n";
        } else {
            echo "✅ Estado correcto: Orden quedó en 'Asignado'\n";
        }

    } else {
        echo "❌ Error en la asignación\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DEL TEST ===\n";
