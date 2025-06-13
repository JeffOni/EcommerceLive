<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateAddressForm;
use App\Models\Address;
use App\Models\Province;
use App\Models\Canton;
use App\Models\Parish;
use Livewire\Component;

class ShippingAddresses extends Component
{
    public $addresses;
    public $newAddress = false;
    public CreateAddressForm $createAddress;

    // Propiedades para manejar los selects en cascada
    public $provinces = [];
    public $cantons = [];
    public $parishes = [];

    // Propiedad para el código postal sugerido
    public $suggestedPostalCode = null;

    public function mount()
    {
        // Verificación adicional de autenticación en el componente
        abort_unless(auth()->check(), 401, 'Usuario no autenticado');

        // Inicializar la lista de direcciones del usuario autenticado usando la relación
        $this->addresses = auth()->user()->addresses()
            ->with(['province', 'canton', 'parish']) // Eager loading de las relaciones
            ->orderBy('default', 'desc') // Ordenar primero las direcciones por defecto
            ->get();

        // Si no hay direcciones, inicializar con un array vacío
        if ($this->addresses->isEmpty()) {
            $this->addresses = collect([]);
        }

        // Cargar todas las provincias para el select inicial
        $this->provinces = Province::orderBy('name')->get();
    }

    public function openNewAddress()
    {
        $this->newAddress = true;
        $this->resetForm();
    }

    public function cancelNewAddress()
    {
        $this->newAddress = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->createAddress->reset();
        $this->cantons = [];
        $this->parishes = [];
        $this->suggestedPostalCode = null;
    }

    public function updatedCreateAddressProvinceId($provinceId)
    {
        // Cuando se selecciona una provincia, cargar sus cantones
        if ($provinceId) {
            $this->cantons = Canton::where('province_id', $provinceId)
                ->orderBy('name')
                ->get();

            // Resetear cantón y parroquia seleccionados
            $this->createAddress->canton_id = '';
            $this->createAddress->parish_id = '';
            $this->parishes = [];
            $this->suggestedPostalCode = null;
        } else {
            $this->cantons = [];
            $this->parishes = [];
            $this->suggestedPostalCode = null;
        }
    }

    public function updatedCreateAddressCantonId($cantonId)
    {
        // Cuando se selecciona un cantón, cargar sus parroquias
        if ($cantonId) {
            $this->parishes = Parish::where('canton_id', $cantonId)
                ->orderBy('name')
                ->get();

            // Resetear parroquia seleccionada
            $this->createAddress->parish_id = '';
            $this->suggestedPostalCode = null;
        } else {
            $this->parishes = [];
            $this->suggestedPostalCode = null;
        }
    }

    public function updatedCreateAddressParishId($parishId)
    {
        // Cuando se selecciona una parroquia, sugerir el código postal
        if ($parishId) {
            $parish = Parish::find($parishId);
            $this->suggestedPostalCode = $parish ? $parish->default_postal_code : null;

            // Solo autocompletar si no hay valor manual ingresado
            if (!$this->createAddress->postal_code) {
                $this->createAddress->postal_code = $this->suggestedPostalCode;
            }
        } else {
            $this->suggestedPostalCode = null;
        }
    }

    public function useDefaultPostalCode()
    {
        // Usar el código postal sugerido
        if ($this->suggestedPostalCode) {
            $this->createAddress->postal_code = $this->suggestedPostalCode;
        }
    }

    public function saveAddress()
    {
        try {
            $this->createAddress->submit();

            // Recargar las direcciones después de crear una nueva
            $this->mount();

            // Cerrar el formulario de nueva dirección
            $this->newAddress = false;

            // Mostrar mensaje de éxito
            session()->flash('message', 'Dirección agregada exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Los errores de validación se manejan automáticamente por Livewire
            throw $e;
        } catch (\Exception $e) {
            // Manejar otros errores
            session()->flash('error', 'Error al guardar la dirección: ' . $e->getMessage());
        }
    }

    public function deleteAddress($addressId)
    {
        try {
            $address = Address::where('id', $addressId)
                ->where('user_id', auth()->id())
                ->first();

            if ($address) {
                $address->delete();

                // Recargar las direcciones
                $this->mount();

                session()->flash('message', 'Dirección eliminada exitosamente.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la dirección: ' . $e->getMessage());
        }
    }

    public function setAsDefault($addressId)
    {
        try {
            // Quitar el default de todas las direcciones del usuario
            auth()->user()->addresses()->update(['default' => false]);

            // Marcar la dirección seleccionada como default
            Address::where('id', $addressId)
                ->where('user_id', auth()->id())
                ->update(['default' => true]);

            // Recargar las direcciones
            $this->mount();

            session()->flash('message', 'Dirección establecida como predeterminada.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al establecer dirección predeterminada: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.shipping-addresses');
    }
}
