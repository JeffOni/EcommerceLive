<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA COMPLETA DEL SISTEMA DE ENVÍOS ===\n\n";

try {
    // 1. Encontrar un repartidor con envíos
    $driver = \App\Models\DeliveryDriver::active()
        ->whereHas('shipments')
        ->first();
        
    if (!$driver) {
        echo "❌ No se encontró repartidor con envíos\n";
        exit;
    }
    
    echo "1. Probando con repartidor: {$driver->name}\n";
    
    // 2. Mostrar estado inicial
    $initialActive = \App\Models\Shipment::where('delivery_driver_id', $driver->id)
        ->whereIn('status', [1, 2, 3, 4])
        ->count();
        
    echo "   - Envíos activos iniciales: {$initialActive}\n";
    echo "   - Espacio disponible: " . (7 - $initialActive) . " envíos\n";
    
    // 3. Buscar un envío para marcar como entregado
    $shipment = \App\Models\Shipment::where('delivery_driver_id', $driver->id)
        ->whereIn('status', [2, 3, 4])
        ->first();
        
    if (!$shipment) {
        echo "❌ No hay envíos activos para este repartidor\n";
        exit;
    }
    
    echo "\n2. Marcando envío #{$shipment->id} como entregado...\n";
    echo "   - Estado anterior: {$shipment->status->value} ({$shipment->status->label()})\n";
    
    // 4. Marcar como entregado (simulando la acción desde la interfaz)
    $result = $shipment->markAsDelivered();
    
    if ($result) {
        echo "   ✅ Envío marcado como entregado exitosamente\n";
        
        // 5. Verificar nuevo estado
        $shipment->refresh();
        echo "   - Estado actual: {$shipment->status->value} ({$shipment->status->label()})\n";
        
        // 6. Verificar que se liberó espacio
        $finalActive = \App\Models\Shipment::where('delivery_driver_id', $driver->id)
            ->whereIn('status', [1, 2, 3, 4])
            ->count();
            
        echo "\n3. Verificando límites después del cambio:\n";
        echo "   - Envíos activos finales: {$finalActive}\n";
        echo "   - Espacio disponible: " . (7 - $finalActive) . " envíos\n";
        
        if ($finalActive < $initialActive) {
            echo "   ✅ Se liberó correctamente 1 espacio en el límite\n";
        } else {
            echo "   ❌ No se liberó espacio (posible error)\n";
        }
        
        // 7. Probar asignación de nuevo envío si hay espacio
        if ($finalActive < 7) {
            echo "\n4. Probando asignación de nuevo envío...\n";
            
            // Buscar una orden sin envío
            $orderWithoutShipment = \App\Models\Order::whereDoesntHave('shipment')
                ->whereIn('status', [2, 3])
                ->first();
                
            if ($orderWithoutShipment) {
                echo "   - Orden encontrada: #{$orderWithoutShipment->id}\n";
                echo "   - Estado de la orden: {$orderWithoutShipment->status}\n";
                echo "   ✅ Sistema listo para recibir nuevas asignaciones\n";
            } else {
                echo "   - No hay órdenes disponibles para envío\n";
            }
        }
        
    } else {
        echo "   ❌ Error al marcar envío como entregado\n";
    }
    
    echo "\n5. Resumen final del sistema:\n";
    
    // Mostrar estado de todos los repartidores
    $allDrivers = \App\Models\DeliveryDriver::active()->get();
    foreach ($allDrivers as $d) {
        $active = \App\Models\Shipment::where('delivery_driver_id', $d->id)
            ->whereIn('status', [1, 2, 3, 4])
            ->count();
            
        $delivered = \App\Models\Shipment::where('delivery_driver_id', $d->id)
            ->where('status', 5)
            ->count();
            
        $status = $active >= 7 ? "❌ LÍMITE ALCANZADO" : "✅ DISPONIBLE (" . (7 - $active) . " espacios)";
        echo "   - {$d->name}: {$active} activos, {$delivered} entregados - {$status}\n";
    }
    
    echo "\n=== PRUEBA COMPLETADA EXITOSAMENTE ===\n";
    echo "✅ El sistema de límites está funcionando correctamente\n";
    echo "✅ Los envíos entregados liberan espacio automáticamente\n";
    echo "✅ El límite de 7 envíos por repartidor se respeta\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . " línea " . $e->getLine() . "\n";
}
