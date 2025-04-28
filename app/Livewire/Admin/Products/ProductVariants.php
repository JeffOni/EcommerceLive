<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature;
use App\Models\Option;
use App\Models\Variant;
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

    public function deleteFeature($option_id, $feature_id)
    {
        // Actualiza la relación existente en la tabla pivote para eliminar solo la característica específica
        $this->product->options()->updateExistingPivot($option_id, [
            'features' => array_filter($this->product->options->find($option_id)->pivot->features, function ($feature) use ($feature_id) {
                return $feature['id'] != $feature_id;
            }),
        ]);

        $this->product = $this->product->fresh(); // Refresca el modelo para obtener los cambios

        $this->generateVariants(); // Genera las variantes del producto
    }

    public function deleteOption($option_id)
    {
        // Elimina la opción del producto
        $this->product->options()->detach($option_id);
        $this->product = $this->product->fresh(); // Refresca el modelo para obtener los cambios
        $this->generateVariants(); // Genera las variantes del producto
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

        // Verificar si el producto ya tiene esta opción
        if ($this->product->options->contains('id', $this->variants['option_id'])) {
            $option = Option::find($this->variants['option_id']);
            $this->addError('variants.option_id', "Este producto ya tiene la opción '{$option->name}'. No se permiten opciones duplicadas.");
            return;
        }

        $this->product->options()->attach($this->variants['option_id'], [
            'features' => $this->variants['features'],
        ]);

        $this->product = $this->product->fresh(); // Refresca el modelo para obtener los cambios

        $this->generateVariants(); // Genera las variantes del producto

        // Reinicia el array de variantes y cierra el modal

        $this->reset([
            'openModal',
            'variants',
        ]);
    }

    public function generateVariants()
    {
        // Verifica si el producto tiene opciones
        if ($this->product->options->isEmpty()) {
            $this->addError('variants.option_id', 'El producto no tiene opciones. Agrega opciones antes de generar variantes.');
            return;
        }

        // Obtiene las características de cada opción
        $features = $this->product->options->pluck('pivot.features')->toArray();

        // Genera todas las combinaciones posibles de características
        $combinations = $this->generateCombinations($features);

        // Elimina las variantes existentes antes de crear nuevas
        $this->product->variants()->delete();

        // Crea nuevas variantes con las combinaciones generadas
        foreach ($combinations as $combination) {
            $variant = Variant::create([
                'product_id' => $this->product->id,
            ]);
            $variant->features()->attach($combination);
        }

        // Refresca el modelo para obtener los cambios
        $this->product = $this->product->fresh();
    }
    /**
     * Genera todas las combinaciones posibles de características
     * @param array $arrays Arreglo de arreglos de características
     * @param int $index Índice actual en el arreglo
     * @param array $combination Combinación actual
     * @return array Arreglo de combinaciones generadas
     */

    public function generateCombinations($arrays, $index = 0, $combination = [])
    {
        if ($index === count($arrays)) {

            return [$combination];
        }

        $result = [];
        foreach ($arrays[$index] as $item) {

            $temporalCombination = $combination;

            $temporalCombination[] = $item['id'];

            $result = array_merge($result, $this->generateCombinations($arrays, $index + 1, $temporalCombination));
        }

        return $result;
    }


    /**
     * Renderiza la vista del componente
     */
    public function render()
    {
        return view('livewire.admin.products.product-variants');
    }
}
