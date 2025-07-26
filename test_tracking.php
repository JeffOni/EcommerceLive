<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Obtener la orden creada
$order = App\Models\Order::find(22);
if (!$order) {
    echo "Orden no encontrada.\n";
    exit;
}

// Simular el OrderTrackingController
$controller = new App\Http\Controllers\OrderTrackingController();

// Usar reflexión para acceder a métodos privados
$reflectionClass = new ReflectionClass($controller);

// Probar calculateOrderProgress
$progressMethod = $reflectionClass->getMethod('calculateOrderProgress');
$progressMethod->setAccessible(true);
$progress = $progressMethod->invoke($controller, $order);

echo "Order ID: " . $order->id . "\n";
echo "Status: " . $order->status . "\n";
echo "Status text: " . $order->status_text . "\n";
echo "Progress: " . $progress . "%\n";

// Probar getOrderTimeline
try {
    $timelineMethod = $reflectionClass->getMethod('getOrderTimeline');
    $timelineMethod->setAccessible(true);
    $timeline = $timelineMethod->invoke($controller, $order);
    
    echo "\nTimeline:\n";
    foreach ($timeline as $item) {
        echo "- " . $item['title'] . " (" . $item['status'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error en timeline: " . $e->getMessage() . "\n";
}

// Probar status method
try {
    // Simular autenticación
    Auth::loginUsingId($order->user_id);
    
    $response = $controller->status($order->id);
    $data = $response->getData(true);
    
    echo "\nAPI Response:\n";
    echo "Success: " . ($data['success'] ? 'true' : 'false') . "\n";
    echo "Order status: " . $data['order']['status'] . "\n";
    echo "Status text: " . $data['order']['status_text'] . "\n";
    echo "Progress: " . $data['progress'] . "%\n";
    
} catch (Exception $e) {
    echo "Error en API: " . $e->getMessage() . "\n";
}
