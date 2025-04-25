<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //

    protected $fillable = [
        'name',
        'type',
    ];

    //Relacion muchos a muchos con Product

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(OptionProduct::class) //para usar la tabla pivote OptionProduct
            ->withPivot('features') //with pivot sirve para traer el valor de la tabla pivote
            // ya que laravel interpreta que solo estan los campos de las llaves de la tabla intermedia en este caso exites el campo extra value
            ->withTimestamps(); //para que se agreguen los campos de tiempo
    }

    //Relacion uno a muchos con Feature

    public function features()
    {
        return $this->hasMany(Feature::class);
    }
}
