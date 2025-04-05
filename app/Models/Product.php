<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'sku',
        'name',
        'description',
        'image_path',
        'price',
        'subcategory_id'
    ];

    //Relacion uno a muchos inversa con Subcategory

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    //Relacion uno a muchos con Variant

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    //Relacion muchos a muchos con Option

    public function options()
    {
        return $this->belongsToMany(Option::class)
            ->withPivot('value') //with pivot sirve para traer el valor de la tabla pivote
            // ya que laravel interpreta que solo estan los campos de las llaves de la tabla intermedia en este caso exites el campo extra value
            ->withTimestamps(); //para que se agreguen los campos de tiempo
    }
}
