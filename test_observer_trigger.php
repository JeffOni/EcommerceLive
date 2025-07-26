<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;

echo "=== TEST OBSERVER TRIGGER ===\n";

// Buscar una orden existente
$order = Order::first();
if (!$order) {
    echo "No hay órdenes en la base de datos.\n";
    exit;
}

echo "Orden encontrada: ID {$order->id}, Status actual: {$order->status}\n";

// Cambiar el estado primero a algo diferente de 5 para que el observer se dispare
if ($order->status == 5) {
    echo "Cambiando estado a 2 primero para poder disparar el observer...\n";
    $order->update(['status' => 2]);
    $order = $order->fresh();
    echo "Estado cambiado a: {$order->status}\n";
}

// Mostrar contenido de la orden antes
echo "\nContenido de la orden ANTES:\n";
$orderContent = $order->content;
if ($orderContent && isset($orderContent['cart'])) {
    foreach ($orderContent['cart'] as $item) {
        echo "- Producto ID: {$item['id']}, Cantidad: {$item['qty']}\n";
        if (isset($item['options']['variant_id']) && $item['options']['variant_id']) {
            $variant = Variant::find($item['options']['variant_id']);
            if ($variant) {
                echo "  Variante ID: {$variant->id}, Stock actual: {$variant->stock}\n";
            }
        } else {
            $product = Product::find($item['id']);
            if ($product) {
                echo "  Producto ID: {$product->id}, Stock actual: {$product->stock}\n";
            }
        }
    }
}

// Actualizar el estado a "En Camino" (5) - esto debería disparar el observer
echo "\n=== ACTUALIZANDO ESTADO A 'EN CAMINO' (5) ===\n";
$order->update(['status' => 5]);

echo "Estado actualizado a: {$order->fresh()->status}\n";

// Verificar stock después
echo "\nContenido de la orden DESPUÉS:\n";
if ($orderContent && isset($orderContent['cart'])) {
    foreach ($orderContent['cart'] as $item) {
        echo "- Producto ID: {$item['id']}, Cantidad: {$item['qty']}\n";
        if (isset($item['options']['variant_id']) && $item['options']['variant_id']) {
            $variant = Variant::find($item['options']['variant_id']);
            if ($variant) {
                echo "  Variante ID: {$variant->id}, Stock NUEVO: {$variant->stock}\n";
            }
        } else {
            $product = Product::find($item['id']);
            if ($product) {
                echo "  Producto ID: {$product->id}, Stock NUEVO: {$product->stock}\n";
            }
        }
    }
}

echo "\nTest completado. Revisar logs en storage/logs/laravel.log\n";
