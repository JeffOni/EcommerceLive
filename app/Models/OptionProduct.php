<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OptionProduct extends Pivot
{
    //
    protected $casts = [
        'features' => 'array', // Convierte el campo features a un array
    ];
}
