<?php

namespace App\Services;

use App\Enums\ShipmentStatus;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\DeliveryDriver;

class ShipmentService
{
    /**
     * Provincias donde está disponible el servicio de reparto
     */
    private const ALLOWED_PROVINCES = ['Pichincha', 'Manabí'];

    /**
     * Verificar si una dirección está en zona de cobertura
     */
    public function isDeliveryAllowed(array $address): bool
    {
        $province = $address['province'] ?? null;
        return in_array($province, self::ALLOWED_PROVINCES);
    }

    /**
     * Crear envío para una orden (cualquier método de pago)
     * Solo si la dirección está en zona de cobertura
     */
    public function createShipmentForOrder(Order $order): ?Shipment
    {
        // Verificar que la orden tenga dirección de envío
        if (!$order->shipping_address) {
            return null;
        }

        // Verificar que esté en zona de cobertura
        if (!$this->isDeliveryAllowed($order->shipping_address)) {
            return null;
        }

        // Verificar que no tenga ya un envío
        if ($order->hasShipment()) {
            return $order->shipment()->first();
        }

        // Crear el envío
        return Shipment::create([
            'tracking_number' => Shipment::generateTrackingNumber(),
            'order_id' => $order->id,
            'status' => ShipmentStatus::PENDING,
            'pickup_address' => $this->getStoreAddress(),
            'delivery_address' => $order->shipping_address,
            'delivery_fee' => $this->calculateDeliveryFee($order),
        ]);
    }

    /**
     * Asignar repartidor a un envío
     */
    public function assignDriverToShipment(Shipment $shipment, DeliveryDriver $driver): bool
    {
        if (!$shipment->canBeAssigned()) {
            return false;
        }

        $success = $shipment->assignDriver($driver);

        if ($success) {
            // Actualizar estado de la orden
            $shipment->order->update(['status' => \App\Enums\OrderStatus::ASIGNADO]); // Asignado
        }

        return $success;
    }

    /**
     * Asignar múltiples órdenes a un repartidor
     */
    public function assignMultipleOrdersToDriver(array $orderIds, DeliveryDriver $driver): array
    {
        $results = [];

        foreach ($orderIds as $orderId) {
            $order = Order::find($orderId);

            if (!$order || !$order->hasShipment()) {
                $results[$orderId] = ['success' => false, 'message' => 'Orden no encontrada o sin envío'];
                continue;
            }

            $success = $this->assignDriverToShipment($order->shipment, $driver);
            $results[$orderId] = [
                'success' => $success,
                'message' => $success ? 'Asignado correctamente' : 'No se pudo asignar'
            ];
        }

        return $results;
    }

    /**
     * Obtener órdenes pendientes de asignación en zona de cobertura
     */
    public function getPendingOrdersForAssignment()
    {
        return Order::whereHas('shipment', function ($query) {
            $query->where('status', ShipmentStatus::PENDING);
        })
            ->with(['shipment', 'user'])
            ->get()
            ->filter(function ($order) {
                return $this->isDeliveryAllowed($order->shipping_address);
            });
    }

    /**
     * Obtener repartidores disponibles
     */
    public function getAvailableDrivers()
    {
        return DeliveryDriver::active()->get();
    }

    /**
     * Calcular tarifa de envío
     */
    private function calculateDeliveryFee(Order $order, ?DeliveryDriver $driver = null): float
    {
        $province = $order->shipping_address['province'] ?? '';

        // Si hay un repartidor asignado, usar sus tarifas personalizadas
        if ($driver) {
            return $driver->getRateForOrder($order);
        }

        // Tarifas por defecto del sistema por provincia
        return match ($province) {
            'Pichincha' => 3.00,
            'Manabí' => 5.00,
            default => 0.00
        };
    }

    /**
     * Obtener dirección de la tienda/almacén
     */
    private function getStoreAddress(): array
    {
        // Intentar obtener la oficina principal desde la base de datos
        $mainOffice = \App\Models\OfficeAddress::getMainOffice();

        if ($mainOffice) {
            return [
                'address' => $mainOffice->address,
                'province' => $mainOffice->province,
                'canton' => $mainOffice->canton,
                'parish' => $mainOffice->parish,
                'reference' => $mainOffice->reference ?? 'Oficina principal'
            ];
        }

        // Dirección por defecto si no hay oficina configurada
        return [
            'address' => 'Dirección de tu tienda',
            'province' => 'Pichincha',
            'canton' => 'Quito',
            'parish' => 'Centro',
            'reference' => 'Almacén principal'
        ];
    }

    /**
     * Procesar orden después de verificar pago (transferencia/QR)
     */
    public function processOrderAfterPaymentVerification(Order $order): bool
    {
        // Cambiar estado a "Preparando"
        $order->update(['status' => \App\Enums\OrderStatus::PREPARANDO]);

        // Crear envío si está en zona de cobertura
        $shipment = $this->createShipmentForOrder($order);

        return $shipment !== null;
    }

    /**
     * Procesar orden de pago en efectivo
     */
    public function processOrderAfterCashConfirmation(Order $order): bool
    {
        // Para efectivo, crear envío inmediatamente si está en zona de cobertura
        $shipment = $this->createShipmentForOrder($order);

        if ($shipment) {
            // Cambiar estado a "Preparando" (listo para asignar repartidor)
            $order->update(['status' => \App\Enums\OrderStatus::PREPARANDO]);
            return true;
        }

        return false;
    }

