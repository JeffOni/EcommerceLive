<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use App\Models\Feature;
use Illuminate\Support\Facades\Log;

/**
 * Componente AddFeatureToOption
 * 
 * Este componente maneja la interfaz para agregar características (features) a una opción de producto.
 * Muestra un botón "+" junto al nombre de cada opción que, al hacer clic, indica al componente padre
 * (ProductVariants) que debe mostrar el formulario de selección de características disponibles.
 */
class AddFeatureToOption extends Component
{
    /**
     * La opción actual a la que se agregarán características
     * @var \App\Models\Option
     */
    public $option;
    
    /**
     * El producto al que pertenece la opción
     * @var \App\Models\Product
     */
    public $product;
    
    /**
     * Controla si el bloque de selección está visible (no se usa actualmente ya que el formulario
     * se muestra en ProductVariants, se mantiene para posibles implementaciones futuras)
     * @var bool
     */
    public $visible = false;
    
    /**
     * Almacena temporalmente las características seleccionadas
     * @var array
     */
    public $selectedFeatures = [];

    /**
     * Lista de eventos que este componente escucha
     * @var array
     */
    protected $listeners = ['hideAddFeatureBlock' => 'hideBlock'];

    /**
     * Inicializa el componente con los datos recibidos
     * @param \App\Models\Option $option La opción a la que se agregarán características
     * @param \App\Models\Product $product El producto al que pertenece la opción
     * @param bool $visible Si el bloque de selección debe mostrarse inicialmente
     */
    public function mount($option, $product, $visible = false)
    {
        $this->option = $option;
        $this->product = $product;
        $this->visible = $visible;
    }

    /**
     * Propiedad computada que obtiene las características disponibles para agregar
     * Filtra las características que ya están agregadas a la opción del producto
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableFeaturesProperty()
    {
        try {
            // Obtener los IDs de features ya agregados a la opción de este producto
            if (isset($this->option->pivot) && isset($this->option->pivot->features)) {
                $addedIds = collect($this->option->pivot->features)->pluck('id')->toArray();
            } else {
                // Obtener directamente del modelo Product la relación con sus opciones
                $productOption = $this->product->options->find($this->option->id);
                if ($productOption && isset($productOption->pivot->features)) {
                    $addedIds = collect($productOption->pivot->features)->pluck('id')->toArray();
                } else {
                    $addedIds = [];
                }
            }

            // Obtener los features que pertenecen a la opción y que no están ya agregados
            return Feature::where('option_id', $this->option->id)
                ->whereNotIn('id', $addedIds)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error al obtener features disponibles: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Muestra el bloque de selección de características
     * (No se usa actualmente ya que el formulario se muestra en ProductVariants)
     */
    public function showBlock()
    {
        $this->visible = true;
    }

    /**
     * Oculta el bloque de selección de características y limpia las selecciones
     */
    public function hideBlock()
    {
        $this->visible = false;
        $this->selectedFeatures = [];
    }

    /**
     * Obtiene el ID del contenedor donde se mostrará el formulario de características
     * (No se usa actualmente, se mantiene para implementaciones futuras)
     * @return string
     */
    public function getFormTargetId()
    {
        return 'feature-form-' . $this->option->id;
    }

    /**
     * Procesa el formulario cuando se seleccionan características y se confirma
     * Emite un evento al componente padre con las características seleccionadas
     */
    public function addSelectedFeatures()
    {
        $selected = array_keys(array_filter($this->selectedFeatures));
        if (empty($selected)) {
            $this->addError('selectedFeatures', 'Selecciona al menos una característica.');
            return;
        }
        // Emitir evento al componente padre con el ID de la opción y los IDs de las características seleccionadas
        $this->dispatch('featureAdded', optionId: $this->option->id, selectedFeatures: $selected);
        $this->hideBlock();
    }

    /**
     * Esta función se ejecuta cuando se hace clic en el botón "+"
     * Emite un evento al componente padre para que muestre el formulario de selección
     */
    public function toggleInParent()
    {
        // Emitir evento al componente padre con el ID de la opción
        $this->dispatch('toggle-feature-form', optionId: $this->option->id);
    }

    /**
     * Renderiza la vista del componente
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.products.add-feature-to-option');
    }
}
