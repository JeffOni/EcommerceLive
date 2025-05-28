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

    public function scopeVerifyFamily($query, $family_id)
    {
        $query->when($family_id, function ($query) use ($family_id) {
            $query->whereHas('products.subcategory.category', function ($query) use ($family_id) {
                $query->where('family_id', $family_id);
            })
                // Carga las características (features) de cada opción, filtrando solo aquellas que tengan variantes
                // cuyos productos también pertenezcan a la familia seleccionada
                ->with([
                    'features' => function ($query) use ($family_id) {
                        $query->whereHas('variants.product.subcategory.category', function ($query) use ($family_id) {
                            $query->where('family_id', $family_id);
                        });
                    },
                ]);
        });
    }

    public function scopeVerifyCategory($query, $category_id)
    {
        $query->when($category_id, function ($query) use ($category_id) {
            $query->whereHas('products.subcategory', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })->with([
                'features' => function ($query) use ($category_id) {
                    $query->whereHas('variants.product.subcategory', function ($query) use ($category_id) {
                        $query->where('category_id', $category_id);
                    });
                },
            ]);
        });
    }
    public function scopeVerifySubcategory($query, $subcategory_id)
    {
        $query->when($subcategory_id, function ($query) use ($subcategory_id) {
            $query->whereHas('products', function ($query) use ($subcategory_id) {
                $query->where('subcategory_id', $subcategory_id);
            })->with([
                'features' => function ($query) use ($subcategory_id) {
                    $query->whereHas('variants.product', function ($query) use ($subcategory_id) {
                        $query->where('subcategory_id', $subcategory_id);
                    });
                },
            ]);
        });
    }
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
