<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ÓRDENES EXISTENTES ===\n";
$orders = \App\Models\Order::with(['user', 'payment'])->get();
foreach($orders as $order) {
    echo "ID: {$order->id}\n";
    echo "  - Order Number: {$order->order_number}\n";
    echo "  - Usuario: " . ($order->user->name ?? 'N/A') . "\n";
    echo "  - Total: \${$order->total}\n";
    echo "  - Status: {$order->status} ({$order->status_label})\n";
    echo "  - Payment Method: {$order->payment_method} ({$order->payment_method_label})\n";
    echo "  - Payment ID: " . ($order->payment_id ?? 'N/A') . "\n";
    echo "  - Creado: {$order->created_at}\n";
    echo "  ----------------------\n";
}

echo "\n=== PAGOS APROBADOS ===\n";
$approvedPayments = \App\Models\Payment::where('status', 'approved')->with(['user', 'order'])->get();
foreach($approvedPayments as $payment) {
    echo "Payment ID: {$payment->id}\n";
    echo "  - Usuario: " . ($payment->user->name ?? 'N/A') . "\n";
    echo "  - Método: {$payment->payment_method}\n";
    echo "  - Monto: \${$payment->amount}\n";
    echo "  - Order ID: " . ($payment->order_id ?? 'N/A') . "\n";
    echo "  - Verificado: " . ($payment->verified_at ? $payment->verified_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
    echo "  ----------------------\n";
}
