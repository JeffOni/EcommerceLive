<?php

namespace App\Livewire;

use App\Livewire\Forms\Shipping\CreateAddressForm;
use App\Livewire\Forms\Shipping\EditAddressForm;
use App\Models\Address;
use App\Models\Province;
use App\Models\Canton;
use App\Models\Parish;
use App\Enums\TypeOfDocuments;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/**
 * =================================================================
 * COMPONENTE LIVEWIRE - GESTIÓN DE DIRECCIONES DE ENVÍO
 * =================================================================
 * 
 * Este componente maneja la creación y visualización de direcciones.
 * Para la ELIMINACIÓN, implementa el patrón admin:
 * 
 * PATRÓN DE ELIMINACIÓN IMPLEMENTADO:
 * ├── Vista: Botón onclick="confirmDelete(addressId)"
 * ├── JavaScript: Función que muestra SweetAlert de confirmación
 * ├── Formularios ocultos: Uno por cada dirección con method DELETE
 * ├── Si acepta: JS envía formulario a ShippingController@destroy
 * └── Controlador: Valida, elimina y redirige con SweetAlert de éxito
 * 
 * NOTA IMPORTANTE:
 * - NO usa métodos Livewire para eliminar (deleteAddress, confirmDelete)
 * - Sigue exactamente el patrón de ProductController del admin
 * - Mantiene consistencia en toda la aplicación
 * - Garantiza seguridad con validación de propiedad en el controlador
 */
class ShippingAddresses extends Component
{
    /**
     * =================================================================
     * PROPIEDADES PRINCIPALES DEL COMPONENTE
     * =================================================================
     */

    // Colección de direcciones del usuario autenticado
    public $addresses;

    // Control de estado para mostrar/ocultar formulario de nueva dirección
    public $newAddress = false;

    // Formularios Livewire para crear y editar direcciones
    public CreateAddressForm $createAddress;  // Formulario para crear nueva dirección
    public EditAddressForm $editAddress;      // Formulario para editar dirección existente

    /**
     * =================================================================
     * PROPIEDADES PARA SELECTS EN CASCADA (GEOGRAFÍA ECUATORIANA)
     * =================================================================
     */

    // Datos para los selects geográficos en cascada
    public $provinces = [];  // Todas las provincias del Ecuador
    public $cantons = [];    // Cantones de la provincia seleccionada
    public $parishes = [];   // Parroquias del cantón seleccionado

    /**
     * =================================================================
     * PROPIEDADES DE CONTROL Y ESTADO
     * =================================================================
     */

    // Código postal sugerido automáticamente según la parroquia
    public $suggestedPostalCode = null;

    // Tipos de documentos disponibles (Enum)
    public $documentTypes = [];

    // Control de eliminación (ya no se usa - se mantiene por compatibilidad)
    public $addressToDelete = null;

    // Propiedades de control para edición de direcciones
    public $editingAddress = false;      // Estado: ¿Estamos editando una dirección?
    public $editingAddressId = null;     // ID de la dirección que se está editando

    // Listeners de Livewire (actualmente vacío)
    protected $listeners = [];

    /**
     * =================================================================
     * MÉTODO DE INICIALIZACIÓN DEL COMPONENTE
     * =================================================================
     */

    /**
     * Método que se ejecuta al cargar el componente
     * 
     * Inicializa:
     * - Verificación de autenticación del usuario
     * - Carga de direcciones del usuario con relaciones eager loading
     * - Carga de provincias para el select inicial
     * - Configuración de tipos de documentos
     * 
     * @return void
     */
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

