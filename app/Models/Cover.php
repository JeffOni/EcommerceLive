<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Modelo para gestionar las imágenes de portada/banners del sitio web.
 * Permite administrar carruseles y banners promocionales con fechas de activación.
 */
class Cover extends Model
{
    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image_path',   // Ruta a la imagen almacenada
        'title',        // Título descriptivo de la portada
        'start_at',     // Fecha de inicio de visualización
        'end_at',       // Fecha de finalización de visualización
        'is_active',    // Estado de activación (true/false)
        'order',        // Posición de ordenamiento en el carrusel
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',   // Convierte el campo a instancia de datetime
        'end_at' => 'datetime',     // Convierte el campo a instancia de datetime
        'is_active' => 'boolean',   // Asegura que el campo sea booleano
    ];

    /**
     * Atributo calculado que devuelve la URL completa de la imagen.
     * Transforma la ruta relativa almacenada en la base de datos en una URL accesible.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url($this->image_path),
        );
    }
}
