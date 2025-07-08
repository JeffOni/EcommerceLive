<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== BUSCANDO PAGOS CON CART DATA ===\n";

$payments = \App\Models\Payment::where('status', 'approved')
    ->whereNull('order_id')
    ->get();

foreach($payments as $payment) {
    echo "Payment ID: {$payment->id}\n";
    echo "  Cart Data: " . (is_array($payment->cart_data) && count($payment->cart_data) > 0 ? count($payment->cart_data) . ' items' : 'Vacío/N/A') . "\n";
    echo "  Monto: \${$payment->amount}\n";
    echo "  ----\n";
}
