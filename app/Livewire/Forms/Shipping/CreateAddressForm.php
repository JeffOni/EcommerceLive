<?php

namespace App\Livewire\Forms\Shipping;

use App\Models\Parish;
use App\Enums\TypeOfDocuments;
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

    // Campos individuales del receptor alternativo
    #[Validate('nullable|string|max:255')]
    public $receiver_name = '';

    #[Validate('nullable|string|max:255')]
    public $receiver_last_name = '';

    #[Validate('nullable|integer|in:1,2,3,4')]
    public $receiver_document_type; // Tipo de documento (enum TypeOfDocuments)

    #[Validate('nullable|string|max:20')]
    public $receiver_document_number = '';

    #[Validate('nullable|email|max:255')]
    public $receiver_email = '';

    #[Validate('nullable|string|max:20')]
    public $receiver_phone = '';

    #[Validate('nullable|string|max:500')]
    public $notes = ''; // Notas especiales para la entrega

    public $default = false; // Si es la dirección principal del usuario

    /**
     * Método para convertir el receiver a integer cuando se actualiza
     */
    public function updatedReceiver($value)
    {
        $this->receiver = (int) $value;
    }

    public function submit()
    {
        // Verificación defensiva - Laravel 401 maneja la respuesta automáticamente
        abort_unless(auth()->check(), 401, 'Usuario no autenticado');
        // Forzar casteo a int para evitar problemas de tipo
        $this->receiver = (int) $this->receiver;

        \Log::info('Datos recibidos en submit CreateAddressForm', [
            'receiver' => $this->receiver,
            'receiver_name' => $this->receiver_name,
            'receiver_last_name' => $this->receiver_last_name,
            'receiver_document_type' => $this->receiver_document_type,
            'receiver_document_number' => $this->receiver_document_number,
            'receiver_email' => $this->receiver_email,
            'receiver_phone' => $this->receiver_phone,
        ]);

        $this->validate();

        // Si no hay código postal ingresado, usar el de la parroquia seleccionada
        if (empty($this->postal_code)) {
            $parish = Parish::find($this->parish_id);
            $this->postal_code = $parish ? $parish->default_postal_code : null;
        }

        // Construcción robusta de receiver_info
        $receiverInfo = null;
        if ($this->receiver === 2) {
            $receiverInfo = [
                'name' => $this->receiver_name,
                'last_name' => $this->receiver_last_name,
                'document_type' => $this->receiver_document_type,
                'document_number' => $this->receiver_document_number,
                'email' => $this->receiver_email,
                'phone' => $this->receiver_phone,
            ];
            // Si todos los campos están vacíos, dejarlo en null
            $allEmpty = collect($receiverInfo)->every(fn($v) => is_null($v) || $v === '');
            if ($allEmpty) {
                $receiverInfo = null;
            }
        }
        \Log::info('receiver_info construido para guardar:', ['receiver_info' => $receiverInfo]);

        $user = auth()->user();
        $isFirstAddress = $user->addresses()->count() === 0;
        $makeDefault = $this->default || $isFirstAddress;
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
            'receiver_info' => $receiverInfo,
            'notes' => $this->notes,
            'default' => $makeDefault,
        ]);

        $this->reset();
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
            'receiver_name' => 'nullable|string|max:255',
            'receiver_last_name' => 'nullable|string|max:255',
            'receiver_document_type' => 'nullable|integer|in:1,2,3,4',
            'receiver_document_number' => 'nullable|string|max:20',
            'receiver_email' => 'nullable|email|max:255',
            'receiver_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
        ];

        // Si el receptor es tercero (2), validar la información obligatoria del receptor
        if ($this->receiver === 2) {
            $documentTypes = implode(',', array_column(TypeOfDocuments::cases(), 'value'));
            $rules['receiver_name'] = 'required|string|max:255';
            $rules['receiver_last_name'] = 'required|string|max:255';
            $rules['receiver_phone'] = 'required|string|max:20|regex:/^[0-9\-\+\(\)\s]+$/';
            $rules['receiver_document_type'] = 'required|integer|in:' . $documentTypes;
            $rules['receiver_document_number'] = 'required|string|max:20|regex:/^[0-9]+$/';
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

            // Mensajes para información del receptor alternativo
            'receiver_name.required' => 'El nombre del receptor es obligatorio.',
            'receiver_name.max' => 'El nombre del receptor no puede tener más de 255 caracteres.',
            'receiver_last_name.required' => 'Los apellidos del receptor son obligatorios.',
            'receiver_last_name.max' => 'Los apellidos no pueden tener más de 255 caracteres.',
            'receiver_document_type.required' => 'El tipo de documento es obligatorio.',
            'receiver_document_type.in' => 'El tipo de documento debe ser cédula (1), pasaporte (2), RUC (3) o DNI (4).',
            'receiver_document_number.required' => 'El número de documento es obligatorio.',
            'receiver_document_number.regex' => 'El número de documento debe contener solo números.',
            'receiver_document_number.max' => 'El número de documento no puede tener más de 20 caracteres.',
            'receiver_email.email' => 'El email debe ser una dirección válida.',
            'receiver_email.max' => 'El email no puede tener más de 255 caracteres.',
            'receiver_phone.required' => 'El teléfono del receptor es obligatorio.',
            'receiver_phone.regex' => 'El teléfono debe contener solo números, espacios y los caracteres: + - ( )',
            'receiver_phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'notes.max' => 'Las notas no pueden tener más de 500 caracteres.',
        ];
    }
}
