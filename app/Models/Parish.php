<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parish extends Model
{
    protected $fillable = [
        'name',
        'code',
        'canton_id',
        'default_postal_code'
    ];

    /**
     * Una parroquia pertenece a un cantón
     */
    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class);
    }

    /**
     * Una parroquia tiene muchas direcciones
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Obtener la provincia a través del cantón
     */
    public function province(): BelongsTo
    {
        return $this->canton->province();
    }
}
