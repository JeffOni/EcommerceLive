<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->boot();

echo "Verificando datos del modelo Shipment...\n";

$shipment = App\Models\Shipment::first();
if ($shipment) {
    echo "Shipment ID: " . $shipment->id . "\n";
    echo "Status type: " . gettype($shipment->status) . "\n";
    echo "Status value: ";
    var_dump($shipment->status);
    
    echo "Tracking number type: " . gettype($shipment->tracking_number) . "\n";
    echo "Tracking number value: " . $shipment->tracking_number . "\n";
    
    if ($shipment->order) {
        echo "Order ID type: " . gettype($shipment->order->id) . "\n";
        echo "Order ID value: " . $shipment->order->id . "\n";
    }
    
    if ($shipment->deliveryDriver) {
        echo "Driver name type: " . gettype($shipment->deliveryDriver->name) . "\n";
        echo "Driver name value: " . $shipment->deliveryDriver->name . "\n";
    }
} else {
    echo "No shipments found in database\n";
}
