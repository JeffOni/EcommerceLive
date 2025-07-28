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

    /**
     * PROPIEDAD: $search
     *
     * Almacena el término de búsqueda actual ingresado por el usuario
     * Esta propiedad es pública para permitir binding con Livewire
     * Se utiliza en el scope Search del modelo Product para filtrar resultados
     *
     * @var string
     */
    public $search;

    // Método que se ejecuta al montar el componente
    public function mount()
    {
        $this->options = Option::verifyFamily($this->family_id)

            ->verifyCategory($this->category_id)
            ->verifySubcategory($this->subcategory_id)
            ->get()->toArray();
    }

    /**
     * LISTENER: search
     *
     * Escucha el evento 'search' enviado desde JavaScript (componente navigation)
     * Actualiza la propiedad $search y resetea la paginación para mostrar nuevos resultados
     *
     * @param string $search - Término de búsqueda recibido del frontend
     * @return void
     */
    #[On('search')]
    public function search($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    /**
     * MÉTODO: clearSearch()
     *
     * Limpia la búsqueda activa y resetea la paginación
     * Emite un evento JavaScript para sincronizar con los campos de búsqueda del navegador
     *
     * Funcionalidad:
     * - Resetea la propiedad $search a cadena vacía
     * - Vuelve a la primera página de resultados
     * - Emite evento 'clear-search-inputs' para limpiar campos de búsqueda en JavaScript
     *
     * @return void
     */
    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
        // Emitir evento para limpiar inputs en JavaScript del componente navigation
        $this->dispatch('clear-search-inputs');
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
        $products = Product::with('variants')
            ->VerifyFamily($this->family_id)
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
