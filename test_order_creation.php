<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DE CREACIÓN DE ORDEN DESDE PAGO ===\n";

// Obtener un pago aprobado sin orden
$payment = \App\Models\Payment::where('status', 'approved')
    ->whereNull('order_id')
    ->where('id', 9) // Usar específicamente el pago ID 9
    ->first();

if (!$payment) {
    echo "No hay pagos aprobados sin orden\n";
    exit;
}

echo "Payment ID: {$payment->id}\n";
echo "Método: {$payment->payment_method}\n";
echo "Monto: \${$payment->amount}\n";
echo "Cart Data: " . (is_array($payment->cart_data) ? count($payment->cart_data) . ' items' : 'N/A') . "\n";

if ($payment->cart_data) {
    echo "\n=== CART DATA ===\n";
    print_r($payment->cart_data);
    
    // Calcular subtotal
    $cartData = $payment->cart_data;
    $subtotal = collect($cartData)->sum('subtotal');
    echo "\nSubtotal calculado: \${$subtotal}\n";
    $shipping = 5.00;
    echo "Shipping: \${$shipping}\n";
    echo "Total esperado: \$" . ($subtotal + $shipping) . "\n";
    echo "Total del pago: \${$payment->amount}\n";
    
    // Crear la orden manualmente
    echo "\n=== CREANDO ORDEN ===\n";
    
    $shippingAddress = [
        'address' => 'Dirección pendiente de confirmación',
        'city' => 'Pendiente',
        'province' => 'Pendiente',
        'phone' => $payment->user->phone ?? 'Pendiente',
        'notes' => 'Pago verificado - Pendiente dirección'
    ];

    $paymentMethod = match($payment->payment_method) {
        'bank_transfer' => 0,
        'qr_deuna' => 3,
        'cash_on_delivery' => 2,
        default => 0
    };
    
    try {
        $order = \App\Models\Order::create([
            'user_id' => $payment->user_id,
            'content' => $cartData,
            'shipping_address' => $shippingAddress,
            'payment_method' => $paymentMethod,
            'total' => $payment->amount,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'status' => 2, // Pago verificado
            'notes' => 'Orden creada desde pago verificado - ' . ucfirst($payment->payment_method)
        ]);
        
        echo "✅ Orden creada: ID {$order->id}\n";
        echo "   Order Number: {$order->order_number}\n";
        echo "   Status: {$order->status}\n";
        echo "   Total: \${$order->total}\n";
        
        // Vincular pago con orden
        $payment->update(['order_id' => $order->id]);
        $order->update(['payment_id' => $payment->id]);
        
        echo "✅ Pago vinculado a orden\n";
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "El pago no tiene cart_data\n";
}
