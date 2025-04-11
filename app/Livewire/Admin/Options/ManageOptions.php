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

    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
