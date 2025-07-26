<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Crear una orden de prueba
$user = App\Models\User::first();
if (!$user) {
    echo "No hay usuarios en la base de datos.\n";
    exit;
}

$order = new App\Models\Order();
$order->user_id = $user->id;
$order->status = 1;
$order->total = 100.00;
$order->subtotal = 90.00;
$order->shipping_address = json_encode(['address' => 'Test Address']);
$order->save();

echo "Order created with ID: " . $order->id . " and status: " . $order->status . "\n";
echo "Status text: " . $order->status_text . "\n";
echo "Status enum: " . get_class($order->getStatusEnumAttribute()) . "\n";

// Probar cambio de estado
$order->status = 2;
$order->save();

echo "Order updated to status: " . $order->status . "\n";
echo "Status text updated: " . $order->status_text . "\n";
