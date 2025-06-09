<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateAddressForm extends Form
{
    //
    public $type = ''; // Tipo de dirección (1: casa, 2: trabajo, etc.)
    public $provincia = ''; // Provincia de Ecuador
    public $canton = ''; // Cantón dentro de la provincia
    public $parroquia = ''; // Parroquia dentro de la provincia
    public $reference = ''; // Referencia adicional (cerca de...)
    public $address = '';// Dirección específica (calle, número, etc.)
    public $receiver = 1; // Tipo de receptor (1: mismo usuario, 2: tercero, etc.)
    public $reciver_info = []; // Datos del receptor (nombre, teléfono, etc.)
    public $default = false; // Si es la dirección principal del usuario

    #[Validate('required')]
    public function submit()
    {
        $this->validate();

        auth()->user()->addresses()->create([
            'type' => $this->type,
            'provincia' => $this->provincia,
            'canton' => $this->canton,
            'parroquia' => $this->parroquia,
            'reference' => $this->reference,
            'address' => $this->address,
            'receiver' => $this->receiver,
            'receiver_info' => json_encode($this->receiver_info),
            'default' => $this->default,
        ]);

        // Reset form fields after submission
        $this->reset();
    }
}
