<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ÓRDENES EXISTENTES ===\n";
$orders = App\Models\Order::latest()->take(5)->get(['id', 'user_id', 'status', 'order_number']);

foreach ($orders as $order) {
    echo "ID: {$order->id}, User: {$order->user_id}, Status: {$order->status}, Order: {$order->order_number}\n";
}

// Usar la orden más reciente para debug
$latestOrder = App\Models\Order::latest()->first();
if (!$latestOrder) {
    echo "No hay órdenes\n";
    exit;
}

echo "\n=== DEBUGGEANDO ORDEN {$latestOrder->id} ===\n";

// Simular autenticación con el usuario correcto
$user = App\Models\User::find($latestOrder->user_id);
Auth::login($user);

try {
    // Cargar la orden como lo hace el controller
    $order = App\Models\Order::with(['payment', 'shipment.deliveryDriver'])
        ->where('user_id', $user->id)
        ->findOrFail($latestOrder->id);
    
    echo "✅ Orden cargada correctamente\n";
    echo "Status: {$order->status}\n";
    
    // Probar status_text
    try {
        $statusText = $order->status_text;
        echo "✅ Status text: {$statusText}\n";
    } catch (Exception $e) {
        echo "❌ ERROR en status_text: " . $e->getMessage() . "\n";
    }
    
    // Probar payment
    try {
        $payment = $order->payment;
        $paymentStatus = $payment?->status ?? 'pending';
        echo "✅ Payment status: {$paymentStatus}\n";
    } catch (Exception $e) {
        echo "❌ ERROR en payment: " . $e->getMessage() . "\n";
    }
    
    // Probar shipment
    try {
        $shipment = $order->shipment;
        if ($shipment) {
            echo "✅ Shipment exists\n";
            $driver = $shipment->deliveryDriver;
            if ($driver) {
                echo "✅ Driver: {$driver->name}\n";
            } else {
                echo "⚠️ No driver assigned\n";
            }
        } else {
            echo "⚠️ No shipment\n";
        }
    } catch (Exception $e) {
        echo "❌ ERROR en shipment: " . $e->getMessage() . "\n";
    }
    
    // Probar los métodos del controller
    try {
        $controller = new App\Http\Controllers\OrderTrackingController();
        $reflectionClass = new ReflectionClass($controller);
        
        $progressMethod = $reflectionClass->getMethod('calculateOrderProgress');
        $progressMethod->setAccessible(true);
        $progress = $progressMethod->invoke($controller, $order);
        echo "✅ Progress: {$progress}%\n";
        
        $timelineMethod = $reflectionClass->getMethod('getOrderTimeline');
        $timelineMethod->setAccessible(true);
        $timeline = $timelineMethod->invoke($controller, $order);
        echo "✅ Timeline: " . count($timeline) . " items\n";
        
    } catch (Exception $e) {
        echo "❌ ERROR en métodos: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR general: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
