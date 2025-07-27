<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;

echo "=== PRUEBA DE FLUJO DE CHECKOUT ===\n\n";

// 1. Verificar que tenemos productos en stock
echo "1. Verificando productos...\n";
$products = Product::where('stock', '>', 0)->take(3)->get();
foreach ($products as $product) {
    echo "   - {$product->name}: Stock {$product->stock}, Precio \${$product->price}\n";
}

// 2. Verificar usuarios con cart items
echo "\n2. Verificando usuarios con items en carrito...\n";
$usersWithCart = User::whereHas('cartItems')->with('cartItems.product')->take(3)->get();
foreach ($usersWithCart as $user) {
    echo "   - Usuario: {$user->name} ({$user->email})\n";
    foreach ($user->cartItems as $item) {
        echo "     * {$item->product->name} x{$item->quantity}\n";
    }
}

// 3. Verificar últimas órdenes para ver el flujo
echo "\n3. Verificando últimas órdenes...\n";
$recentOrders = Order::with(['user', 'items.product'])
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

foreach ($recentOrders as $order) {
    echo "   - Orden #{$order->id}: {$order->user->name}, Estado: {$order->status->value}, Total: \${$order->total}\n";
    echo "     Método de pago: {$order->payment_method->value}\n";
    if ($order->payment_method->value === 'transfer' && $order->transfer_receipt) {
        echo "     Comprobante: {$order->transfer_receipt}\n";
    }
    echo "     Creada: {$order->created_at}\n\n";
}

// 4. Verificar configuración de provincia
echo "4. Verificando configuración de provincia...\n";
$config = config('cart');
if (isset($config['accepted_provinces'])) {
    echo "   - Provincias aceptadas: " . implode(', ', $config['accepted_provinces']) . "\n";
} else {
    echo "   - No hay configuración de provincias específica\n";
}

// 5. Probar controlador directamente
echo "\n5. Probando respuesta del controlador...\n";
try {
    $controller = new App\Http\Controllers\CheckoutController();
    echo "   - Controlador CheckoutController existe ✓\n";

    // Verificar métodos
    $methods = ['index', 'store', 'transferPayment', 'qrPayment'];
    foreach ($methods as $method) {
        if (method_exists($controller, $method)) {
            echo "   - Método {$method} existe ✓\n";
        } else {
            echo "   - Método {$method} NO existe ✗\n";
        }
    }
} catch (Exception $e) {
    echo "   - Error al instanciar controlador: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE PRUEBA ===\n";
