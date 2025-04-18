<?php

namespace App\Livewire\Forms\Admin\Options;

use App\Models\Option;
use Livewire\Attributes\Validate;
use Livewire\Form;

class NewOptionForm extends Form
{
    //
    //pasamos los valores definidos anterioreme en el componente ManageOptions de livewire
    public $name;
    public $type = 1;
    public $features = [
        [
            'value' => '',
            'description' => '',
        ]
    ];

    public $openModal = false; //para abrir el modal

    public function rules()
    {
        //validacion con rules sirve para validar los campos del formulario
        //diferente a this->validate() ya que este no valida los campos del formulario
        $rules = [
            'name' => 'required',
            'type' => 'required|in:1,2',
            'features' => 'required|array|min:1',
        ];
        // Recorre cada característica (feature) de la nueva opción para generar reglas de validación dinámicas
        foreach ($this->features as $index => $feature) {

            // Si el tipo de opción es 1, el campo 'value' es obligatorio (puede ser cualquier valor)
            if ($this->type == 1) {
                $rules['features.' . $index . '.value'] = 'required';
            } else {
                // Si el tipo es diferente de 1, 'value' debe ser un color hexadecimal válido.
                // El siguiente regex valida cadenas que:
                // - Comienzan con '#'
                // - Son seguidas por exactamente 3 o 6 caracteres hexadecimales (0-9, a-f, A-F)
                // Ejemplos válidos: #FFF, #ffffff, #123ABC
                $rules['features.' . $index . '.value'] = 'required|string|regex:/^#[a-f0-9]{6}$/i';
            }
            // El campo 'description' es obligatorio para cada característica
            $rules['features.' . $index . '.description'] = 'required';
        }

        return $rules;
    }

    public function validationAttributes()
    {
        // Personaliza los nombres de los atributos para los mensajes de error
        $attributes = [
            'name' => 'Nombre',
            'type' => 'Tipo',
            'features' => 'Características',
        ];
        // Recorre cada característica (feature) para personalizar los nombres de los atributos
        foreach ($this->features as $index => $feature) {
            $attributes['features.' . $index . '.value'] = 'Valor de la característica ' . ($index + 1);
            $attributes['features.' . $index . '.description'] = 'Descripción de la característica ' . ($index + 1);
        }
        return $attributes;
    }

    public function addFeature()
    {
        // Agrega una nueva característica al array de características
        $this->features[] = [
            'value' => '',
            'description' => '',
        ];
    }

    public function removeFeature($index)
    {
        // Elimina una característica del array de características
        unset($this->features[$index]);
        // Reindexa el array de características para evitar problemas de índices
        $this->features = array_values($this->features);
    }

    public function save()
    {
        //validacion de los campos del formulario
        $this->validate();
        // Crea una nueva opción en la base de datos de acuerdo a los datos ingresados en el formulario
        //name y type son los campos definidos en la tabla options
        $option = Option::create([
            'name' => $this->name,
            'type' => $this->type,
        ]);
        // Recorre cada característica (feature) de la nueva opción y la guarda en la base de datos
        // 'value' y 'description' son los campos definidos en la tabla option_features
        foreach ($this->features as $feature) {
            $option->features()->create([
                'value' => $feature['value'],
                'description' => $feature['description'],
            ]);
        }
        // Resetea los campos del formulario después de guardar y el formulario
        $this->reset();
    }
}
