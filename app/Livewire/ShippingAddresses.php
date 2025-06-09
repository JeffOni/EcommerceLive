<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateAddressForm;
use App\Models\Address;
use Livewire\Component;

class ShippingAddresses extends Component
{
    public $addresses;

    public $newAddress = false;

    public CreateAddressForm $createAddress;
    public function mount()
    {
        // Inicializar la lista de direcciones del usuario autenticado usando la relación
        $this->addresses = auth()->user()->addresses()
            ->orderBy('default', 'desc') // Ordenar primero las direcciones por defecto
            ->get();

        // Si no hay direcciones, inicializar con un array vacío
        if ($this->addresses->isEmpty()) {
            $this->addresses = collect([]);
        }
    }
    public function render()
    {
        return view('livewire.shipping-addresses');
    }
}
