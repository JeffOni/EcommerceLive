<?php

namespace App\Livewire;

use App\Models\Option;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Filter extends Component
{
    use WithPagination;
    // Propiedad pública para almacenar el ID de la familia seleccionada
    // ID de la familia seleccionada, se utiliza para filtrar las opciones
    public $family_id;

    public $category_id;

    public $subcategory_id;

    // Colección de opciones filtradas según la familia
    public $options;

    public $selected_features = [];
    // Propiedad pública para almacenar las características seleccionadas

    public $orderBy = 1;

    public $search;

    // Método que se ejecuta al montar el componente
    public function mount()
    {
        $this->options = Option::verifyFamily($this->family_id)

            ->verifyCategory($this->category_id)
            ->verifySubcategory($this->subcategory_id)
            ->get()->toArray();
    }

    #[On('search')]
    public function search($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    // Método para limpiar la búsqueda
    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
        $this->dispatch('clear-search-inputs'); // Emitir evento para limpiar inputs en JS
    }

    // Método que se ejecuta al cambiar la familia seleccionada
    public function updatedFamilyId()
    {
        // Actualiza las opciones filtradas según la nueva familia seleccionada, usando when igual que en mount
        $this->options = Option::verifyFamily($this->family_id)
            ->verifyCategory($this->category_id)
            ->verifySubcategory($this->subcategory_id)
            // Carga las características (features) de cada opción, filtrando solo aquellas que tengan variantes
            // cuyos productos también pertenezcan a la familia seleccionada
            // y las subcategorías relacionadas
            ->get()->toArray();
    }

    // Renderiza la vista asociada al componente Livewire
    public function render()
    {
        $products = Product::VerifyFamily($this->family_id)
            ->VerifyCategory($this->category_id)
            ->VerifySubcategory($this->subcategory_id)
            ->CustomOrder($this->orderBy)
            ->SelectFeatures($this->selected_features)
            ->Search($this->search)
            ->paginate(12);
        // Devuelve la vista 'livewire.filter' y pasa las opciones filtradas

        return view('livewire.filter', compact('products'));
    }
}
