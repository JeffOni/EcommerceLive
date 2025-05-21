<?php

namespace App\Livewire;

use App\Models\Option;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Filter extends Component
{
    use WithPagination;
    // Propiedad pública para almacenar el ID de la familia seleccionada
    // ID de la familia seleccionada, se utiliza para filtrar las opciones
    public $family_id;

    // Colección de opciones filtradas según la familia
    public $options;

    public $selected_features = [];
    // Propiedad pública para almacenar las características seleccionadas

    public $orderBy = 1;

    // Método que se ejecuta al montar el componente
    public function mount()
    {
        // Consulta las opciones que tienen productos cuya subcategoría pertenece a una categoría de la familia seleccionada
        $this->options  = Option::whereHas('products.subcategory.category', function ($query) {
            $query->where('family_id', $this->family_id);
        })
            // Carga las características (features) de cada opción, filtrando solo aquellas que tengan variantes
            // cuyos productos también pertenezcan a la familia seleccionada
            ->with([
                'features' => function ($query) {
                    $query->whereHas('variants.product.subcategory.category', function ($query) {
                        $query->where('family_id', $this->family_id);
                    });
                },
            ])->get()->toArray();
    }

    // Renderiza la vista asociada al componente Livewire
    public function render()
    {
        $products = Product::whereHas('subcategory.category', function ($query) {
            $query->where('family_id', $this->family_id);
        })
            ->when($this->orderBy == 1, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when($this->orderBy == 2, function ($query) {
                $query->orderBy('price', 'desc');
            })
            ->when($this->orderBy == 3, function ($query) {
                $query->orderBy('price', 'asc');
            })
            ->when($this->selected_features, function ($query) {
                $query->whereHas('variants.features', function ($query) {
                    $query->whereIn('features.id', $this->selected_features);
                });
            })
            ->paginate(12);
        // Devuelve la vista 'livewire.filter' y pasa las opciones filtradas

        return view('livewire.filter', compact('products'));
    }
}
