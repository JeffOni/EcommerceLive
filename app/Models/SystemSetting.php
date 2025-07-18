<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Obtener el valor parseado según el tipo
     */
    public function getParsedValueAttribute()
    {
        return match ($this->type) {
            'json' => json_decode($this->value, true),
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'decimal', 'float' => (float) $this->value,
            default => $this->value,
        };
    }

    /**
     * Establecer valor según el tipo
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->type) {
            'json' => is_array($value) ? json_encode($value) : $value,
            'boolean' => $value ? 'true' : 'false',
            default => (string) $value,
        };
    }

    /**
     * Obtener configuración por clave
     */
    public static function getSetting(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->parsed_value : $default;
    }

    /**
     * Establecer configuración
     */
    public static function set(string $key, $value, string $type = 'text'): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type
            ]
        );
    }

    /**
     * Obtener configuraciones públicas
     */
    public static function getPublicSettings(): array
    {
        return static::where('is_public', true)
            ->select(['key', 'value', 'type'])
            ->get()
            ->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->parsed_value];
            })
            ->toArray();
    }
}
