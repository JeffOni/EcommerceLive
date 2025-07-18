<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Shipment extends Model
{
    use HasFactory;

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
        'status' => ShipmentStatus::class,
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

    /**
     * Relación con Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con DeliveryDriver
     */
    public function deliveryDriver(): BelongsTo
    {
        return $this->belongsTo(DeliveryDriver::class, 'delivery_driver_id');
    }

    /**
     * Generar número de seguimiento único
     */
    public static function generateTrackingNumber(): string
    {
        do {
            $trackingNumber = 'SHP-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    /**
     * Verificar si el envío puede ser asignado a un conductor
     */
    public function canBeAssigned(): bool
    {
        return $this->status === ShipmentStatus::PENDING;
    }

    /**
     * Verificar si el envío puede ser recogido
     */
    public function canBePickedUp(): bool
    {
        return $this->status === ShipmentStatus::ASSIGNED;
    }

    /**
     * Verificar si el envío puede estar en tránsito
     */
    public function canBeInTransit(): bool
    {
        return $this->status === ShipmentStatus::PICKED_UP;
    }

    /**
     * Verificar si el envío puede ser entregado
     */
    public function canBeDelivered(): bool
    {
        return $this->status === ShipmentStatus::IN_TRANSIT;
    }

    /**
     * Asignar conductor al envío
     */
    public function assignDriver(DeliveryDriver $driver): bool
    {
        if (!$this->canBeAssigned()) {
            return false;
        }

        $this->update([
            'delivery_driver_id' => $driver->id,
            'status' => ShipmentStatus::ASSIGNED,
            'assigned_at' => now(),
        ]);

        return true;
    }

    /**
     * Marcar como recogido
     */
    public function markAsPickedUp(): bool
    {
        if (!$this->canBePickedUp()) {
            return false;
        }

        $this->update([
            'status' => ShipmentStatus::PICKED_UP,
            'picked_up_at' => now(),
        ]);

        return true;
    }

    /**
     * Marcar como en tránsito
     */
    public function markAsInTransit(): bool
    {
        if (!$this->canBeInTransit()) {
            return false;
        }

        $this->update([
            'status' => ShipmentStatus::IN_TRANSIT,
            'in_transit_at' => now(),
        ]);

        return true;
    }

    /**
     * Marcar como entregado
     */
    public function markAsDelivered(?array $proof = null): bool
    {
        if (!$this->canBeDelivered()) {
            return false;
        }

        $this->update([
            'status' => ShipmentStatus::DELIVERED,
            'delivered_at' => now(),
            'delivery_proof' => $proof,
        ]);

        // Actualizar estado de la orden
        $this->order->update(['status' => 4]); // Entregado

        return true;
    }

    /**
     * Marcar como fallido
     */
    public function markAsFailed(string $reason): bool
    {
        $this->update([
            'status' => ShipmentStatus::FAILED,
            'failed_at' => now(),
            'failure_reason' => $reason,
        ]);

        return true;
    }

    /**
     * Verificar si la dirección está en provincias permitidas
     */
    public static function isDeliveryAllowed(array $address): bool
    {
        $allowedProvinces = ['Pichincha', 'Manabí'];
        $province = $address['province'] ?? null;

        return in_array($province, $allowedProvinces);
    }

    // Scopes para mantener compatibilidad
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ShipmentStatus::PENDING);
    }

    public function scopeAssigned(Builder $query): Builder
    {
        return $query->where('status', ShipmentStatus::ASSIGNED);
    }

    public function scopeInTransit(Builder $query): Builder
    {
        return $query->where('status', ShipmentStatus::IN_TRANSIT);
    }

    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('status', ShipmentStatus::DELIVERED);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', ShipmentStatus::FAILED);
    }

    public function scopeByDriver(Builder $query, int $driverId): Builder
    {
        return $query->where('delivery_driver_id', $driverId);
    }

    // Accessors usando el Enum
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            ShipmentStatus::PENDING => 'bg-yellow-100 text-yellow-800',
            ShipmentStatus::ASSIGNED => 'bg-blue-100 text-blue-800',
            ShipmentStatus::PICKED_UP => 'bg-purple-100 text-purple-800',
            ShipmentStatus::IN_TRANSIT => 'bg-orange-100 text-orange-800',
            ShipmentStatus::DELIVERED => 'bg-green-100 text-green-800',
            ShipmentStatus::FAILED => 'bg-red-100 text-red-800',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            ShipmentStatus::PENDING => 'fas fa-clock',
            ShipmentStatus::ASSIGNED => 'fas fa-user-check',
            ShipmentStatus::PICKED_UP => 'fas fa-hand-paper',
            ShipmentStatus::IN_TRANSIT => 'fas fa-truck',
            ShipmentStatus::DELIVERED => 'fas fa-check-circle',
            ShipmentStatus::FAILED => 'fas fa-times-circle',
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

    /**
     * Actualizar ubicación GPS del envío
     */
    public function updateLocation(float $lat, float $lng): void
    {
        $this->update([
            'current_latitude' => $lat,
            'current_longitude' => $lng,
            'location_updated_at' => now()
        ]);
    }

    /**
     * Agregar calificación del cliente
     */
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

            // Aquí necesitarías un método en el modelo User para actualizar rating
            // $this->deliveryDriver->updateRating($avgRating);
        }
    }
}
