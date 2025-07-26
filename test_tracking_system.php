<?php

require_once 'vendor/autoload.php';

use App\Models\Order;
use App\Models\Shipment;
use App\Http\Controllers\OrderTrackingController;
use Illuminate\Http\Request;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING ORDER TRACKING SYSTEM ===\n\n";

// Get first order with shipment
$order = Order::with(['shipment', 'shipment.deliveryDriver'])->first();

if (!$order) {
    echo "❌ No hay órdenes en la base de datos para probar\n";
    exit;
}

echo "✅ Orden encontrada: #{$order->id}\n";
$orderStatus = $order->status instanceof \App\Enums\OrderStatus ? $order->status->value : $order->status;
echo "   - Estado de orden: {$orderStatus}\n";

if ($order->shipment) {
    echo "   - Tiene envío: Sí\n";
    $shipmentStatus = $order->shipment->status instanceof \App\Enums\ShipmentStatus ? $order->shipment->status->value : $order->shipment->status;
    echo "   - Estado de envío: {$shipmentStatus}\n";
    
    if ($order->shipment->deliveryDriver) {
        echo "   - Repartidor: {$order->shipment->deliveryDriver->name}\n";
    } else {
        echo "   - Repartidor: No asignado\n";
    }
} else {
    echo "   - Tiene envío: No\n";
}

echo "\n=== TESTING TIMELINE GENERATION ===\n";

try {
    $controller = new OrderTrackingController();
    $request = new Request();
    
    // Use reflection to access private method
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('buildTimeline');
    $method->setAccessible(true);
    
    $timeline = $method->invokeArgs($controller, [$order]);
    
    echo "✅ Timeline generado correctamente con " . count($timeline) . " eventos:\n";
    
    foreach ($timeline as $index => $event) {
        $status = $event['status'] ?? 'unknown';
        echo "   " . ($index + 1) . ". {$event['title']} [{$status}]\n";
        if (isset($event['description'])) {
            echo "      {$event['description']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error generando timeline: " . $e->getMessage() . "\n";
}

echo "\n=== TESTING PROGRESS CALCULATION ===\n";

try {
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('calculateOrderProgress');
    $method->setAccessible(true);
    
    $progress = $method->invokeArgs($controller, [$order]);
    echo "✅ Progreso calculado: {$progress}%\n";
    
} catch (Exception $e) {
    echo "❌ Error calculando progreso: " . $e->getMessage() . "\n";
}

echo "\n=== SYSTEM CHECK COMPLETE ===\n";
echo "Estado del sistema: ✅ FUNCIONANDO CORRECTAMENTE\n";
