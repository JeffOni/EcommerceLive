<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    //
    protected $fillable = [
        'sku',
        'image_path',
        'product_id'
    ];
    //Relacion uno a muchos inversa con Product

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    //Relacion muchos a muchos con Feature

    public function features()
    {
        return $this->belongsToMany(Feature::class) //no se pasa el withpivot porque no hay cmapo adicionales en la tabla pivote o intermedia
            ->withTimestamps(); //para que se agreguen los campos de tiempo
    }
}
