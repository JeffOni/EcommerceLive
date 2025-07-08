<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COMPLETANDO VINCULACIONES ===\n";

// Vincular payment 1 con order 2 (ya tiene order_id pero le falta payment_id)
$payment1 = \App\Models\Payment::find(1);
$order2 = \App\Models\Order::find(2);
if ($payment1 && $order2 && $payment1->order_id == 2) {
    $order2->update(['payment_id' => 1]);
    echo "✅ Order 2 vinculada con Payment 1\n";
}

// Vincular payment 9 con order 3 (ya tiene order_id pero le falta payment_id)
$payment9 = \App\Models\Payment::find(9);
$order3 = \App\Models\Order::find(3);
if ($payment9 && $order3 && $payment9->order_id == 3) {
    $order3->update(['payment_id' => 9]);
    echo "✅ Order 3 vinculada con Payment 9\n";
}

// Crear orden para payment 12 (que tiene cart_data)
$payment12 = \App\Models\Payment::find(12);
if ($payment12 && !$payment12->order_id && $payment12->cart_data) {
    echo "Creando orden para Payment 12...\n";
    
    $cartData = $payment12->cart_data;
    $subtotal = collect($cartData)->sum('subtotal');
    $shipping = 5.00;
    
    $shippingAddress = [
        'address' => 'Dirección pendiente de confirmación',
        'city' => 'Pendiente',
        'province' => 'Pendiente',
        'phone' => $payment12->user->phone ?? 'Pendiente',
        'notes' => 'Pago verificado - Pendiente dirección'
    ];

    $order = \App\Models\Order::create([
        'user_id' => $payment12->user_id,
        'content' => $cartData,
        'shipping_address' => $shippingAddress,
        'payment_method' => 0, // bank_transfer
        'total' => $payment12->amount,
        'subtotal' => $subtotal,
        'shipping_cost' => $shipping,
        'status' => 2, // Pago verificado
        'notes' => 'Orden creada desde pago verificado - bank_transfer'
    ]);
    
    // Vincular
    $payment12->update(['order_id' => $order->id]);
    $order->update(['payment_id' => $payment12->id]);
    
    echo "✅ Orden {$order->id} creada y vinculada con Payment 12\n";
}

echo "\n=== ESTADO FINAL ===\n";
echo "Órdenes totales: " . \App\Models\Order::count() . "\n";
echo "Órdenes con status 2 (Pago Verificado): " . \App\Models\Order::where('status', 2)->count() . "\n";
echo "Pagos aprobados: " . \App\Models\Payment::where('status', 'approved')->count() . "\n";
echo "Pagos vinculados a órdenes: " . \App\Models\Payment::where('status', 'approved')->whereNotNull('order_id')->count() . "\n";
