<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'address',
        'receiver',
        'receiver_info',
        'default',
        // Nuevos campos de ubicación
        'province_id',
        'canton_id',
        'parish_id',
        'postal_code',
        'reference',
        'notes'
    ];

    protected $casts = [
        'receiver_info' => 'array', // Para almacenar datos del receptor como un array
        'default' => 'boolean', // Para manejar el campo default como booleano
    ];

    /**
     * Una dirección pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una dirección pertenece a una provincia
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Una dirección pertenece a un cantón
     */
    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class);
    }

    /**
     * Una dirección pertenece a una parroquia
     */
    public function parish(): BelongsTo
    {
        return $this->belongsTo(Parish::class);
    }

    /**
     * Obtener la dirección completa formateada
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [];

        if ($this->address)
            $parts[] = $this->address;
        if ($this->parish)
            $parts[] = $this->parish->name;
        if ($this->canton)
            $parts[] = $this->canton->name;
        if ($this->province)
            $parts[] = $this->province->name;

        return implode(', ', $parts);
    }
}
