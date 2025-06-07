<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = [
        'user_id',
        'type',
        'provincia',
        'canton',
        'parroquia',
        'reference',
        'address',
        'receiver',
        'receiver_info',
        'default'
    ];
    protected $casts = [
        'receiver_info' => 'array', // Para almacenar datos del receptor como un array
        'default' => 'boolean', // Para manejar el campo default como booleano
    ];
    public function user()
    {
        return $this->belongsTo(User::class); // Relación con el modelo User
    }
}
