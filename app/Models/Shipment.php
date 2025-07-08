<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Shipment extends Model
{
    protected $fillable = [
        'tracking_number',
        'order_id',
        'delivery_driver_id',
        'status',
        'pickup_address',
        'delivery_address',
        'delivery_fee',
        'distance_km',
        'estimated_time_minutes',
        'assigned_at',
        'picked_up_at',
        'in_transit_at',
        'delivered_at',
        'failed_at',
        'delivery_notes',
        'failure_reason',
        'delivery_proof',
        'customer_rating',
        'customer_feedback',
        'current_latitude',
        'current_longitude',
        'location_updated_at'
    ];

    protected $casts = [
        'pickup_address' => 'array',
        'delivery_address' => 'array',
        'delivery_proof' => 'array',
        'delivery_fee' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'customer_rating' => 'decimal:2',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'in_transit_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_at' => 'datetime',
        'location_updated_at' => 'datetime'
    ];

    // Relaciones
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryDriver()
    {
        return $this->belongsTo(DeliveryDriver::class);
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    public function scopeAssigned(Builder $query): Builder
    {
        return $query->where('status', 2);
    }

    public function scopeInTransit(Builder $query): Builder
    {
        return $query->where('status', 3);
    }

    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('status', 4);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 5);
    }

    public function scopeByDriver(Builder $query, int $driverId): Builder
    {
        return $query->where('delivery_driver_id', $driverId);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            1 => 'Pendiente',
            2 => 'Asignado',
            3 => 'En Camino',
            4 => 'Entregado',
            5 => 'Fallido',
            default => 'Pendiente'
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            1 => 'bg-yellow-100 text-yellow-800',
            2 => 'bg-blue-100 text-blue-800',
            3 => 'bg-orange-100 text-orange-800',
            4 => 'bg-green-100 text-green-800',
            5 => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            1 => 'fas fa-clock',
            2 => 'fas fa-user-check',
            3 => 'fas fa-truck',
            4 => 'fas fa-check-circle',
            5 => 'fas fa-times-circle',
            default => 'fas fa-question'
        };
    }

    public function getCurrentLocationAttribute(): ?array
    {
        if ($this->current_latitude && $this->current_longitude) {
            return [
                'lat' => (float) $this->current_latitude,
                'lng' => (float) $this->current_longitude,
                'updated_at' => $this->location_updated_at
            ];
        }
        return null;
    }

    // Métodos de utilidad
    public static function generateTrackingNumber(): string
    {
        do {
            $trackingNumber = 'TRK-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (self::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    public function assignDriver(DeliveryDriver $driver): void
    {
        $this->update([
            'delivery_driver_id' => $driver->id,
            'status' => 2,
            'assigned_at' => now(),
            'delivery_fee' => $driver->delivery_fee
        ]);

        // Actualizar estado de la orden
        $this->order->update(['status' => 4]); // Asignado
    }

    public function markAsPickedUp(string $notes = null): void
    {
        $this->update([
            'status' => 3,
            'picked_up_at' => now(),
            'in_transit_at' => now(),
            'delivery_notes' => $notes
        ]);

        // Actualizar estado de la orden
        $this->order->update(['status' => 5]); // En camino
    }

    public function markAsDelivered(array $proof = null, string $notes = null): void
    {
        $this->update([
            'status' => 4,
            'delivered_at' => now(),
            'delivery_proof' => $proof,
            'delivery_notes' => $notes
        ]);

        // Actualizar estado de la orden
        $this->order->update(['status' => 6]); // Entregado

        // Incrementar entregas del repartidor
        if ($this->deliveryDriver) {
            $this->deliveryDriver->incrementDeliveries();
        }
    }

    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => 5,
            'failed_at' => now(),
            'failure_reason' => $reason
        ]);

        // La orden puede volver a estado anterior o cancelarse
        $this->order->update(['status' => 3]); // Volver a preparando
    }

    public function updateLocation(float $lat, float $lng): void
    {
        $this->update([
            'current_latitude' => $lat,
            'current_longitude' => $lng,
            'location_updated_at' => now()
        ]);
    }

    public function addCustomerFeedback(float $rating, string $feedback = null): void
    {
        $this->update([
            'customer_rating' => $rating,
            'customer_feedback' => $feedback
        ]);

        // Actualizar rating del repartidor (promedio simple)
        if ($this->deliveryDriver) {
            $avgRating = self::where('delivery_driver_id', $this->delivery_driver_id)
                ->whereNotNull('customer_rating')
                ->avg('customer_rating');

            $this->deliveryDriver->updateRating($avgRating);
        }
    }
}
