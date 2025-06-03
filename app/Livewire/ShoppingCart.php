<?php

namespace App\Livewire;

use Livewire\Component;

class ShoppingCart extends Component
{

    public function mount()
    {
        // Aquí puedes inicializar cualquier dato necesario para el componente
        // Por ejemplo, cargar el carrito de compras desde la sesión o base de datos

    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
