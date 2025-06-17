# Documentación - EditAddressForm

## 📋 Resumen

Se implementó el `EditAddressForm` basado en la lógica del `CreateAddressForm` para permitir la edición de direcciones existentes. El formulario mantiene toda la funcionalidad del crear pero adaptada para actualizar registros existentes.

## 🏗️ Arquitectura del EditAddressForm

### 1. **Ubicación y Namespace**
```php
// Archivo: app/Livewire/Forms/Shipping/EditAddressForm.php
namespace App\Livewire\Forms\Shipping;
```

### 2. **Características Principales**

#### ✅ **Propiedades Idénticas al CreateAddressForm**
- Todos los campos del formulario de creación
- Mismas validaciones y reglas
- Mismo manejo de `receiver_info`
- Soporte completo para receptor alternativo

#### ✅ **Funcionalidades Específicas de Edición**
- **Método `load(Address $address)`** - Carga datos de dirección existente
- **Método `update()`** - Actualiza dirección en lugar de crear
- **Validación de propiedad** - Solo el owner puede editar
- **Manejo de dirección predeterminada** - Preserva lógica existente

## 🔧 Métodos Principales

### 1. **load(Address $address)**
```php
/**
 * Carga una dirección existente en el formulario
 * - Verifica que pertenezca al usuario autenticado
 * - Llena todos los campos con los datos existentes
 * - Maneja correctamente receiver_info si existe
 */
public function load(Address $address)
{
    // Verificación de seguridad
    if ($address->user_id !== auth()->id()) {
        abort(403, 'No tienes permiso para editar esta dirección');
    }

    // Cargar datos básicos
    $this->id = $address->id;
    $this->type = $address->type;
    // ... resto de campos
    
    // Cargar información del receptor si existe
    if ($address->receiver === 2 && $address->receiver_info) {
        $receiverInfo = $address->receiver_info;
        $this->receiver_name = $receiverInfo['name'] ?? '';
        // ... resto de campos del receptor
    }
}
```

### 2. **update()**
```php
/**
 * Actualiza la dirección existente
 * - Validaciones idénticas al CreateAddressForm
 * - Construcción de receiver_info igual
 * - Manejo de dirección predeterminada
 * - Logs para debugging
 */
public function update()
{
    // Verificar autenticación
    abort_unless(auth()->check(), 401, 'Usuario no autenticado');
    
    // Buscar dirección y verificar propiedad
    $address = Address::where('id', $this->id)
        ->where('user_id', auth()->id())
        ->firstOrFail();
    
    // Validar datos
    $this->validate();
    
    // Construir receiver_info (lógica idéntica al CreateAddressForm)
    $receiverInfo = null;
    if ($this->receiver === 2) {
        // ... construcción de receiver_info
    }
    
    // Actualizar dirección
    $address->update([
        'type' => $this->type,
        // ... resto de campos
        'receiver_info' => $receiverInfo,
    ]);
}
```

## 🎯 Integración con ShippingAddresses Component

### **Propiedades Agregadas**
```php
class ShippingAddresses extends Component
{
    // Formularios
    public CreateAddressForm $createAddress;
    public EditAddressForm $editAddress; // ✅ NUEVO

    // Estados de edición
    public $editingAddress = false; // ✅ NUEVO
    public $editingAddressId = null; // ✅ NUEVO
    
    // ... resto de propiedades existentes
}
```

### **Métodos de Edición Implementados**

#### 1. **editAddress($addressId)**
```php
/**
 * Inicia la edición de una dirección
 * - Carga la dirección en el formulario
 * - Prepara selects en cascada (cantones/parroquias)
 * - Activa modo edición
 */
public function editAddress($addressId)
{
    $address = Address::where('id', $addressId)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $this->editAddress->load($address);
    
    // Cargar cantones y parroquias si es necesario
    if ($this->editAddress->province_id) {
        $this->cantons = Canton::where('province_id', $this->editAddress->province_id)
            ->orderBy('name')
            ->get();
    }
    
    $this->editingAddress = true;
    $this->editingAddressId = $addressId;
}
```

#### 2. **updateAddress()**
```php
/**
 * Procesa la actualización de la dirección
 * - Llama al método update() del formulario
 * - Recarga direcciones
 * - Muestra mensaje de éxito con SweetAlert
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
        throw $e; // Propagar errores de validación
    }
}
```

