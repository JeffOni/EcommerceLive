<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CONFIGURANDO OFERTA PARA PRUEBAS ===\n\n";

try {
    // Buscar un producto con variantes
    $product = \App\Models\Product::whereHas('variants')->first();
    
    if (!$product) {
        echo "❌ No se encontró producto con variantes\n";
        exit;
    }
    
    echo "Producto encontrado: {$product->name}\n";
    echo "Precio base: $" . number_format($product->price, 2) . "\n";
    
    // Crear una oferta del 15%
    $product->update([
        'is_on_offer' => true,
        'offer_percentage' => 15,
        'offer_starts_at' => now(),
        'offer_ends_at' => now()->addDays(7),
        'offer_name' => 'Oferta de prueba 15% descuento'
    ]);
    
    // Refrescar el producto para obtener los nuevos valores
    $product = $product->fresh();
    
    echo "✅ Oferta aplicada: 15% de descuento\n";
    echo "Precio con oferta: $" . number_format($product->current_price, 2) . "\n\n";
    
    echo "Verificando variantes:\n";
    foreach ($product->variants as $variant) {
        echo "- Variante #{$variant->id}:\n";
        echo "  * Precio efectivo: $" . number_format($variant->getEffectivePrice(), 2) . "\n";
        echo "  * Precio con oferta: $" . number_format($variant->current_price, 2) . "\n";
        echo "  * Stock: {$variant->stock}\n\n";
    }
    
    echo "🛒 Ahora puedes probar agregando este producto al carrito desde la web\n";
    echo "📝 URL del producto: /products/{$product->slug}\n";
    
    echo "\n=== CONFIGURACIÓN COMPLETADA ===\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . " línea " . $e->getLine() . "\n";
}
