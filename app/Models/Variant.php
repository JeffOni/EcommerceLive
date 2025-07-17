<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Variant extends Model
{
    //
    protected $fillable = [
        'sku',
        'image_path',
        'custom_price', // Precio personalizado (NULL = usar precio base del producto)
        'stock',
        'product_id'
    ];
    //Relacion uno a muchos inversa con Product

    protected function image(): Attribute
    {
        //se usa el metodo get para obtener el valor de la propiedad image_path
        //un accesor es un metodo que se usa para transformar el valor de un atributo
        //en este caso se usa el metodo url de Storage para obtener la url de la imagen
        //si no hay imagen se devuelve la imagen por defecto
        return Attribute::make(
            get: fn($value) => $this->image_path ? Storage::url($this->image_path) : asset('img/no-image.png'),
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtiene el precio efectivo de la variante
     * Si tiene precio personalizado lo usa, sino usa el precio base del producto
     */
    public function getEffectivePrice()
    {
        return $this->custom_price ?? $this->product->price;
    }

    //Relacion muchos a muchos con Feature

    public function features()
    {
        return $this->belongsToMany(Feature::class) //no se pasa el withpivot porque no hay cmapo adicionales en la tabla pivote o intermedia
            ->withTimestamps(); //para que se agreguen los campos de tiempo
    }
}
