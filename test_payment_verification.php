<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DE VERIFICACIÓN DE PAGO ===\n";

// Buscar un pago pending_verification que tenga cart_data
$payment = \App\Models\Payment::where('status', 'pending_verification')
    ->whereNotNull('cart_data')
    ->whereJsonLength('cart_data', '>', 0)
    ->first();

if (!$payment) {
    echo "No hay pagos pendientes de verificación\n";
    exit;
}

echo "Pago a verificar:\n";
echo "  ID: {$payment->id}\n";
echo "  Usuario: " . ($payment->user->name ?? 'N/A') . "\n";
echo "  Método: {$payment->payment_method}\n";
echo "  Monto: \${$payment->amount}\n";
echo "  Status: {$payment->status}\n";

echo "\n=== SIMULANDO VERIFICACIÓN (APROBACIÓN) ===\n";

// Simular la aprobación usando el controlador
$controller = new \App\Http\Controllers\Admin\PaymentVerificationController();

// Crear un request de verificación
$request = new \Illuminate\Http\Request();
$request->replace([
    'status' => 'approved',
    'reason' => '' // Campo requerido aunque esté vacío para aprobados
]);

try {
    $response = $controller->verify($request, $payment);
    $responseData = $response->getData(true);
    
    if ($responseData['success']) {
        echo "✅ " . $responseData['message'] . "\n";
        
        // Recargar el pago para ver los cambios
        $payment->refresh();
        echo "\n=== PAGO DESPUÉS DE VERIFICACIÓN ===\n";
        echo "Status: {$payment->status}\n";
        echo "Verificado en: " . ($payment->verified_at ? $payment->verified_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
        echo "Order ID: " . ($payment->order_id ?? 'N/A') . "\n";
        
        // Ver si se creó una orden
        if ($payment->order_id) {
            $order = \App\Models\Order::find($payment->order_id);
            if ($order) {
                echo "\n=== ORDEN ASOCIADA ===\n";
                echo "Order Number: {$order->order_number}\n";
                echo "Status: {$order->status} ({$order->status_label})\n";
                echo "Total: \${$order->total}\n";
                echo "Payment ID: {$order->payment_id}\n";
            }
        }
        
    } else {
        echo "❌ " . $responseData['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== ESTADO FINAL ===\n";
echo "Órdenes totales: " . \App\Models\Order::count() . "\n";
echo "Pagos pending_verification: " . \App\Models\Payment::where('status', 'pending_verification')->count() . "\n";
echo "Pagos approved: " . \App\Models\Payment::where('status', 'approved')->count() . "\n";
