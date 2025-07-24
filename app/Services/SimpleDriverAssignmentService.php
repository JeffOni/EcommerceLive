<?php

namespace App\Services;

use App\Models\Order;
use App\Models\DeliveryDriver;
use App\Models\Shipment;
use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;

class SimpleDriverAssignmentService
{
    /**
     * Asignar repartidor a una orden de manera simple y directa
     */
    public function assignDriverToOrder(Order $order, DeliveryDriver $driver): array
    {
        try {
            // 1. Verificar que la orden esté en estado válido
            if (!in_array($order->status, [1, 2, 3])) { // PENDIENTE, PAGADO, PREPARANDO
                return [
                    'success' => false,
                    'message' => 'La orden no está en un estado válido para asignar repartidor.'
                ];
            }

            // 2. Verificar límite de repartidor (máximo 5 envíos activos)
            $activeShipments = Shipment::where('delivery_driver_id', $driver->getKey())
                ->whereIn('status', [1, 2, 4]) // PENDING, ASSIGNED, IN_TRANSIT
                ->count();

            if ($activeShipments >= 5) {
                return [
                    'success' => false,
                    'message' => 'El repartidor ya tiene 5 envíos activos. No puede recibir más asignaciones.'
                ];
            }

            // 3. Si ya existe un envío, actualizarlo
            if ($order->hasShipment()) {
                $shipment = $order->shipment()->first();
                $shipment->update([
                    'delivery_driver_id' => $driver->getKey(),
                    'status' => ShipmentStatus::ASSIGNED->value,
                    'assigned_at' => now()
                ]);
            } else {
                // 4. Crear nuevo envío con repartidor asignado
                Shipment::create([
                    'tracking_number' => $this->generateTrackingNumber(),
                    'order_id' => $order->getKey(),
                    'status' => ShipmentStatus::ASSIGNED->value,
                    'pickup_address' => json_encode([
                        'address' => 'Tienda Principal',
                        'city' => 'Santa Cruz',
                        'province' => 'Santa Cruz'
                    ]),
                    'delivery_address' => $order->shipping_address,
                    'delivery_driver_id' => $driver->getKey(),
                    'assigned_at' => now()
                ]);
            }

            // 5. Actualizar estado de la orden a ASIGNADO
            $order->update(['status' => OrderStatus::ASIGNADO->value]);

            return [
                'success' => true,
                'message' => "Repartidor {$driver->getAttribute('name')} asignado correctamente a la orden #{$order->getKey()}"
            ];

        } catch (\Exception $e) {
            \Log::error('Error en asignación simple: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Buscar repartidor disponible automáticamente
     */
    public function findAvailableDriver(): ?DeliveryDriver
    {
        $drivers = DeliveryDriver::where('is_active', true)->get();

        foreach ($drivers as $driver) {
            $activeShipments = Shipment::where('delivery_driver_id', $driver->getKey())
                ->whereIn('status', [1, 2, 4]) // PENDING, ASSIGNED, IN_TRANSIT
                ->count();

            if ($activeShipments < 5) {
                return $driver;
            }
        }

        return null;
    }

    /**
     * Asignar repartidor automáticamente
     */
    public function autoAssignDriver(Order $order): array
    {
        $driver = $this->findAvailableDriver();

        if (!$driver) {
            return [
                'success' => false,
                'message' => 'No hay repartidores disponibles en este momento.'
            ];
        }

        return $this->assignDriverToOrder($order, $driver);
    }

    /**
     * Generar número de seguimiento
     */
    private function generateTrackingNumber(): string
    {
        do {
            $trackingNumber = 'SHP-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (Shipment::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }
}
