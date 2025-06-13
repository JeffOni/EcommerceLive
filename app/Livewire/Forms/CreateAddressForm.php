<?php

namespace App\Livewire\Forms;

use App\Models\Parish;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateAddressForm extends Form
{
    #[Validate('required|integer|in:1,2,3')]
    public $type = 1; // Tipo de dirección (1=casa, 2=trabajo, 3=otro)

    #[Validate('required|exists:provinces,id')]
    public $province_id = ''; // ID de la Provincia de Ecuador

    #[Validate('required|exists:cantons,id')]
    public $canton_id = ''; // ID del Cantón dentro de la provincia

    #[Validate('required|exists:parishes,id')]
    public $parish_id = ''; // ID de la Parroquia dentro del cantón

    #[Validate('nullable|string|max:255')]
    public $reference = ''; // Referencia adicional (cerca de...)

    #[Validate('required|string|max:255')]
    public $address = ''; // Dirección específica (calle, número, etc.)

    #[Validate('nullable|string|max:10')]
    public $postal_code = ''; // Código postal (puede ser sugerido o personalizado)

    #[Validate('required|integer|in:1,2')]
    public $receiver = 1; // Tipo de receptor (1=propio, 2=tercero)

    #[Validate('nullable|array')]
    public $receiver_info = []; // Datos del receptor (nombre, teléfono, etc.)

    public $default = false; // Si es la dirección principal del usuario

    public function submit()
    {
        // Verificación defensiva - Laravel 401 maneja la respuesta automáticamente
        abort_unless(auth()->check(), 401, 'Usuario no autenticado');

        $this->validate();

        // Si no hay código postal ingresado, usar el de la parroquia seleccionada
        if (empty($this->postal_code)) {
            $parish = Parish::find($this->parish_id);
            $this->postal_code = $parish ? $parish->default_postal_code : null;
        }

        // Si es la primera dirección o se marca como default, hacer que sea la predeterminada
        $user = auth()->user();
        $isFirstAddress = $user->addresses()->count() === 0;
        $makeDefault = $this->default || $isFirstAddress;

        // Si se marca como predeterminada, quitar el default de las otras direcciones
        if ($makeDefault) {
            $user->addresses()->update(['default' => false]);
        }

        $user->addresses()->create([
            'type' => $this->type,
            'province_id' => $this->province_id,
            'canton_id' => $this->canton_id,
            'parish_id' => $this->parish_id,
            'postal_code' => $this->postal_code,
            'reference' => $this->reference,
            'address' => $this->address,
            'receiver' => $this->receiver,
            'receiver_info' => !empty($this->receiver_info) ? json_encode($this->receiver_info) : null,
            'default' => $makeDefault,
        ]);

        // Reset form fields after submission
        $this->reset();

        // Mensaje de éxito
        session()->flash('message', 'Dirección guardada exitosamente.');
    }

    public function rules()
    {
        $rules = [
            'type' => 'required|integer|in:1,2,3',
            'province_id' => 'required|exists:provinces,id',
            'canton_id' => 'required|exists:cantons,id',
            'parish_id' => 'required|exists:parishes,id',
            'reference' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'receiver' => 'required|integer|in:1,2',
            'receiver_info' => 'nullable|array',
        ];

        // Si el receptor es tercero (2), validar la información del receptor
        if ($this->receiver === 2) {
            $rules['receiver_info.name'] = 'required|string|max:255';
            $rules['receiver_info.phone'] = 'required|string|max:20';
            $rules['receiver_info.identification'] = 'nullable|string|max:20';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'type.required' => 'El tipo de dirección es obligatorio.',
            'type.integer' => 'El tipo de dirección debe ser un número válido.',
            'type.in' => 'El tipo de dirección debe ser casa (1), trabajo (2) u otro (3).',
            'province_id.required' => 'La provincia es obligatoria.',
            'province_id.exists' => 'La provincia seleccionada no es válida.',
            'canton_id.required' => 'El cantón es obligatorio.',
            'canton_id.exists' => 'El cantón seleccionado no es válido.',
            'parish_id.required' => 'La parroquia es obligatoria.',
            'parish_id.exists' => 'La parroquia seleccionada no es válida.',
            'address.required' => 'La dirección específica es obligatoria.',
            'address.max' => 'La dirección no puede tener más de 255 caracteres.',
            'postal_code.max' => 'El código postal no puede tener más de 10 caracteres.',
            'receiver.required' => 'El tipo de receptor es obligatorio.',
            'receiver.integer' => 'El tipo de receptor debe ser un número válido.',
            'receiver.in' => 'El receptor debe ser propio (1) o tercero (2).',
            'receiver_info.name.required' => 'El nombre del receptor es obligatorio.',
            'receiver_info.phone.required' => 'El teléfono del receptor es obligatorio.',
        ];
    }
}
