<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature;
use App\Models\Option;
use App\Models\Variant;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;

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

    /**
     * Modelo del producto que se está editando
     * @var \App\Models\Product
     */
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
     * Control de la interfaz para agregar características a opciones existentes
     * $showAddFeature: Almacena el ID de la opción para la que se muestra el formulario de agregar características
     * $featuresToAdd: Almacena características seleccionadas temporalmente por opción antes de confirmar
     */
    public $featuresToAdd = [];
    public $showAddFeature = null;

    /**
     * Define los eventos que este componente escucha
     * featureAdded: Evento emitido cuando se añaden características en el componente AddFeatureToOption
     */
    protected $listeners = ['featureAdded' => 'handleFeatureAdded'];

    /**
     * Método que se ejecuta al montar el componente
     * Carga todas las opciones disponibles desde la base de datos
     */
    public function mount()
    {
        // Cargamos todas las opciones del sistema al inicializar el componente
        $this->options = Option::all();
    }

    /**
     * Método que se ejecuta automáticamente cuando cambia el valor de variants.option_id
     * Resetea las características cuando se selecciona una nueva opción
     */
    public function updatedVariantsOptionId()
    {
        // Reinicia las características a un array con un elemento vacío inicial
        // Esto evita que se mantengan características de otras opciones seleccionadas previamente
        $this->variants['features'] = [
            [
                'id' => '',
                'value' => '',
                'description' => '',
            ],
        ];
    }

    /**
     * Método legacy que no se está utilizando actualmente
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
     * Nota: Esta consulta puede optimizarse con caché si el tiempo de respuesta es un problema
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

    /**
     * Propiedad computada que obtiene las características disponibles para una opción
     * utilizando caché para mejorar el rendimiento
     *
     * @param int $optionId ID de la opción
     * @return \Illuminate\Database\Eloquent\Collection
     */
    #[Computed()]
    public function getAvailableFeaturesForOption($optionId)
    {
        // Usar caché para almacenar las características disponibles por 5 minutos
        // La clave de caché incluye el ID del producto y de la opción para mantener unicidad
        $cacheKey = "available-features-for-option-{$optionId}-{$this->product->id}";

        // Importante: No usar remember aquí para que siempre se obtengan datos frescos
        // después de añadir o eliminar características
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $option = $this->product->options->find($optionId);
        if (!$option) {
            return collect();
        }

        $addedIds = collect($option->pivot->features ?? [])->pluck('id')->toArray();

        $availableFeatures = Feature::where('option_id', $optionId)
            ->whereNotIn('id', $addedIds)
            ->get();

        // Guardar en caché por un tiempo más corto para evitar datos obsoletos
        Cache::put($cacheKey, $availableFeatures, 120); // 2 minutos

        return $availableFeatures;
    }

    /**
     * Agrega una nueva entrada vacía al array de características
     * para que el usuario pueda seleccionar otra característica
     */
    public function addFeature()
    {
        // Agrega una nueva característica vacía al array de características
        $this->variants['features'][] = [
            'id' => '',
            'value' => '',
            'description' => '',
        ];
    }

    /**
     * Se ejecuta cuando el usuario cambia la selección de una característica
     * Actualiza automáticamente los campos value y description con los datos de la BD
     *
     * @param int $index Índice de la característica en el array
     */
    public function feature_change($index)
    {
        // Busca la característica por su ID
        $feature = Feature::find($this->variants['features'][$index]['id']);
        if ($feature) {
            // Si la característica existe, actualiza los campos value y description
            $this->variants['features'][$index]['value'] = $feature->value;
            $this->variants['features'][$index]['description'] = $feature->description;
        } else {
            // Si la característica no existe (o se seleccionó una opción vacía), limpia los campos
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
            // Reindexa el array para mantener índices consecutivos
            $this->variants['features'] = array_values($this->variants['features']);
        }
    }

    /**
     * Elimina una característica existente de una opción del producto
     *
     * @param int $option_id ID de la opción
     * @param int $feature_id ID de la característica a eliminar
     */
    public function deleteFeature($option_id, $feature_id)
    {
        // Actualiza la relación existente en la tabla pivote para eliminar solo la característica específica
        $this->product->options()->updateExistingPivot($option_id, [
            'features' => array_filter($this->product->options->find($option_id)->pivot->features, function ($feature) use ($feature_id) {
                return $feature['id'] != $feature_id;
            }),
        ]);

        // Refresca el modelo para obtener los cambios
        $this->product = $this->product->fresh();

        // Regenera las variantes del producto para reflejar el cambio
        $this->generateVariants();
    }

    /**
     * Elimina completamente una opción del producto
     *
     * @param int $option_id ID de la opción a eliminar
     */
    public function deleteOption($option_id)
    {
        // Elimina la relación entre la opción y el producto
        $this->product->options()->detach($option_id);

        // Refresca el modelo para obtener los cambios
        $this->product = $this->product->fresh();

        // Regenera las variantes del producto para reflejar el cambio
        $this->generateVariants();
    }

    /**
     * Guarda una nueva opción con sus características en el producto
     * Valida que no haya opciones duplicadas y que se hayan seleccionado características
     */
    public function save()
    {
        // Validación para evitar features duplicados
        $featureIds = array_column($this->variants['features'], 'id');
        if (count($featureIds) !== count(array_unique($featureIds))) {
            $this->addError('variants.features', 'No se permiten características duplicadas.');
            return;
        }

        // Validación de datos del formulario
        $this->validate([
            'variants.option_id' => 'required|exists:options,id',
            'variants.features' => 'required|array|min:1',
            'variants.features.*.id' => 'required|exists:features,id',
        ], [], [
            'variants.option_id' => 'Opción',
            'variants.features' => 'Características',
            'variants.features.*.id' => 'Valor de característica',
        ]);

        // Verificar si el producto ya tiene esta opción (no se permiten opciones duplicadas)
        if ($this->product->options->contains('id', $this->variants['option_id'])) {
            $option = Option::find($this->variants['option_id']);
            $this->addError('variants.option_id', "Este producto ya tiene la opción '{$option->name}'. No se permiten opciones duplicadas.");
            return;
        }

        // Guarda la opción seleccionada con sus características en la tabla pivote
        $this->product->options()->attach($this->variants['option_id'], [
            'features' => $this->variants['features'],
        ]);

        // Refresca el modelo para obtener los cambios
        $this->product = $this->product->fresh();

        // Genera las variantes del producto con la nueva opción
        $this->generateVariants();

        // Reinicia el formulario, cierra el modal y limpia errores
        $this->reset([
            'openModal',
            'variants',
        ]);
        $this->resetErrorBag();
    }

    /**
     * Limpia errores al abrir/cerrar el modal
     * @param bool $value Estado actual del modal
     */
    public function updatedOpenModal($value)
    {
        if (!$value) {
            $this->resetErrorBag();
        }
    }

    /**
     * Genera todas las combinaciones posibles de variantes del producto
     * basadas en las opciones y características seleccionadas
     */
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
     * Genera todas las combinaciones posibles de características recursivamente
     * Este algoritmo puede optimizarse para mejorar el rendimiento en productos con muchas opciones
     *
     * @param array $arrays Arreglo de arreglos de características
     * @param int $index Índice actual en el arreglo
     * @param array $combination Combinación actual
     * @return array Arreglo de combinaciones generadas
     */
    public function generateCombinations($arrays, $index = 0, $combination = [])
    {
        // Caso base: cuando llegamos al final del array, devolvemos la combinación actual
        if ($index === count($arrays)) {
            return [$combination];
        }

        $result = [];
        // Para cada elemento en el array actual
        foreach ($arrays[$index] as $item) {
            // Creamos una copia de la combinación actual
            $temporalCombination = $combination;
            // Agregamos el elemento actual a la combinación
            $temporalCombination[] = $item['id'];
            // Recursivamente generamos combinaciones para los arrays restantes
            $result = array_merge($result, $this->generateCombinations($arrays, $index + 1, $temporalCombination));
        }

        return $result;
    }

    /**
     * Método para mostrar/ocultar el bloque de agregar características para una opción específica
     * Este es el que se activa al hacer clic en el botón + de AddFeatureToOption
     *
     * @param int $optionId ID de la opción
     */
    public function toggleAddFeature($optionId)
    {
        // Forzar refresco de la caché antes de mostrar el formulario
        $cacheKey = "available-features-for-option-{$optionId}-{$this->product->id}";
        Cache::forget($cacheKey);

        // Si ya está mostrando el mismo ID, lo cierra. Si es otro ID o está cerrado, lo abre
        $this->showAddFeature = $this->showAddFeature === $optionId ? null : $optionId;
    }

    /**
     * Método para agregar las características seleccionadas a una opción del producto
     * Este método no se usa actualmente, pero se mantiene como alternativa al handleFeatureAdded
     *
     * @param int $optionId ID de la opción
     */
    public function addFeaturesToOption($optionId)
    {
        // Obtener los IDs de features seleccionados
        $selected = collect($this->featuresToAdd[$optionId] ?? [])->filter()->keys()->toArray();
        if (empty($selected)) {
            $this->addError('featuresToAdd.' . $optionId, 'Selecciona al menos una característica.');
            return;
        }
        // Obtener los features actuales de la opción
        $optionPivot = $this->product->options->find($optionId)?->pivot;
        $currentFeatures = $optionPivot ? ($optionPivot->features ?? []) : [];
        $currentIds = collect($currentFeatures)->pluck('id')->toArray();
        // Obtener los nuevos features a agregar
        $newFeatures = \App\Models\Feature::whereIn('id', $selected)->get()->map(function($f) {
            return [
                'id' => $f->id,
                'value' => $f->value,
                'description' => $f->description,
            ];
        })->toArray();
        // Unir los actuales con los nuevos (sin duplicados)
        $allFeatures = array_merge($currentFeatures, array_filter($newFeatures, function($f) use ($currentIds) {
            return !in_array($f['id'], $currentIds);
        }));
        // Actualizar la relación pivot
        $this->product->options()->updateExistingPivot($optionId, [
            'features' => $allFeatures
        ]);
        $this->product = $this->product->fresh();
        $this->generateVariants();
        // Limpiar selección y ocultar formulario
        unset($this->featuresToAdd[$optionId]);
        $this->showAddFeature = null;
    }

    /**
     * Maneja el evento featureAdded que emite el componente AddFeatureToOption
     * Agrega las características seleccionadas a la opción del producto
     *
     * @param int $optionId ID de la opción
     * @param array $selectedFeatures IDs de las características seleccionadas
     */
    #[On('featureAdded')]
    public function handleFeatureAdded($optionId, $selectedFeatures)
    {
        // Verificar que hay características seleccionadas
        if (empty($selectedFeatures)) {
            return;
        }

        // Obtener los features actuales de la opción
        $optionPivot = $this->product->options->find($optionId)?->pivot;
        $currentFeatures = $optionPivot ? ($optionPivot->features ?? []) : [];
        $currentIds = collect($currentFeatures)->pluck('id')->toArray();

        // Obtener directamente de la base de datos para asegurar datos frescos
        $newFeatures = Feature::whereIn('id', $selectedFeatures)->get()->map(function($f) {
            return [
                'id' => $f->id,
                'value' => $f->value,
                'description' => $f->description,
            ];
        })->toArray();

        // Unir los actuales con los nuevos (sin duplicados)
        $allFeatures = array_merge($currentFeatures, array_filter($newFeatures, function($f) use ($currentIds) {
            return !in_array($f['id'], $currentIds);
        }));

        // Actualizar la relación pivot
        $this->product->options()->updateExistingPivot($optionId, [
            'features' => $allFeatures
        ]);

        // Refrescar el producto y regenerar variantes
        $this->product = $this->product->fresh();
        $this->generateVariants();

        // Limpiar caché para esta opción específica
        $cacheKey = "available-features-for-option-{$optionId}-{$this->product->id}";
        Cache::forget($cacheKey);

        // Enviar evento para que otros componentes AddFeatureToOption se actualicen
        $this->dispatch('hideAddFeatureBlock');

        // Forzar a que el componente se vuelva a renderizar completamente
        $this->showAddFeature = null;
    }

    /**
     * Escucha el evento toggle-feature-form emitido por el botón + en el componente AddFeatureToOption
     * y llama al método toggleAddFeature con el ID de la opción
     *
     * @param int $optionId ID de la opción
     */
    #[On('toggle-feature-form')]
    public function handleToggleFeatures($optionId)
    {
        // Este es el método que se activa al hacer clic en el botón + del componente AddFeatureToOption
        // ÁREA DE OPTIMIZACIÓN 4: Este es el punto de entrada cuando el usuario hace clic en el botón +
        $this->toggleAddFeature($optionId);
    }

    /**
     * Renderiza la vista del componente
     */
    public function render()
    {
        return view('livewire.admin.products.product-variants');
    }
}
