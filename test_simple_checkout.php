<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Gloudemans\Shoppingcart\Facades\Cart;

echo "=== PRUEBA SIMPLE DE CHECKOUT ===\n\n";

// 1. Verificar si hay elementos en el carrito shopping
echo "1. Verificando carrito 'shopping'...\n";
Cart::instance('shopping');
$cartCount = Cart::count();
$cartContent = Cart::content();

echo "   - Items en carrito: {$cartCount}\n";
echo "   - Subtotal: " . Cart::subtotal() . "\n";
echo "   - Total: " . Cart::total() . "\n";

if ($cartCount > 0) {
    echo "   - Contenido del carrito:\n";
    foreach ($cartContent as $item) {
        echo "     * {$item->name} x{$item->qty} = \${$item->total}\n";
    }
} else {
    echo "   - Carrito vacío\n";
}

// 2. Verificar configuración de carrito
echo "\n2. Verificando configuración del carrito...\n";
$config = config('cart');
if ($config) {
    echo "   - Configuración del carrito encontrada:\n";
    foreach ($config as $key => $value) {
        if (is_array($value)) {
            echo "     * {$key}: " . json_encode($value) . "\n";
        } else {
            echo "     * {$key}: {$value}\n";
        }
    }
} else {
    echo "   - No se encontró configuración específica del carrito\n";
}

// 3. Verificar último pedido para ver el flujo
echo "\n3. Verificando último pedido...\n";
try {
    $lastOrder = App\Models\Order::with('user')->latest()->first();
    if ($lastOrder) {
        echo "   - Último pedido: #{$lastOrder->id}\n";
        echo "   - Usuario: {$lastOrder->user->name}\n";
        echo "   - Estado: {$lastOrder->status->value}\n";
        echo "   - Método de pago: {$lastOrder->payment_method->value}\n";
        echo "   - Total: \${$lastOrder->total}\n";
        echo "   - Fecha: {$lastOrder->created_at->format('Y-m-d H:i:s')}\n";
    } else {
        echo "   - No hay pedidos en el sistema\n";
    }
} catch (Exception $e) {
    echo "   - Error al consultar pedidos: " . $e->getMessage() . "\n";
}

// 4. Verificar rutas del checkout
echo "\n4. Verificando rutas del checkout...\n";
try {
    $routes = [
        'checkout.index',
        'checkout.store',
        'checkout.transfer-payment',
        'checkout.qr-payment',
        'checkout.thank-you'
    ];

    foreach ($routes as $routeName) {
        try {
            $url = route($routeName);
            echo "   - {$routeName}: {$url} ✓\n";
        } catch (Exception $e) {
            echo "   - {$routeName}: NO EXISTE ✗\n";
        }
    }
} catch (Exception $e) {
    echo "   - Error al verificar rutas: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE PRUEBA ===\n";
