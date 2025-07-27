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
    public function getEffectivePrice(): float
    {
        return (float) ($this->custom_price ?? $this->product->price);
    }

    /**
     * Accessor para obtener el precio actual (considerando ofertas)
     */
    protected function currentPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                $basePrice = $this->getEffectivePrice();

                if (!$this->product->is_on_valid_offer) {
                    return $basePrice;
                }

                // Si hay precio fijo de oferta, calcularlo proporcionalmente
                if ($this->product->offer_price && $this->product->offer_price > 0) {
                    // Calcular el factor de descuento del producto
                    $discountFactor = $this->product->offer_price / $this->product->price;
                    return $basePrice * $discountFactor;
                }

                // Si hay porcentaje de descuento, aplicarlo al precio base de la variante
                if ($this->product->offer_percentage && $this->product->offer_percentage > 0) {
                    return $basePrice * (1 - ($this->product->offer_percentage / 100));
                }

                return $basePrice;
            }
        );
    }

    /**
     * Accessor para verificar si la variante está en oferta válida
     */
    protected function isOnValidOffer(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->product->is_on_valid_offer
        );
    }

    /**
     * Accessor para obtener el porcentaje de descuento
     */
    protected function discountPercentage(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->product->discount_percentage ?? 0
        );
    }

    /**
     * Accessor para obtener el monto de ahorro
     */
    protected function savingsAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->product->is_on_valid_offer) {
                    return 0;
                }

                $originalPrice = $this->getEffectivePrice();
                $currentPrice = $this->current_price;

                return $originalPrice - $currentPrice;
            }
        );
    }

    /**
     * Accessor para obtener el precio original de la variante
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getEffectivePrice()
        );
    }

    //Relacion muchos a muchos con Feature

    public function features()
    {
        return $this->belongsToMany(Feature::class) //no se pasa el withpivot porque no hay cmapo adicionales en la tabla pivote o intermedia
            ->withTimestamps(); //para que se agreguen los campos de tiempo
    }
}
