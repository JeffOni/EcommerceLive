<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    /**
     * Una provincia tiene muchos cantones
     */
    public function cantons(): HasMany
    {
        return $this->hasMany(Canton::class);
    }

    /**
     * Una provincia tiene muchas direcciones
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
