<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Canton extends Model
{
    protected $fillable = [
        'name',
        'code',
        'province_id'
    ];

    /**
     * Un cantón pertenece a una provincia
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Un cantón tiene muchas parroquias
     */
    public function parishes(): HasMany
    {
        return $this->hasMany(Parish::class);
    }

    /**
     * Un cantón tiene muchas direcciones
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
