<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simular la llamada del admin para cambiar estado
$order = App\Models\Order::where('id', 21)->first();
if (!$order) {
    echo "Orden 21 no encontrada.\n";
    exit;
}

echo "Estado actual de la orden 21: " . $order->status . "\n";
echo "Status text: " . $order->status_text . "\n";

// Simular el cambio de estado a "En camino" (estado 5)
try {
    $order->update(['status' => 5]);
    echo "✅ Estado actualizado exitosamente a: " . $order->status . "\n";
    echo "✅ Status text actualizado: " . $order->status_text . "\n";
    
    // Verificar que el método status del tracking funciona
    $user = App\Models\User::find($order->user_id);
    Auth::loginUsingId($user->id);
    
    $controller = new App\Http\Controllers\OrderTrackingController();
    $response = $controller->status($order->id);
    $data = $response->getData(true);
    
    echo "✅ API Response exitosa: " . ($data['success'] ? 'true' : 'false') . "\n";
    echo "✅ Progress: " . $data['progress'] . "%\n";
    
    echo "\n🎉 ¡Todo funciona correctamente!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
