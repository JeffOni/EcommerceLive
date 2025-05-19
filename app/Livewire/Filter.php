<?php

namespace App\Livewire;

use App\Models\Option;
use Livewire\Component;

class Filter extends Component
{
    // ID de la familia seleccionada, se utiliza para filtrar las opciones
    public $family_id;

    // Colección de opciones filtradas según la familia
    public $options;

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
            ])->get();
    }

    // Renderiza la vista asociada al componente Livewire
    public function render()
    {
        return view('livewire.filter');
    }
}