    public function confirmDelete($addressId)
    {
        $this->dispatch('swal:confirm', [
            'addressId' => $addressId,
            'title' => '¿Estás seguro?',
            'text' => '¿Quieres eliminar esta dirección? Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#d33',
            'cancelButtonColor' => '#3085d6',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar'
        ]);
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

    /**
     * =================================================================
     * MÉTODOS PARA EDICIÓN DE DIRECCIONES
     * =================================================================
     */

    /**
     * Iniciar edición de una dirección existente
     * 
     * Este método:
     * 1. Busca la dirección por ID y verifica que pertenezca al usuario
     * 2. Carga los datos en el EditAddressForm
     * 3. Prepara los selects en cascada (cantones/parroquias)
     * 4. Activa el modo edición para mostrar el formulario
     * 
     * @param int $addressId - ID de la dirección a editar
     * @return void
     */
    public function startEditingAddress($addressId)
    {
        // Debug temporal
        \Log::info('Método startEditingAddress ejecutado', ['addressId' => $addressId, 'userId' => auth()->id()]);

        try {
            $address = Address::where('id', $addressId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            \Log::info('Dirección encontrada', ['address' => $address->toArray()]);

            // Cargar datos en el formulario de edición
            $this->editAddress->load($address);

            // Cargar cantones y parroquias para los selects en cascada
            if ($this->editAddress->province_id) {
                $this->cantons = Canton::where('province_id', $this->editAddress->province_id)
                    ->orderBy('name')
                    ->get();
            }

            if ($this->editAddress->canton_id) {
                $this->parishes = Parish::where('canton_id', $this->editAddress->canton_id)
                    ->orderBy('name')
                    ->get();

                // Sugerir código postal si no hay uno
                if (!$this->editAddress->postal_code && $this->editAddress->parish_id) {
                    $parish = Parish::find($this->editAddress->parish_id);
                    $this->suggestedPostalCode = $parish ? $parish->default_postal_code : null;
                }
            }

            $this->editingAddress = true;
            $this->editingAddressId = $addressId;

            \Log::info('Estado de edición establecido', [
                'editingAddress' => $this->editingAddress,
                'editingAddressId' => $this->editingAddressId
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'No se pudo cargar la dirección para editar.',
                'icon' => 'error',
            ]);
        }
    }

    /**
     * Cancelar la edición de una dirección
     * 
     * Este método:
     * 1. Desactiva el modo edición
     * 2. Limpia el formulario EditAddressForm
     * 3. Resetea los selects en cascada
     * 
     * @return void
     */
    public function cancelEditAddress()
    {
        $this->editingAddress = false;
        $this->editingAddressId = null;
        $this->editAddress->reset();
        $this->resetSelects();
    }

    /**
     * Actualizar una dirección existente
     * 
     * Este método:
     * 1. Ejecuta el método update() del EditAddressForm
     * 2. Recarga la lista de direcciones
     * 3. Cierra el modo edición
     * 4. Muestra mensaje de éxito
     * 
     * @return void
     */
    public function updateAddress()
    {
        try {
            $this->editAddress->update();
            $this->mount(); // Recargar direcciones
            $this->cancelEditAddress(); // Cerrar modo edición

            $this->dispatch('swal', [
                'title' => '¡Actualizada!',
                'text' => 'La dirección se actualizó correctamente.',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Error al actualizar la dirección: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    /**
     * Resetear selects en cascada y código postal sugerido
     * 
     * Utilizado al cancelar edición para limpiar:
     * - Cantones cargados
     * - Parroquias cargadas  
     * - Código postal sugerido
     * 
     * @return void
     */
    private function resetSelects()
    {
        $this->cantons = [];
        $this->parishes = [];
        $this->suggestedPostalCode = null;
    }

    /**
     * =================================================================
     * MÉTODOS PARA SELECTS EN CASCADA EN EDICIÓN
     * =================================================================
     * 
     * Estos métodos manejan la lógica de selects geográficos en cascada
     * específicamente para el formulario de EDICIÓN de direcciones.
     * 
     * Flujo: Provincia → Cantón → Parroquia → Código Postal Sugerido
     */

    /**
     * Actualizar cantones cuando cambia la provincia en modo edición
     * 
     * @param string|null $provinceId - ID de la provincia seleccionada
     * @return void
     */
    public function updatedEditAddressProvinceId($provinceId)
    {
        if ($provinceId) {
            $this->cantons = Canton::where('province_id', $provinceId)
                ->orderBy('name')
                ->get();

            // Resetear cantón y parroquia seleccionados
            $this->editAddress->canton_id = '';
            $this->editAddress->parish_id = '';
            $this->parishes = [];
            $this->suggestedPostalCode = null;
        } else {
            $this->cantons = [];
            $this->parishes = [];
            $this->suggestedPostalCode = null;
        }
    }

    /**
     * Actualizar parroquias cuando cambia el cantón en modo edición
     * 
     * @param string|null $cantonId - ID del cantón seleccionado
     * @return void
     */
    public function updatedEditAddressCantonId($cantonId)
    {
        if ($cantonId) {
            $this->parishes = Parish::where('canton_id', $cantonId)
                ->orderBy('name')
                ->get();

            // Resetear parroquia seleccionada
            $this->editAddress->parish_id = '';
            $this->suggestedPostalCode = null;
        } else {
            $this->parishes = [];
            $this->suggestedPostalCode = null;
        }
    }

    /**
     * Actualizar código postal sugerido cuando cambia la parroquia en modo edición
     * 
     * @param string|null $parishId - ID de la parroquia seleccionada
     * @return void
     */
    public function updatedEditAddressParishId($parishId)
    {
        if ($parishId) {
            $parish = Parish::find($parishId);
            $this->suggestedPostalCode = $parish ? $parish->default_postal_code : null;

            // Solo autocompletar si no hay valor manual ingresado
            if (!$this->editAddress->postal_code) {
                $this->editAddress->postal_code = $this->suggestedPostalCode;
            }
        } else {
            $this->suggestedPostalCode = null;
        }
    }

    /**
     * Usar código postal sugerido en el formulario de edición
     * 
     * Permite al usuario hacer clic en el botón junto al campo
     * de código postal para usar automáticamente el código sugerido.
     * 
     * @return void
     */
    public function useDefaultPostalCodeEdit()
    {
        if ($this->suggestedPostalCode) {
            $this->editAddress->postal_code = $this->suggestedPostalCode;
        }
    }

    /**
     * Manejar cambio de tipo de receptor en el formulario de edición
     * 
     * Cuando el usuario cambia entre "Yo mismo" y "Otra persona":
     * - Si selecciona "Otra persona": inicializa los campos del receptor
     * - Si selecciona "Yo mismo": limpia todos los campos del receptor
     * 
     * @param string|int $receiverType - Tipo de receptor (1=yo mismo, 2=otra persona)
     * @return void
     */
    public function updatedEditAddressReceiver($receiverType)
    {
        // Convertir a entero para asegurar comparación correcta
        $receiverType = (int) $receiverType;

        // Cuando se selecciona "otra persona", inicializar los campos del receptor
        if ($receiverType === 2) {
            // Inicializar los campos individuales del receptor si están vacíos
            if (empty($this->editAddress->receiver_name)) {
                $this->editAddress->receiver_name = '';
                $this->editAddress->receiver_last_name = '';
                $this->editAddress->receiver_document_type = TypeOfDocuments::CÉDULA->value;
                $this->editAddress->receiver_document_number = '';
                $this->editAddress->receiver_email = '';
                $this->editAddress->receiver_phone = '';
            }
        } else {
            // Si se selecciona "yo mismo", limpiar todos los campos del receptor
            $this->editAddress->receiver_name = '';
            $this->editAddress->receiver_last_name = '';
            $this->editAddress->receiver_document_type = TypeOfDocuments::CÉDULA->value;
            $this->editAddress->receiver_document_number = '';
            $this->editAddress->receiver_email = '';
            $this->editAddress->receiver_phone = '';
        }
    }

    public function render()
    {
        return view('livewire.shipping-addresses');
    }
}