    /**
     * Verificar si una orden puede tener envío con repartidor
     */
    public function canOrderHaveDelivery(Order $order): bool
    {
        return $order->shipping_address &&
            $this->isDeliveryAllowed($order->shipping_address);
    }

    /**
     * Crear envío para una orden con repartidor asignado directamente
     */
    public function createShipmentForOrderWithDriver(Order $order, DeliveryDriver $driver): ?Shipment
    {
        // Verificar que la orden tenga dirección de envío
        if (!$order->shipping_address) {
            return null;
        }

        // Verificar que esté en zona de cobertura
        $shippingAddress = $order->shipping_address;
        if (is_string($shippingAddress)) {
            $shippingAddress = json_decode($shippingAddress, true);
        }

        if (!$this->isDeliveryAllowed($shippingAddress)) {
            return null;
        }

        // Verificar que no tenga ya un envío
        if ($order->hasShipment()) {
            $shipment = $order->shipment()->first();

            // Si ya tiene envío, verificar si se puede asignar el nuevo conductor
            if ($shipment->canBeAssigned()) {
                // Solo asignar si está en estado PENDING
                $this->assignDriverToShipment($shipment, $driver);
            } else {
                // Si ya está asignado, solo actualizar el conductor y la orden
                $shipment->update([
                    'delivery_driver_id' => $driver->getKey(),
                    'assigned_at' => now(),
                ]);

                // Asegurar que la orden esté en estado correcto
                if ($order->status != \App\Enums\OrderStatus::ASIGNADO->value) {
                    $order->update(['status' => \App\Enums\OrderStatus::ASIGNADO]); // Asignado
                }
            }

            // Si ya tiene envío pero no puede ser asignado, crear uno nuevo
            if ($order->hasShipment() && !$order->shipment()->first()->canBeAssigned()) {
                // Crear nuevo envío con repartidor asignado directamente
                $newShipment = Shipment::create([
                    'tracking_number' => Shipment::generateTrackingNumber(),
                    'order_id' => $order->getKey(),
                    'status' => ShipmentStatus::ASSIGNED,
                    'pickup_address' => $this->getStoreAddress(),
                    'delivery_address' => $order->shipping_address,
                    'delivery_driver_id' => $driver->getKey(),
                    'assigned_at' => now(),
                ]);
                $order->update(['status' => \App\Enums\OrderStatus::ASIGNADO]); // Asignado
                return $newShipment;
            }

            return $shipment;
        }

        // Crear el envío con repartidor asignado
        $shipment = Shipment::create([
            'tracking_number' => Shipment::generateTrackingNumber(),
            'order_id' => $order->getKey(),
            'status' => ShipmentStatus::ASSIGNED,
            'pickup_address' => $this->getStoreAddress(),
            'delivery_address' => $order->shipping_address,
            'delivery_fee' => $this->calculateDeliveryFee($order, $driver),
            'delivery_driver_id' => $driver->getKey(),
            'assigned_at' => now(),
        ]);

        // Actualizar estado de la orden a "Asignado"
        $order->update(['status' => \App\Enums\OrderStatus::ASIGNADO]);

        return $shipment;
    }

    /**
     * Obtener un repartidor disponible con menos de 7 envíos activos
     */
    public function getAvailableDriver(): ?DeliveryDriver
    {
        $drivers = DeliveryDriver::where('is_active', true)->get();

        foreach ($drivers as $driver) {
            $activeShipments = Shipment::where('delivery_driver_id', $driver->getKey())
                ->whereIn('status', [
                    \App\Enums\ShipmentStatus::PENDING->value,
                    \App\Enums\ShipmentStatus::ASSIGNED->value,
                    \App\Enums\ShipmentStatus::IN_TRANSIT->value
                ])
                ->count();

            if ($activeShipments < 7) {
                return $driver;
            }
        }

        return null; // No hay repartidores disponibles
    }

    /**
     * Asignar repartidor automáticamente a una orden
     */
    public function autoAssignDriver(Order $order): bool
    {
        $driver = $this->getAvailableDriver();

        if (!$driver) {
            return false; // No hay repartidores disponibles
        }

        return $this->assignDriverWithLimit($order, $driver);
    }

    /**
     * Asignar repartidor con validación de límite mínimo de envíos
     */
    public function assignDriverWithLimit(Order $order, DeliveryDriver $driver): bool
    {
        // Verificar que el repartidor no tenga más de 7 envíos activos
        $activeShipments = Shipment::where('delivery_driver_id', $driver->getKey())
            ->whereIn('status', [
                \App\Enums\ShipmentStatus::PENDING->value,
                \App\Enums\ShipmentStatus::ASSIGNED->value,
                \App\Enums\ShipmentStatus::IN_TRANSIT->value
            ])
            ->count();

        if ($activeShipments >= 7) {
            return false; // No se puede asignar más envíos
        }

        // Crear o asignar envío
        if ($order->hasShipment()) {
            $shipment = $order->shipment()->first();
            $this->assignDriverToShipment($shipment, $driver);
        } else {
            // Crear envío directamente
            Shipment::create([
                'tracking_number' => Shipment::generateTrackingNumber(),
                'order_id' => $order->getKey(),
                'status' => \App\Enums\ShipmentStatus::ASSIGNED,
                'pickup_address' => $this->getStoreAddress(),
                'delivery_address' => $order->shipping_address,
                'delivery_driver_id' => $driver->getKey(),
                'assigned_at' => now(),
            ]);
        }

        // Actualizar estado de la orden a ASIGNADO
        $order->update(['status' => \App\Enums\OrderStatus::ASIGNADO->value]);

        return true;
    }
}
