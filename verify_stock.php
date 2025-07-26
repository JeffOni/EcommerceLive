<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== VERIFICACIÓN DE STOCK DESPUÉS DE REDUCCIÓN ===\n";

$products = Product::whereIn('id', [1, 2])->get(['id', 'sku', 'name', 'stock']);

foreach ($products as $product) {
    echo "Producto ID {$product->id}: {$product->sku} - {$product->name}\n";
    echo "Stock actual: {$product->stock}\n";
    echo "---\n";
}

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
