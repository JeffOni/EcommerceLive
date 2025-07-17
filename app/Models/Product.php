<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

/**
 * Modelo Product: Representa los productos disponibles en el ecommerce
 *
 * Este modelo es central para el sistema de ecommerce, permitiendo gestionar
 * el catálogo completo de productos con sus características, opciones y variantes.
 */
class Product extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sku',          // Código único de producto (Stock Keeping Unit)
        'name',         // Nombre del producto
        'description',  // Descripción detallada del producto
        'general_features', // Características generales del producto
        'recommended_preparation', // Preparación recomendada
        'image_path',   // Ruta a la imagen principal del producto
        'image_2',      // Ruta a la segunda imagen del producto
        'image_3',      // Ruta a la tercera imagen del producto
        'price',        // Precio base del producto
        'stock',        // Cantidad disponible en inventario
        'subcategory_id' // ID de la subcategoría a la que pertenece
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url($this->image_path),
        );
    }

    protected function image2(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image_2 ? Storage::url($this->image_2) : null,
        );
    }

    protected function image3(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image_3 ? Storage::url($this->image_3) : null,
        );
    }


    /**
     * Relación uno a muchos inversa con Subcategory
     *
     * Cada producto pertenece a una subcategoría específica.
     * Esta relación permite acceder a los datos de la subcategoría
     * a la que pertenece el producto, incluyendo su categoría y familia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Relación uno a muchos con Variant
     *
     * Un producto puede tener múltiples variantes basadas en combinaciones
     * de características (color, tamaño, etc.).
     * Cada variante puede tener su propio precio, stock e imagen.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    /**
     * Relación muchos a muchos con Option
     *
     * Un producto puede tener múltiples opciones (color, tamaño, etc.)
     * y cada opción puede estar presente en múltiples productos.
     *
     * Esta relación personalizada incluye datos adicionales en la tabla pivote,
     * como las características específicas disponibles para cada producto-opción.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function scopeVerifyFamily($query, $family_id)
    {
        $query->when($family_id, function ($query) use ($family_id) {
            $query->whereHas('subcategory.category', function ($query) use ($family_id) {
                $query->where('family_id', $family_id);
            });
        });
    }
    public function scopeVerifyCategory($query, $category_id)
    {
        $query->when($category_id, function ($query) use ($category_id) {
            $query->whereHas('subcategory', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        });
    }
    public function scopeVerifySubcategory($query, $subcategory_id)
    {
        $query->when($subcategory_id, function ($query) use ($subcategory_id) {
            $query->where('subcategory_id', $subcategory_id);
        });
    }

    public function scopeCustomOrder($query, $orderBy)
    {
        $query->when($orderBy == 1, function ($query) {
            $query->orderBy('created_at', 'desc');
        })
            ->when($orderBy == 2, function ($query) {
                $query->orderBy('price', 'desc');
            })
            ->when($orderBy == 3, function ($query) {
                $query->orderBy('price', 'asc');
            });
    }

    public function scopeSelectFeatures($query, $selected_features)
    {
        $query->when($selected_features, function ($query) use ($selected_features) {
            $query->whereHas('variants.features', function ($query) use ($selected_features) {
                $query->whereIn('features.id', $selected_features);
            });
        });
    }

    public function scopeSearch($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function options()
    {
        return $this->belongsToMany(Option::class)
            ->using(OptionProduct::class) // Usa la tabla pivote OptionProduct para manejar la relación
            ->withPivot('features')       // Incluye el campo 'features' de la tabla pivote en los resultados
            // 'features' contiene las características específicas disponibles
            ->withTimestamps();           // Incluye los campos created_at y updated_at en la tabla pivote
    }
}
