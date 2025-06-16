<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateAddressForm;
use App\Models\Address;
use App\Models\Province;
use App\Models\Canton;
use App\Models\Parish;
use App\Enums\TypeOfDocuments;
use Illuminate\Validation\ValidationException;
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
    public $addressToDelete = null;
    public $documentTypes = [];

    protected $listeners = [];

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

        $this->documentTypes = TypeOfDocuments::cases();
        $this->createAddress->receiver_document_type = TypeOfDocuments::CÉDULA->value;
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
            $this->mount();
            $this->newAddress = false;
            $this->dispatch('swal', [
                'title' => '¡Dirección guardada!',
                'text' => 'La dirección se guardó correctamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Error al guardar la dirección: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
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
                $this->mount();
                $this->dispatch('swal', [
                    'title' => '¡Eliminada!',
                    'text' => 'La dirección fue eliminada.',
                    'icon' => 'success',
                    'timer' => 2000,
                    'showConfirmButton' => false
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Error al eliminar la dirección: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
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

    public function updatedCreateAddressReceiver($receiverType)
    {
        // Convertir a entero para asegurar comparación correcta
        $receiverType = (int) $receiverType;

        // Cuando se selecciona "otra persona", inicializar los campos del receptor
        if ($receiverType === 2) {
            // Inicializar los campos individuales del receptor si están vacíos
            if (empty($this->createAddress->receiver_name)) {
                $this->createAddress->receiver_name = '';
                $this->createAddress->receiver_last_name = '';
                $this->createAddress->receiver_document_type = TypeOfDocuments::CÉDULA->value; // Cédula por defecto
                $this->createAddress->receiver_document_number = '';
                $this->createAddress->receiver_email = '';
                $this->createAddress->receiver_phone = '';
            }
        } else {
            // Si se selecciona "yo mismo", limpiar todos los campos del receptor
            $this->createAddress->receiver_name = '';
            $this->createAddress->receiver_last_name = '';
            $this->createAddress->receiver_document_type = TypeOfDocuments::CÉDULA->value;
            $this->createAddress->receiver_document_number = '';
            $this->createAddress->receiver_email = '';
            $this->createAddress->receiver_phone = '';
        }
    }

    public function render()
    {
        return view('livewire.shipping-addresses');
    }
}
