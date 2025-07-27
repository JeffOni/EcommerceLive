<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DeliveryDriver;
use App\Models\Shipment;

echo "🧪 PROBANDO LIBERACIÓN DE ESPACIO AL ENTREGAR\n";
echo "=============================================\n\n";

// Obtener el repartidor JEFFERSON ALEXIS
$driver = DeliveryDriver::where('name', 'JEFFERSON ALEXIS')->first();

if (!$driver) {
    echo "❌ No se encontró el repartidor JEFFERSON ALEXIS\n";
    exit;
}

echo "📋 ESTADO INICIAL:\n";
echo "******************\n";

$activeShipments = Shipment::where('delivery_driver_id', $driver->id)
    ->whereIn('status', [
        \App\Enums\ShipmentStatus::PENDING->value,
        \App\Enums\ShipmentStatus::ASSIGNED->value,
        \App\Enums\ShipmentStatus::IN_TRANSIT->value
    ])
    ->count();

$deliveredShipments = Shipment::where('delivery_driver_id', $driver->id)
    ->where('status', \App\Enums\ShipmentStatus::DELIVERED->value)
    ->count();

echo "👤 {$driver->name}:\n";
echo "   • Envíos activos: {$activeShipments}/7\n";
echo "   • Envíos entregados: {$deliveredShipments}\n";
echo "   • Disponible: " . ($activeShipments < 7 ? "✅ SÍ" : "❌ NO") . "\n\n";

if ($activeShipments > 0) {
    // Buscar un envío activo para marcar como entregado
    $shipmentToDeliver = Shipment::where('delivery_driver_id', $driver->id)
        ->whereIn('status', [
            \App\Enums\ShipmentStatus::PENDING->value,
            \App\Enums\ShipmentStatus::ASSIGNED->value,
            \App\Enums\ShipmentStatus::IN_TRANSIT->value
        ])
        ->first();

    if ($shipmentToDeliver) {
        echo "🚛 SIMULANDO ENTREGA:\n";
        echo "********************\n";
        echo "📦 Marcando como entregado el envío: {$shipmentToDeliver->tracking_number}\n";
        
        // Marcar como entregado
        $result = $shipmentToDeliver->markAsDelivered();
        
        if ($result) {
            echo "✅ Envío marcado como entregado exitosamente\n\n";
            
            // Verificar nuevo estado
            echo "📊 ESTADO DESPUÉS DE LA ENTREGA:\n";
            echo "********************************\n";
            
            $activeShipmentsAfter = Shipment::where('delivery_driver_id', $driver->id)
                ->whereIn('status', [
                    \App\Enums\ShipmentStatus::PENDING->value,
                    \App\Enums\ShipmentStatus::ASSIGNED->value,
                    \App\Enums\ShipmentStatus::IN_TRANSIT->value
                ])
                ->count();

            $deliveredShipmentsAfter = Shipment::where('delivery_driver_id', $driver->id)
                ->where('status', \App\Enums\ShipmentStatus::DELIVERED->value)
                ->count();

            echo "👤 {$driver->name}:\n";
            echo "   • Envíos activos: {$activeShipmentsAfter}/7 (era {$activeShipments})\n";
            echo "   • Envíos entregados: {$deliveredShipmentsAfter} (era {$deliveredShipments})\n";
            echo "   • Disponible: " . ($activeShipmentsAfter < 7 ? "✅ SÍ" : "❌ NO") . "\n";
            echo "   • Espacios liberados: " . ($activeShipments - $activeShipmentsAfter) . "\n\n";
            
            if ($activeShipmentsAfter < $activeShipments) {
                echo "🎉 ¡ÉXITO! El espacio se liberó correctamente\n";
                echo "💡 El repartidor puede ahora recibir " . (7 - $activeShipmentsAfter) . " envíos más\n";
            } else {
                echo "❌ ERROR: El espacio no se liberó correctamente\n";
            }
        } else {
            echo "❌ Error al marcar como entregado\n";
        }
    } else {
        echo "❌ No se encontró ningún envío activo para entregar\n";
    }
} else {
    echo "✅ El repartidor no tiene envíos activos\n";
}

echo "\n✅ PRUEBA COMPLETADA\n";
