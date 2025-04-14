<?php

namespace App\Livewire\Admin\Options;

use App\Models\Option;
use Livewire\Component;

class ManageOptions extends Component
{

    public $options;//para obtener los datos de las opciones

    public $openModal = false;//para abrir el modal

    public $newOption = [//para crear una nueva opción especificados en el modal con sus inputs
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
    {//para agregar una nueva opción al array de opciones
        $this->newOption['features'][] = [
            'value' => '',
            'description' => '',
        ];
    }

    public function removeFeature($index)
    {//para eliminar una opción del array de opciones
        unset($this->newOption['features'][$index]);//unset sirve para eliminar un elemento de un array
        $this->newOption['features'] = array_values($this->newOption['features']);//array_values sirve para reindexar el array
    }

    public function addOption()
    {//para agregar una nueva opción a la base de datos
        $this->validate([
            'newOption.name' => 'required',
            'newOption.type' => 'required',
            'newOption.features' => 'required|array',
            'newOption.features.*.value' => 'required',
            'newOption.features.*.description' => 'required',
        ]);

        Option::create($this->newOption);//crea la nueva opción en la base de datos

        $this->reset('newOption');//resetea el array de opciones

        $this->options = Option::with('features')->get();//actualiza el array de opciones

        $this->openModal = false;//cierra el modal
    }

    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
