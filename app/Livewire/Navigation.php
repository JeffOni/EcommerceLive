<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Family;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Componente Livewire para la navegación
 * Gestiona la visualización y selección de familias de productos
 */
class Navigation extends Component
{
    /**
     * Almacena todas las familias de productos disponibles
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $families;

    /**
     * Almacena el ID de la familia seleccionada actualmente
     * @var int|null
     */
    public $familyId;

    /**
     * Inicializa el componente al cargarse
     * Carga todas las familias y establece la primera como seleccionada por defecto
     */
    public function mount()
    {
        // Obtiene todas las familias de la base de datos
        $this->families = Family::all();

        // Establece la primera familia como seleccionada por defecto (si existe)
        $this->familyId = $this->families->first()->id ?? null;
    }

    /**
     * Obtiene las categorías asociadas a la familia seleccionada
     * Incluye las subcategorías relacionadas y ordena por nombre
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    #[Computed()]
    public function categories()
    {
        return Category::where('family_id', $this->familyId)
            ->with('subcategories')
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtiene el nombre de la familia seleccionada actualmente
     * Implementa validación para evitar errores cuando no existe la familia
     *
     * @return string El nombre de la familia o cadena vacía si no existe
     */
    #[Computed()]
    public function familyName()
    {
        $family = Family::find($this->familyId);
        return $family ? $family->name : '';
    }

    /**
     * Renderiza la vista del componente
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.navigation');
    }
}
