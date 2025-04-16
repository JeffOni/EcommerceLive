<?php

namespace App\Livewire\Admin\Options;

use App\Models\Option;
use Livewire\Component;

class ManageOptions extends Component
{

    public $options; //para obtener los datos de las opciones

    public $openModal = false; //para abrir el modal

    public $newOption = [ //para crear una nueva opción especificados en el modal con sus inputs
        'name' => '',
        'type' => 1,
        'features' => [
            [
                'value' => '',
                'description' => '',
            ]
        ],
    ];

    public function mount()
    {
        $this->options = Option::with('features')->get(); //para obtener los datos de las opciones evitando el problema de la carga de datos n+1
    }

    public function addFeature()
    { //para agregar una nueva opción al array de opciones
        $this->newOption['features'][] = [
            'value' => '',
            'description' => '',
        ];
    }

    public function removeFeature($index)
    { //para eliminar una opción del array de opciones
        unset($this->newOption['features'][$index]); //unset sirve para eliminar un elemento de un array
        $this->newOption['features'] = array_values($this->newOption['features']); //array_values sirve para reindexar el array
    }

    public function addOption()
    { //validacion con rules sirve para validar los campos del formulario
        //diferente a this->validate() ya que este no valida los campos del formulario
        $rules = [
            'newOption.name' => 'required',
            'newOption.type' => 'required|in:1,2',
            'newOption.features' => 'required|array|min:1',
        ];
        // Recorre cada característica (feature) de la nueva opción para generar reglas de validación dinámicas
        foreach ($this->newOption['features'] as $index => $feature) {

            // Si el tipo de opción es 1, el campo 'value' es obligatorio (puede ser cualquier valor)
            if ($this->newOption['type'] == 1) {
                $rules['newOption.features.' . $index . '.value'] = 'required';
            } else {
                // Si el tipo es diferente de 1, 'value' debe ser un color hexadecimal válido.
                // El siguiente regex valida cadenas que:
                // - Comienzan con '#'
                // - Son seguidas por exactamente 3 o 6 caracteres hexadecimales (0-9, a-f, A-F)
                // Ejemplos válidos: #FFF, #ffffff, #123ABC
                $rules['newOption.features.' . $index . '.value'] = 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';
            }
            // El campo 'description' es obligatorio para cada característica
            $rules['newOption.features.' . $index . '.description'] = 'required';
        }
        // Valida los datos de la nueva opción según las reglas definidas
        $this->validate($rules);
        // Crea una nueva opción en la base de datos de acuerdo a los datos ingresados en el formulario
        //name y type son los campos definidos en la tabla options
        $option = Option::create([
            'name' => $this->newOption['name'],
            'type' => $this->newOption['type'],
        ]);
        // Recorre cada característica (feature) de la nueva opción y la guarda en la base de datos
        // 'value' y 'description' son los campos definidos en la tabla option_features
        foreach ($this->newOption['features'] as $feature) {
            $option->features()->create([
                'value' => $feature['value'],
                'description' => $feature['description'],
            ]);
        }

        // vuelve a cargar los datos de las opciones con los nuevos datos guardados
        $this->options = Option::with('features')->get();

        //Resetear el modal y los inputs
        $this->reset('openModal', 'newOption'); //resetea la variable newOption y openModal

        //muestra una alerta de SweetAlert
        $this->dispatch('swal', [ //muestra una alerta de SweetAlert
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Opción creada correctamente.',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