#### 3. **cancelEditAddress()**
```php
/**
 * Cancela la edición y resetea el estado
 */
public function cancelEditAddress()
{
    $this->editingAddress = false;
    $this->editingAddressId = null;
    $this->editAddress->reset();
    $this->resetSelects();
}
```

### **Métodos para Selects en Cascada en Edición**
```php
// Actualizadores para provincia, cantón y parroquia en modo edición
public function updatedEditAddressProvinceId($provinceId) { /* ... */ }
public function updatedEditAddressCantonId($cantonId) { /* ... */ }
public function updatedEditAddressParishId($parishId) { /* ... */ }
public function updatedEditAddressReceiver($receiverType) { /* ... */ }

// Método para usar código postal sugerido en edición
public function useDefaultPostalCodeEdit() { /* ... */ }
```

## 🎨 Uso en la Vista (Ejemplo)

### **Botón de Editar**
```blade
{{-- En la lista de direcciones --}}
<button wire:click="editAddress({{ $address->id }})"
    class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200">
    Editar
</button>
```

### **Formulario de Edición**
```blade
@if ($editingAddress)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <form wire:submit.prevent="updateAddress" class="p-6 space-y-6">
            {{-- Campos del formulario usando editAddress en lugar de createAddress --}}
            <select wire:model.live="editAddress.province_id">
                {{-- Opciones --}}
            </select>
            
            <input wire:model="editAddress.address" type="text" />
            
            {{-- Resto de campos... --}}
            
            <div class="flex gap-3">
                <button type="button" wire:click="cancelEditAddress">
                    Cancelar
                </button>
                <button type="submit">
                    Actualizar Dirección
                </button>
            </div>
        </form>
    </div>
@endif
```

## 🛡️ Características de Seguridad

### 1. **Verificación de Propiedad**
```php
// En load()
if ($address->user_id !== auth()->id()) {
    abort(403, 'No tienes permiso para editar esta dirección');
}

// En update()
$address = Address::where('id', $this->id)
    ->where('user_id', auth()->id())
    ->firstOrFail();
```

### 2. **Validaciones Robustas**
- Mismas reglas que CreateAddressForm
- Validación condicional para receptor alternativo
- Verificación de existencia de relaciones

### 3. **Logs de Auditoría**
```php
\Log::info('Datos recibidos en update EditAddressForm', [
    'address_id' => $this->id,
    'receiver' => $this->receiver,
    // ... resto de datos relevantes
]);
```

## ✅ Características Implementadas

### **✅ Funcionalidades Base**
- ✅ Cargar dirección existente en formulario
- ✅ Actualizar todos los campos de la dirección
- ✅ Manejo completo de receiver_info
- ✅ Validaciones idénticas al CreateAddressForm
- ✅ Seguridad y verificación de propiedad

### **✅ Selects en Cascada**
- ✅ Provincia → Cantón → Parroquia
- ✅ Código postal sugerido
- ✅ Reseteo de campos dependientes

### **✅ Receptor Alternativo**
- ✅ Carga datos existentes del receptor
- ✅ Limpia campos al cambiar tipo de receptor
- ✅ Validaciones condicionales

### **✅ Integración con Componente**
- ✅ Estados de edición (`editingAddress`, `editingAddressId`)
- ✅ Métodos completos para editar/cancelar/actualizar
- ✅ Mensajes SweetAlert para feedback

### **✅ Dirección Predeterminada**
- ✅ Manejo correcto al marcar/desmarcar como predeterminada
- ✅ Actualización de otras direcciones cuando se cambia

## 🚀 Estado Final

El `EditAddressForm` está **100% completo y listo para usar**:

1. **✅ Formulario funcional** - Todas las propiedades y métodos implementados
2. **✅ Validaciones robustas** - Idénticas al CreateAddressForm
3. **✅ Seguridad garantizada** - Verificación de propiedad en múltiples niveles
4. **✅ Integración completa** - Métodos agregados al componente principal
5. **✅ Documentación completa** - Código bien comentado y explicado

**¡Listo para implementar en la vista y comenzar a usar!** 🎉
