<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ESTADO ACTUAL ===\n";
echo "Órdenes existentes: " . \App\Models\Order::count() . "\n";
echo "Pagos existentes: " . \App\Models\Payment::count() . "\n";
echo "Pagos pending_verification: " . \App\Models\Payment::where('status', 'pending_verification')->count() . "\n\n";

echo "=== PAGOS PENDING VERIFICATION ===\n";
$payments = \App\Models\Payment::where('status', 'pending_verification')->with('user')->get();
foreach($payments as $p) {
    echo "ID: {$p->id} - Usuario: " . ($p->user->name ?? 'N/A') . " - Método: {$p->payment_method} - Monto: \${$p->amount}\n";
}

echo "\n=== SIMULANDO APROBACIÓN DEL PRIMER PAGO ===\n";
$firstPayment = $payments->first();
if($firstPayment) {
    echo "Aprobando pago ID: {$firstPayment->id}\n";
    
    // Simular la aprobación
    $firstPayment->update([
        'status' => 'approved',
        'verified_at' => now(),
        'verified_by' => 1
    ]);
    
    // Crear orden desde el pago
    $controller = new \App\Http\Controllers\Admin\PaymentVerificationController();
    $method = new ReflectionMethod($controller, 'createOrderFromPayment');
    $method->setAccessible(true);
    $order = $method->invoke($controller, $firstPayment);
    
    if($order) {
        echo "✅ Orden creada: ID {$order->id}, Order Number: {$order->order_number}\n";
        echo "✅ Total: \${$order->total}, Status: {$order->status}\n";
    } else {
        echo "❌ Error al crear la orden\n";
    }
    
    echo "\n=== ESTADO DESPUÉS ===\n";
    echo "Órdenes existentes: " . \App\Models\Order::count() . "\n";
    echo "Pagos pending_verification: " . \App\Models\Payment::where('status', 'pending_verification')->count() . "\n";
    echo "Pagos approved: " . \App\Models\Payment::where('status', 'approved')->count() . "\n";
}
