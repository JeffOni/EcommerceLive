<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DE PAGO EN EFECTIVO ===\n";

// Simular login de usuario (user_id = 1)
auth()->loginUsingId(1);

// Limpiar carrito existente
\Gloudemans\Shoppingcart\Facades\Cart::instance('shopping');
\Gloudemans\Shoppingcart\Facades\Cart::destroy();

// Agregar un producto al carrito (necesitamos un producto existente)
$product = \App\Models\Product::first();
if (!$product) {
    echo "❌ No hay productos en la base de datos\n";
    exit;
}

echo "Agregando producto al carrito: {$product->name} (\${$product->price})\n";

\Gloudemans\Shoppingcart\Facades\Cart::add(
    $product->id,
    $product->name,
    1, // cantidad
    $product->price
);

echo "Carrito actual:\n";
$cartItems = \Gloudemans\Shoppingcart\Facades\Cart::content();
foreach($cartItems as $item) {
    echo "  - {$item->name}: \${$item->price} x {$item->qty} = \${$item->subtotal}\n";
}

$subtotal = (float) \Gloudemans\Shoppingcart\Facades\Cart::total(2, '.', '');
$shipping = 5.00;
$total = $subtotal + $shipping;

echo "Subtotal: \${$subtotal}\n";
echo "Shipping: \${$shipping}\n";
echo "Total: \${$total}\n";

echo "\n=== SIMULANDO PAGO EN EFECTIVO ===\n";

// Simular la llamada al endpoint de pago en efectivo
$controller = new \App\Http\Controllers\PaymentController();

try {
    $response = $controller->confirmCashPayment(new \Illuminate\Http\Request());
    $responseData = $response->getData(true);
    
    if ($responseData['success']) {
        echo "✅ " . $responseData['message'] . "\n";
        echo "Payment ID: " . $responseData['payment_id'] . "\n";
        echo "Order ID: " . $responseData['order_id'] . "\n";
        
        // Verificar la orden creada
        $order = \App\Models\Order::find($responseData['order_id']);
        if ($order) {
            echo "\n=== ORDEN CREADA ===\n";
            echo "Order Number: {$order->order_number}\n";
            echo "Status: {$order->status} ({$order->status_label})\n";
            echo "Payment Method: {$order->payment_method} ({$order->payment_method_label})\n";
            echo "Total: \${$order->total}\n";
        }
        
        // Verificar el pago creado
        $payment = \App\Models\Payment::find($responseData['payment_id']);
        if ($payment) {
            echo "\n=== PAGO CREADO ===\n";
            echo "Método: {$payment->payment_method}\n";
            echo "Status: {$payment->status}\n";
            echo "Monto: \${$payment->amount}\n";
        }
        
    } else {
        echo "❌ " . $responseData['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== ESTADO FINAL ===\n";
echo "Órdenes totales: " . \App\Models\Order::count() . "\n";
echo "Pagos confirmed (efectivo): " . \App\Models\Payment::where('status', 'confirmed')->count() . "\n";
