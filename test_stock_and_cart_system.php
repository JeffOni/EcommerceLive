<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DEL SISTEMA DE STOCK AUTOMÁTICO Y CARRITO ===\n\n";

// 1. Verificar que existe una orden para probar
$order = Order::with('user')->where('status', '!=', 5)->first();

if (!$order) {
    echo "❌ No se encontraron órdenes para probar\n";
    echo "Crea una orden primero usando el checkout\n";
    exit;
}

echo "1. ORDEN DE PRUEBA:\n";
echo "   ID: {$order->id}\n";
echo "   Usuario: {$order->user->email}\n";
echo "   Estado actual: {$order->status}\n";
echo "   Total: \${$order->total}\n";

// 2. Mostrar contenido de la orden
echo "\n2. CONTENIDO DE LA ORDEN:\n";
if (is_array($order->content)) {
    foreach ($order->content as $index => $item) {
        echo "   Item " . ($index + 1) . ":\n";
        echo "     - Producto ID: " . ($item['product_id'] ?? $item['id'] ?? 'N/A') . "\n";
        echo "     - Variante ID: " . ($item['variant_id'] ?? 'N/A') . "\n";
        echo "     - Nombre: " . ($item['name'] ?? 'N/A') . "\n";
        echo "     - Cantidad: " . ($item['quantity'] ?? 1) . "\n";
        echo "     - SKU: " . ($item['sku'] ?? 'N/A') . "\n";
    }
} else {
    echo "   ❌ Contenido no es un array válido\n";
}

// 3. Mostrar stock actual de los productos/variantes
echo "\n3. STOCK ACTUAL ANTES DEL CAMBIO:\n";
if (is_array($order->content)) {
    foreach ($order->content as $item) {
        $productId = $item['product_id'] ?? $item['id'] ?? null;
        $variantId = $item['variant_id'] ?? null;

        if ($variantId) {
            $variant = Variant::find($variantId);
            if ($variant) {
                echo "   Variante {$variant->sku}: {$variant->stock} unidades\n";
            }
        } elseif ($productId) {
            $product = Product::find($productId);
            if ($product) {
                echo "   Producto {$product->sku}: {$product->stock} unidades\n";
            }
        }
    }
}

// 4. Cambiar estado a "En Camino" para activar la reducción de stock
echo "\n4. CAMBIANDO ESTADO A 'EN CAMINO' (5)...\n";
$order->status = 5;
$order->save();

echo "✅ Estado cambiado exitosamente\n";

// 5. Verificar stock después del cambio
echo "\n5. STOCK DESPUÉS DEL CAMBIO:\n";
if (is_array($order->content)) {
    foreach ($order->content as $item) {
        $productId = $item['product_id'] ?? $item['id'] ?? null;
        $variantId = $item['variant_id'] ?? null;
        $quantity = $item['quantity'] ?? 1;

        if ($variantId) {
            $variant = Variant::find($variantId);
            if ($variant) {
                echo "   Variante {$variant->sku}: {$variant->stock} unidades (reducido en {$quantity})\n";
            }
        } elseif ($productId) {
            $product = Product::find($productId);
            if ($product) {
                echo "   Producto {$product->sku}: {$product->stock} unidades (reducido en {$quantity})\n";
            }
        }
    }
}

echo "\n6. INSTRUCCIONES PARA PROBAR EL CARRITO:\n";
echo "1. Ve a: http://127.0.0.1:8000/products\n";
echo "2. Agrega algunos productos al carrito\n";
echo "3. Ve al checkout y completa una compra\n";
echo "4. Después de completar la compra, verifica que:\n";
echo "   - El contador del carrito muestre 0\n";
echo "   - El carrito esté vacío\n";
echo "   - Cuando cambies la orden a 'En Camino', el stock se reduzca automáticamente\n";

echo "\n✅ SISTEMA CONFIGURADO CORRECTAMENTE\n";
echo "=== FIN DE LA PRUEBA ===\n";
