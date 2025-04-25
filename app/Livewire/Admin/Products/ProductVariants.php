<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature;
use App\Models\Option;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Componente Livewire que maneja las variantes de productos en el panel de administración
 * Permite seleccionar opciones y sus características asociadas para crear variantes de productos
 */
class ProductVariants extends Component
{
    /**
     * Controla la visibilidad del modal para añadir/editar variantes
     * @var bool
     */
    public $openModal = false;

    /**
     * Almacena todas las opciones disponibles (ej: color, tamaño, etc.)
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $options;

    public $product;

    /**
     * Almacena la información de la variante actual
     * option_id: ID de la opción seleccionada (ej: color, tamaño)
     * features: Características seleccionadas para esta opción
     * @var array
     */
    public $variants = [
        'option_id' => '',
        'features' => [
            [
                'id' => '',
                'value' => '',
                'description' => '',
            ],
        ],
    ];

    /**
     * Método que se ejecuta al montar el componente
     * Carga todas las opciones disponibles desde la base de datos
     */
    public function mount()
    {
        $this->options = Option::all();
    }

    /**
     * Método que se ejecuta automáticamente cuando cambia el valor de variants.option_id
     * Resetea las características cuando se selecciona una nueva opción
     */
    public function updatedVariantsOptionId()
    {
        // Reinicia las características a un array con un elemento vacío inicial
        $this->variants['features'] = [
            [
                'id' => '',
                'value' => '',
                'description' => '',
            ],
        ];
    }

    /**
     * Método que no se está utilizando actualmente
     * Se mantiene por compatibilidad con posibles llamadas manuales
     */
    public function updateOptionId()
    {
        // Reinicia las características a un array vacío
        $this->variants['features'] = [
            [
                'id' => '',
                'value' => '',
                'description' => '',
            ],
        ];
    }

    /**
     * Propiedad computada que obtiene las características disponibles
     * para la opción seleccionada actualmente
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    #[Computed()]
    public function features()
    {
        // Obtiene el ID de opción seleccionado o null si no existe
        $optionId = $this->variants['option_id'] ?? null;

        // Valida que el ID sea un valor numérico antes de realizar la consulta
        // Si no es numérico, retorna una colección vacía
        return is_numeric($optionId)
            ? Feature::where('option_id', $optionId)->get()
            : collect();
    }

    public function addFeature()
    {
        // Agrega una nueva característica al array de características
        $this->variants['features'][] = [
            'id' => '',
            'value' => '',
            'description' => '',
        ];
    }

    public function feature_change($index)
    {
        $feature = Feature::find($this->variants['features'][$index]['id']);
        if ($feature) {
            $this->variants['features'][$index]['value'] = $feature->value;
            $this->variants['features'][$index]['description'] = $feature->description;
        } else {
            $this->variants['features'][$index]['value'] = '';
            $this->variants['features'][$index]['description'] = '';
        }
    }
    /**
     * Elimina una característica específica del array de características
     * @param int $index Índice de la característica a eliminar
     */
    public function removeFeature($index)
    {
        // Verifica que el índice sea válido antes de eliminar
        if (isset($this->variants['features'][$index])) {
            unset($this->variants['features'][$index]);
            $this->variants['features'] = array_values($this->variants['features']); // Reindexa el array
        }
    }

    public function save()
    {
        $this->validate([
            'variants.option_id' => 'required|exists:options,id',
            'variants.features' => 'required|array|min:1',
            'variants.features.*.id' => 'required|exists:features,id',
        ], [], [
            'variants.option_id' => 'Opción',
            'variants.features' => 'Características',
            'variants.features.*.id' => 'Valor de característica',
        ]);

        $this->product->options()->attach($this->variants['option_id'], [
            'features' => $this->variants['features'],
        ]);
        $this->reset([
            'openModal',
            'variants',
        ]);
    }

    /**
     * Renderiza la vista del componente
     */
    public function render()
    {
        return view('livewire.admin.products.product-variants');
    }
}
