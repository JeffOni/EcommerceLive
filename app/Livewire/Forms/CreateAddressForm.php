<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateAddressForm extends Form
{
    #[Validate('required|string|in:casa,trabajo,otro')]
    public $type = ''; // Tipo de dirección (casa, trabajo, otro)

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

    #[Validate('required|string|in:propio,tercero')]
    public $receiver = 'propio'; // Tipo de receptor (propio, tercero)

    #[Validate('nullable|array')]
    public $receiver_info = []; // Datos del receptor (nombre, teléfono, etc.)

    public $default = false; // Si es la dirección principal del usuario

    public function submit()
    {
        // Verificación defensiva - Laravel 401 maneja la respuesta automáticamente
        abort_unless(auth()->check(), 401, 'Usuario no autenticado');

        $this->validate();

        // Obtener el código postal basado en la parroquia seleccionada
        $parish = \App\Models\Parish::find($this->parish_id);
        $postal_code = $parish ? $parish->code : null;

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
            'postal_code' => $postal_code,
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
            'type' => 'required|string|in:casa,trabajo,otro',
            'province_id' => 'required|exists:provinces,id',
            'canton_id' => 'required|exists:cantons,id',
            'parish_id' => 'required|exists:parishes,id',
            'reference' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'receiver' => 'required|string|in:propio,tercero',
            'receiver_info' => 'nullable|array',
        ];

        // Si el receptor es tercero, validar la información del receptor
        if ($this->receiver === 'tercero') {
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
            'type.in' => 'El tipo de dirección debe ser casa, trabajo u otro.',
            'province_id.required' => 'La provincia es obligatoria.',
            'province_id.exists' => 'La provincia seleccionada no es válida.',
            'canton_id.required' => 'El cantón es obligatorio.',
            'canton_id.exists' => 'El cantón seleccionado no es válido.',
            'parish_id.required' => 'La parroquia es obligatoria.',
            'parish_id.exists' => 'La parroquia seleccionada no es válida.',
            'address.required' => 'La dirección específica es obligatoria.',
            'address.max' => 'La dirección no puede tener más de 255 caracteres.',
            'receiver.required' => 'El tipo de receptor es obligatorio.',
            'receiver.in' => 'El receptor debe ser propio o tercero.',
            'receiver_info.name.required' => 'El nombre del receptor es obligatorio.',
            'receiver_info.phone.required' => 'El teléfono del receptor es obligatorio.',
        ];
    }
}
