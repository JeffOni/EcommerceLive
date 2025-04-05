<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    //
    protected $fillable = [
        'value',
        'description',
        'option_id'
    ];
    //Relacion uno a muchos inversa con Option

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    //Relacion muchos a muchos con Variant

    public function variants()
    {
        return $this->belongsToMany(Variant::class) //no se pasa el withpivot porque no hay cmapo adicionales en la tabla pivote o intermedia
            ->withTimestamps(); //para que se agreguen los campos de tiempo
    }
}
