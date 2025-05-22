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
        $this->options = Option::when($this->family_id, function ($query) {
            $query->whereHas('products.subcategory.category', function ($query) {
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
                ]);
        })

            ->when($this->category_id, function ($query) {
                $query->whereHas('products.subcategory', function ($query) {
                    $query->where('category_id', $this->category_id);
                })->with([
                    'features' => function ($query) {
                        $query->whereHas('variants.product.subcategory', function ($query) {
                            $query->where('category_id', $this->category_id);
                        });
                    },
                ]);
            })

            ->when($this->subcategory_id, function ($query) {
                $query->whereHas('products', function ($query) {
                    $query->where('subcategory_id', $this->subcategory_id);
                })->with([
                    'features' => function ($query) {
                        $query->whereHas('variants.product', function ($query) {
                            $query->where('subcategory_id', $this->subcategory_id);
                        });
                    },
                ]);
            })
            ->get()->toArray();
    }

    #[On('search')]
    public function search($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    // Método que se ejecuta al cambiar la familia seleccionada
    public function updatedFamilyId()
    {
        // Actualiza las opciones filtradas según la nueva familia seleccionada, usando when igual que en mount
        $this->options = Option::when($this->family_id, function ($query) {
            $query->whereHas('products.subcategory.category', function ($query) {
                $query->where('family_id', $this->family_id);
            })
                ->with([
                    'features' => function ($query) {
                        $query->whereHas('variants.product.subcategory.category', function ($query) {
                            $query->where('family_id', $this->family_id);
                        });
                    },
                ]);
        })
            ->when($this->category_id, function ($query) {
                $query->whereHas('products.subcategory', function ($query) {
                    $query->where('category_id', $this->category_id);
                })->with([
                    'features' => function ($query) {
                        $query->whereHas('variants.product.subcategory', function ($query) {
                            $query->where('category_id', $this->category_id);
                        });
                    },
                ]);
            })

            ->when($this->subcategory_id, function ($query) {
                $query->whereHas('products', function ($query) {
                    $query->where('subcategory_id', $this->subcategory_id);
                })->with([
                    'features' => function ($query) {
                        $query->whereHas('variants.product', function ($query) {
                            $query->where('subcategory_id', $this->subcategory_id);
                        });
                    },
                ]);
            })
            // Carga las características (features) de cada opción, filtrando solo aquellas que tengan variantes
            // cuyos productos también pertenezcan a la familia seleccionada
            // y las subcategorías relacionadas
            ->get()->toArray();
    }

    // Renderiza la vista asociada al componente Livewire
    public function render()
    {
        $products = Product::when($this->family_id, function ($query) {
            $query->whereHas('subcategory.category', function ($query) {
                $query->where('family_id', $this->family_id);
            });
        })
            ->when($this->subcategory_id, function ($query) {
                $query->where('subcategory_id', $this->subcategory_id);
            })
            ->when($this->category_id, function ($query) {
                $query->whereHas('subcategory', function ($query) {
                    $query->where('category_id', $this->category_id);
                });
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
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(12);
        // Devuelve la vista 'livewire.filter' y pasa las opciones filtradas

        return view('livewire.filter', compact('products'));
    }
}
