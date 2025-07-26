<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simular lo que hace el admin al cambiar estado
echo "=== SIMULANDO CAMBIO DE ESTADO ADMIN ===\n";

// Buscar una orden en estado 4 (Asignado)
$order = App\Models\Order::where('status', 4)->first();
if (!$order) {
    echo "No hay órdenes en estado 4\n";
    exit;
}

echo "Orden encontrada: ID {$order->id}, Status actual: {$order->status}\n";
echo "Status text actual: {$order->status_text}\n";

// Simular la autenticación del admin
$adminUser = App\Models\User::first(); // Usar cualquier usuario como admin
Auth::login($adminUser);

// Simular la request del admin para cambiar a "En camino" (status 5)
$request = new Illuminate\Http\Request();
$request->merge(['status' => 5]);

try {
    echo "\n=== PROBANDO EL MÉTODO updateStatus ===\n";

    // Crear el controlador con sus dependencias
    $shipmentService = app(\App\Services\ShipmentService::class);
    $controller = new App\Http\Controllers\Admin\OrderController($shipmentService);
    $response = $controller->updateStatus($order, $request);

    // Verificar la respuesta
    $responseData = $response->getData(true);
    if ($responseData['success']) {
        echo "✅ Respuesta exitosa: {$responseData['message']}\n";

        // Recargar la orden para verificar el cambio
        $order->refresh();
        echo "✅ Nuevo status: {$order->status}\n";
        echo "✅ Nuevo status text: {$order->status_text}\n";

    } else {
        echo "❌ Error en respuesta\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== VERIFICANDO SI FUNCIONA LA RUTA DE TRACKING ===\n";

// Simular el usuario correcto para el tracking
$orderUser = App\Models\User::find($order->user_id);
Auth::login($orderUser);

try {
    $trackingController = new App\Http\Controllers\OrderTrackingController();
    $trackingResponse = $trackingController->status($order->id);
    $trackingData = $trackingResponse->getData(true);

    if ($trackingData['success']) {
        echo "✅ Tracking funciona correctamente\n";
        echo "Status: {$trackingData['order']['status']}\n";
        echo "Status text: {$trackingData['order']['status_text']}\n";
        echo "Progress: {$trackingData['progress']}%\n";
    } else {
        echo "❌ Error en tracking\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR en tracking: " . $e->getMessage() . "\n";
}
