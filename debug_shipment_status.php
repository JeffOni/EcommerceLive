<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->boot();

echo "=== DEBUG SHIPMENT STATUS ===\n";

// Obtener el primer shipment
$shipment = App\Models\Shipment::first();

if (!$shipment) {
    echo "No hay shipments en la base de datos\n";
    exit;
}

echo "Shipment ID: " . $shipment->id . "\n";
echo "Raw status from DB: ";
var_dump($shipment->getRawOriginal('status'));

echo "Status after casting: ";
var_dump($shipment->status);

echo "Status type: " . gettype($shipment->status) . "\n";

if (is_object($shipment->status)) {
    echo "Status class: " . get_class($shipment->status) . "\n";
    echo "Status value: " . $shipment->status->value . "\n";

    if (method_exists($shipment->status, 'getValue')) {
        echo "getValue(): " . $shipment->status->getValue() . "\n";
    } else {
        echo "Method getValue() not found\n";
    }

    if (method_exists($shipment->status, 'label')) {
        echo "label(): " . $shipment->status->label() . "\n";
    }
} else {
    echo "Status is not an object - casting failed\n";
}

echo "\n=== TESTING ENUM DIRECTLY ===\n";
$status = App\Enums\ShipmentStatus::PENDING;
echo "Enum PENDING value: " . $status->value . "\n";
echo "Enum PENDING getValue(): " . $status->getValue() . "\n";
echo "Enum PENDING label(): " . $status->label() . "\n";

echo "\n=== TESTING STATUS UPDATE ===\n";
try {
    $shipment->update(['status' => App\Enums\ShipmentStatus::ASSIGNED]);
    echo "Status updated successfully\n";
    echo "New status: " . $shipment->fresh()->status->getValue() . "\n";
} catch (Exception $e) {
    echo "Error updating status: " . $e->getMessage() . "\n";
}
