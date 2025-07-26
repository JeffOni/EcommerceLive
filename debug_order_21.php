<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simular la autenticación como en el controlador
$user = App\Models\User::find(2); // Usuario correcto para la orden 21
if (!$user) {
    echo "Usuario no encontrado\n";
    exit;
}

Auth::login($user);

try {
    // Buscar la orden 21 específicamente
    $order = App\Models\Order::with(['payment', 'shipment.deliveryDriver'])
        ->where('user_id', $user->id)
        ->findOrFail(21);
    
    echo "Orden encontrada:\n";
    echo "ID: " . $order->id . "\n";
    echo "User ID: " . $order->user_id . "\n";
    echo "Status: " . $order->status . "\n";
    
    // Probar acceso a status_text
    try {
        $statusText = $order->status_text;
        echo "Status text: " . $statusText . "\n";
    } catch (Exception $e) {
        echo "ERROR en status_text: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }
    
    // Probar acceso a payment
    try {
        $payment = $order->payment;
        echo "Payment: " . ($payment ? "Exists" : "Null") . "\n";
        if ($payment) {
            echo "Payment status: " . ($payment->status ?? 'N/A') . "\n";
        }
    } catch (Exception $e) {
        echo "ERROR en payment: " . $e->getMessage() . "\n";
    }
    
    // Probar acceso a shipment
    try {
        $shipment = $order->shipment;
        echo "Shipment: " . ($shipment ? "Exists" : "Null") . "\n";
        if ($shipment) {
            echo "Tracking number: " . ($shipment->tracking_number ?? 'N/A') . "\n";
            echo "Delivery driver: " . ($shipment->deliveryDriver ? "Exists" : "Null") . "\n";
            if ($shipment->deliveryDriver) {
                echo "Driver name: " . $shipment->deliveryDriver->name . "\n";
            }
        }
    } catch (Exception $e) {
        echo "ERROR en shipment: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }
    
    // Probar los métodos del controller
    try {
        $controller = new App\Http\Controllers\OrderTrackingController();
        $reflectionClass = new ReflectionClass($controller);
        
        // Probar calculateOrderProgress
        $progressMethod = $reflectionClass->getMethod('calculateOrderProgress');
        $progressMethod->setAccessible(true);
        $progress = $progressMethod->invoke($controller, $order);
        echo "Progress: " . $progress . "%\n";
        
        // Probar getOrderTimeline
        $timelineMethod = $reflectionClass->getMethod('getOrderTimeline');
        $timelineMethod->setAccessible(true);
        $timeline = $timelineMethod->invoke($controller, $order);
        echo "Timeline items: " . count($timeline) . "\n";
        
    } catch (Exception $e) {
        echo "ERROR en controller methods: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }
    
    // Probar construir la response completa
    try {
        $response = [
            'success' => true,
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'status_text' => $order->status_text,
                'payment_status' => $order->payment?->status ?? 'pending',
                'shipment' => $order->shipment ? [
                    'tracking_number' => $order->shipment->tracking_number,
                    'estimated_delivery' => $order->shipment->estimated_delivery_date?->format('d/m/Y'),
                    'driver' => $order->shipment->deliveryDriver ? [
                        'name' => $order->shipment->deliveryDriver->name,
                        'phone' => $order->shipment->deliveryDriver->phone,
                        'vehicle' => $order->shipment->deliveryDriver->vehicle_info
                    ] : null
                ] : null
            ],
            'progress' => $progress ?? 0,
            'timeline' => $timeline ?? []
        ];
        
        echo "Response construida exitosamente\n";
        echo "JSON length: " . strlen(json_encode($response)) . "\n";
        
    } catch (Exception $e) {
        echo "ERROR construyendo response: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }

} catch (Exception $e) {
    echo "ERROR general: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
