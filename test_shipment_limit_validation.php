<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VALIDACIÓN DE SISTEMA DE LÍMITE DE ENVÍOS ===\n\n";

try {
    // 1. Verificar repartidores activos
    $drivers = \App\Models\DeliveryDriver::active()->get();
    echo "1. Repartidores activos: " . $drivers->count() . "\n";
    
    foreach ($drivers as $driver) {
        // Contar envíos activos
        $activeShipments = \App\Models\Shipment::where('delivery_driver_id', $driver->id)
            ->whereIn('status', [
                \App\Enums\ShipmentStatus::PENDING->value,
                \App\Enums\ShipmentStatus::ASSIGNED->value,
                \App\Enums\ShipmentStatus::IN_TRANSIT->value
            ])
            ->count();
            
        // Contar envíos entregados
        $deliveredShipments = \App\Models\Shipment::where('delivery_driver_id', $driver->id)
            ->where('status', \App\Enums\ShipmentStatus::DELIVERED->value)
            ->count();
            
        echo "   - {$driver->name}: {$activeShipments} activos, {$deliveredShipments} entregados\n";
        
        if ($activeShipments >= 7) {
            echo "     ❌ LÍMITE ALCANZADO - No puede recibir más envíos\n";
        } else {
            echo "     ✅ Disponible para " . (7 - $activeShipments) . " envíos más\n";
        }
    }
    
    echo "\n2. Verificando estados de envíos:\n";
    
    // Verificar estados de envíos
    $statusCounts = DB::table('shipments')
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();
        
    foreach ($statusCounts as $status) {
        $statusName = match($status->status) {
            1 => 'PENDING',
            2 => 'ASSIGNED', 
            3 => 'PICKED_UP',
            4 => 'IN_TRANSIT',
            5 => 'DELIVERED',
            6 => 'FAILED',
            default => 'UNKNOWN'
        };
        echo "   - Estado {$status->status} ({$statusName}): {$status->count} envíos\n";
    }
    
    echo "\n3. Probando marcado como entregado:\n";
    
    // Buscar un envío activo para probar
    $testShipment = \App\Models\Shipment::whereIn('status', [2, 3, 4])
        ->whereNotNull('delivery_driver_id')
        ->first();
        
    if ($testShipment) {
        echo "   - Envío de prueba encontrado: #{$testShipment->id} (Estado: {$testShipment->status->value})\n";
        
        $driverBefore = $testShipment->deliveryDriver;
        $activesBefore = \App\Models\Shipment::where('delivery_driver_id', $driverBefore->id)
            ->whereIn('status', [1, 2, 3, 4])
            ->count();
            
        echo "   - Repartidor {$driverBefore->name} tenía {$activesBefore} envíos activos\n";
        
        // Marcar como entregado
        $testShipment->markAsDelivered();
        
        $activesAfter = \App\Models\Shipment::where('delivery_driver_id', $driverBefore->id)
            ->whereIn('status', [1, 2, 3, 4])
            ->count();
            
        echo "   - Después de marcar como entregado: {$activesAfter} envíos activos\n";
        
        if ($activesAfter < $activesBefore) {
            echo "   ✅ ¡CORRECTO! Se liberó espacio en el límite del repartidor\n";
        } else {
            echo "   ❌ ERROR: No se liberó espacio en el límite\n";
        }
    } else {
        echo "   - No hay envíos activos para probar\n";
    }
    
    echo "\n=== VALIDACIÓN COMPLETADA ===\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . " línea " . $e->getLine() . "\n";
}
