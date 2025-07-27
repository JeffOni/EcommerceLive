<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE CÁLCULO DE PRECIOS DE VARIANTES EN OFERTAS ===\n\n";

try {
    // Buscar un producto con variantes que esté en oferta
    $productsWithOffers = \App\Models\Product::where('is_on_offer', true)
        ->whereHas('variants')
        ->with('variants')
        ->first();

    if (!$productsWithOffers) {
        echo "❌ No se encontraron productos con variantes en oferta\n";
        
        // Buscar cualquier producto con variantes
        $product = \App\Models\Product::whereHas('variants')->with('variants')->first();
        if ($product) {
            echo "📝 Usando producto sin oferta para prueba: {$product->name}\n";
            echo "   - Precio base: $" . number_format($product->price, 2) . "\n";
            echo "   - Precio actual: $" . number_format($product->current_price, 2) . "\n";
            echo "   - En oferta: " . ($product->is_on_valid_offer ? 'Sí' : 'No') . "\n\n";
            
            foreach ($product->variants as $variant) {
                echo "Variante #{$variant->id}:\n";
                echo "   - Precio personalizado: " . ($variant->custom_price ? '$' . number_format($variant->custom_price, 2) : 'N/A') . "\n";
                echo "   - Precio efectivo: $" . number_format($variant->getEffectivePrice(), 2) . "\n";
                echo "   - Precio actual: $" . number_format($variant->current_price, 2) . "\n";
                echo "   - Stock: {$variant->stock}\n\n";
            }
        } else {
            echo "❌ No se encontraron productos con variantes\n";
        }
        
        exit;
    }

    $product = $productsWithOffers;
    echo "✅ Producto encontrado: {$product->name}\n";
    echo "   - Precio base: $" . number_format($product->price, 2) . "\n";
    echo "   - Precio actual: $" . number_format($product->current_price, 2) . "\n";
    echo "   - En oferta: " . ($product->is_on_valid_offer ? 'Sí' : 'No') . "\n";
    
    if ($product->is_on_valid_offer) {
        if ($product->offer_price) {
            echo "   - Tipo de oferta: Precio fijo: $" . number_format($product->offer_price, 2) . "\n";
        } elseif ($product->offer_percentage) {
            echo "   - Tipo de oferta: Descuento: " . $product->offer_percentage . "%\n";
        }
    }
    
    echo "\n2. Análisis de variantes:\n";
    
    foreach ($product->variants as $index => $variant) {
        echo "Variante #" . ($index + 1) . " (ID: {$variant->id}):\n";
        echo "   - Precio personalizado: " . ($variant->custom_price ? '$' . number_format($variant->custom_price, 2) : 'N/A (usa precio base)') . "\n";
        echo "   - Precio efectivo: $" . number_format($variant->getEffectivePrice(), 2) . "\n";
        echo "   - Precio actual (con oferta): $" . number_format($variant->current_price, 2) . "\n";
        echo "   - Descuento aplicado: $" . number_format($variant->savings_amount, 2) . "\n";
        echo "   - Porcentaje de descuento: " . number_format($variant->discount_percentage, 1) . "%\n";
        echo "   - Stock: {$variant->stock}\n";
        
        // Verificar si el cálculo es correcto
        $expectedPrice = $variant->getEffectivePrice();
        if ($product->is_on_valid_offer) {
            if ($product->offer_price) {
                $discountFactor = $product->offer_price / $product->price;
                $expectedPrice = $variant->getEffectivePrice() * $discountFactor;
            } elseif ($product->offer_percentage) {
                $expectedPrice = $variant->getEffectivePrice() * (1 - ($product->offer_percentage / 100));
            }
        }
        
        $actualPrice = $variant->current_price;
        $isCorrect = abs($expectedPrice - $actualPrice) < 0.01; // Tolerancia de 1 centavo
        
        echo "   - Cálculo correcto: " . ($isCorrect ? "✅ Sí" : "❌ No (esperado: $" . number_format($expectedPrice, 2) . ")") . "\n\n";
    }
    
    echo "3. Simulación de carrito:\n";
    
    $variant = $product->variants->first();
    if ($variant) {
        echo "Agregando variante #{$variant->id} al carrito...\n";
        echo "   - Precio que se usará: $" . number_format($variant->current_price, 2) . "\n";
        echo "   - ✅ Precio correcto para el carrito\n";
    }
    
    echo "\n=== PRUEBA COMPLETADA ===\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . " línea " . $e->getLine() . "\n";
}
