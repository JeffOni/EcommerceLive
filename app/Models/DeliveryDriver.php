<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DeliveryDriver extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'license_number',
        'vehicle_type',
        'vehicle_plate',
        'identification_number',
        'address',
        'profile_photo',
        'delivery_fee',
        'intra_province_rate',
        'inter_province_rate',
        'is_active',
        'total_deliveries',
        'rating',
        'last_delivery_at',
        'emergency_contact'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delivery_fee' => 'decimal:2',
        'intra_province_rate' => 'decimal:2',
        'inter_province_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'total_deliveries' => 'integer',
        'last_delivery_at' => 'datetime',
        'emergency_contact' => 'array'
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByVehicleType(Builder $query, string $vehicleType): Builder
    {
        return $query->where('vehicle_type', $vehicleType);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Activo' : 'Inactivo';
    }

    public function getStatusClassAttribute(): string
    {
        return $this->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    public function getRatingStarsAttribute(): string
    {
        $rating = (float) $this->rating;
        $stars = '';

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<i class="fas fa-star text-yellow-400"></i>';
            } elseif ($i - 0.5 <= $rating) {
                $stars .= '<i class="fas fa-star-half-alt text-yellow-400"></i>';
            } else {
                $stars .= '<i class="far fa-star text-gray-300"></i>';
            }
        }

        return $stars;
    }

    /**
     * Obtener URL de la foto de perfil
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo && file_exists(storage_path('app/public/' . $this->profile_photo))) {
            return asset('storage/' . $this->profile_photo);
        }

        // Foto por defecto usando Gravatar o una imagen predeterminada
        $gravatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?d=mp&s=200';
        return $gravatar;
    }

    // Métodos de utilidad
    public function incrementDeliveries(): void
    {
        $this->increment('total_deliveries');
        $this->update(['last_delivery_at' => now()]);
    }

    public function updateRating(float $newRating): void
    {
        // Lógica simple para actualizar rating
        // En una implementación real, podrías promediar todas las calificaciones
        $this->update(['rating' => $newRating]);
    }

    /**
     * Calcular tarifa de envío según las provincias de origen y destino
     */
    public function calculateDeliveryRate(string $fromProvince, string $toProvince): float
    {
        // Si es la misma provincia, usar tarifa intra-provincial
        if ($fromProvince === $toProvince) {
            return (float) $this->intra_province_rate;
        }

        // Si son provincias diferentes, usar tarifa inter-provincial
        return (float) $this->inter_province_rate;
    }

    /**
     * Obtener tarifa para una orden específica
     */
    public function getRateForOrder(\App\Models\Order $order): float
    {
        $storeProvince = 'Pichincha'; // Provincia por defecto de la tienda
        $deliveryProvince = $order->shipping_address['province'] ?? '';

        return $this->calculateDeliveryRate($storeProvince, $deliveryProvince);
    }

    // Relaciones
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function activeShipments()
    {
        return $this->shipments()->whereIn('status', [2, 3]); // Asignado o En camino
    }

    public function completedShipments()
    {
        return $this->shipments()->where('status', 4); // Entregado
    }
}
