<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'province',
        'canton',
        'parish',
        'reference',
        'phone',
        'email',
        'coordinates',
        'working_hours',
        'is_main',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'coordinates' => 'array',
        'is_main' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope para oficinas activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para oficina principal
     */
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    /**
     * Obtener la oficina principal
     */
    public static function getMainOffice()
    {
        return self::main()->active()->first();
    }

    /**
     * Formatear dirección completa
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->reference) {
            $address .= ' (' . $this->reference . ')';
        }
        return $address . ', ' . $this->parish . ', ' . $this->canton . ', ' . $this->province;
    }

    /**
     * Obtener coordenadas formateadas
     */
    public function getCoordinatesStringAttribute()
    {
        if ($this->coordinates) {
            return $this->coordinates['lat'] . ', ' . $this->coordinates['lng'];
        }
        return null;
    }
}
