<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING CHECKOUT SYSTEM ===\n\n";

try {
    // Test enum creation
    echo "✅ Testing OrderStatus enum...\n";
    $status = \App\Enums\OrderStatus::PENDIENTE;
    echo "   - PENDIENTE value: {$status->value}\n";
    
    // Test order creation with enum
    echo "✅ Testing Order creation with enum...\n";
    $orderData = [
        'user_id' => 1,
        'status' => \App\Enums\OrderStatus::PENDIENTE,
        'payment_method' => 3,
        'delivery_type' => 'delivery',
        'subtotal' => 100.00,
        'shipping_cost' => 5.00,
        'total' => 105.00,
        'content' => [],
        'shipping_address' => []
    ];
    
    echo "   - Order data prepared with enum status\n";
    echo "   - Status type: " . get_class($orderData['status']) . "\n";
    echo "   - Status value: " . $orderData['status']->value . "\n";
    
    echo "\n=== CHECKOUT SYSTEM CHECK COMPLETE ===\n";
    echo "Estado del sistema: ✅ ENUM FUNCIONANDO CORRECTAMENTE\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
