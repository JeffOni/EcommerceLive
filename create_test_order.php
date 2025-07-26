<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CREANDO ORDEN DE PRUEBA PARA STOCK AUTOMÁTICO ===\n\n";

// 1. Buscar usuario admin
$user = User::where('email', 'Admin@example.com')->first();
if (!$user) {
    echo "❌ Usuario admin no encontrado\n";
    exit;
}

// 2. Buscar algunos productos con stock
$products = Product::where('stock', '>', 5)->limit(2)->get();
if ($products->count() == 0) {
    echo "❌ No hay productos con stock disponible\n";
    exit;
}

echo "1. CREANDO ORDEN DE PRUEBA:\n";
echo "   Usuario: {$user->email}\n";
echo "   Productos seleccionados:\n";

// 3. Crear contenido de la orden
$content = [];
$total = 0;

foreach ($products as $product) {
    $quantity = 2; // Cantidad de prueba
    $subtotal = $product->price * $quantity;
    $total += $subtotal;

    $content[] = [
        'id' => $product->id,
        'product_id' => $product->id,
        'variant_id' => null, // Para productos simples
        'name' => $product->name,
        'price' => $product->price,
        'quantity' => $quantity,
        'subtotal' => $subtotal,
        'sku' => $product->sku,
        'features' => [],
    ];

    echo "     - {$product->name} (SKU: {$product->sku})\n";
    echo "       Stock actual: {$product->stock}\n";
    echo "       Cantidad pedida: {$quantity}\n";
    echo "       Precio: \${$product->price}\n";
}

$shipping = 5.00;
$total += $shipping;

// 4. Crear dirección de prueba
$shippingAddress = [
    'address' => 'Calle de Prueba 123',
    'reference' => 'Edificio de prueba',
    'province' => 'Pichincha',
    'canton' => 'Quito',
    'parish' => 'Centro',
    'postal_code' => '170150',
    'notes' => 'Orden de prueba para testing',
];

// 5. Crear la orden
$order = Order::create([
    'user_id' => $user->id,
    'content' => $content,
    'total' => $total,
    'status' => 2, // Pago verificado
    'shipping_address' => $shippingAddress,
    'subtotal' => $total - $shipping,
    'shipping_cost' => $shipping,
    'notes' => 'Orden de prueba para sistema de stock automático'
]);

echo "\n2. ORDEN CREADA EXITOSAMENTE:\n";
echo "   ID: {$order->id}\n";
echo "   Estado: {$order->status} (Pago verificado)\n";
echo "   Total: \$" . number_format($order->total, 2) . "\n";

// 6. Crear el pago asociado
$payment = Payment::create([
    'user_id' => $user->id,
    'order_id' => $order->id,
    'payment_method' => 'test',
    'amount' => $order->total,
    'status' => 'confirmed',
    'cart_data' => $content,
]);

echo "   Payment ID: {$payment->id}\n";

echo "\n3. STOCK ANTES DEL CAMBIO A 'EN CAMINO':\n";
foreach ($products as $product) {
    $product->refresh(); // Recargar desde la base de datos
    echo "   {$product->sku}: {$product->stock} unidades\n";
}

echo "\n4. CAMBIANDO ESTADO A 'EN CAMINO' (5)...\n";
$order->status = 5; // En Camino - esto debería activar la reducción de stock
$order->save();

echo "✅ Estado cambiado a 'En Camino'\n";

echo "\n5. STOCK DESPUÉS DEL CAMBIO:\n";
foreach ($products as $product) {
    $product->refresh(); // Recargar desde la base de datos
    echo "   {$product->sku}: {$product->stock} unidades (debería haberse reducido en 2)\n";
}

echo "\n✅ ORDEN DE PRUEBA CREADA Y PROCESADA\n";
echo "Orden ID: {$order->id} lista para testing\n";

echo "\n📋 PRÓXIMOS PASOS:\n";
echo "1. Verifica que el stock se haya reducido correctamente\n";
echo "2. Prueba el sistema de checkout con productos reales\n";
echo "3. Verifica que el contador del carrito se actualice después de compras\n";

echo "\n=== FIN CREACIÓN ORDEN DE PRUEBA ===\n";
