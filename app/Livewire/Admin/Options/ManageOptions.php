<?php

namespace App\Livewire\Admin\Options;

use App\Livewire\Forms\Admin\Options\NewOptionForm;
use App\Models\Option;
use Livewire\Component;

class ManageOptions extends Component
{

    public $options; //para obtener los datos de las opciones



    // public $newOption = [ //para crear una nueva opción especificados en el modal con sus inputs
    //     'name' => '',
    //     'type' => 1,
    //     'features' => [
    //         [
    //             'value' => '',
    //             'description' => '',
    //         ]
    //     ],
    // ];

    //para crear una nueva opción especificados en el modal con sus inputs tomado de la clase NewOptionForm
    public NewOptionForm $newOption;

    public function mount()
    {
        $this->options = Option::with('features')->get(); //para obtener los datos de las opciones evitando el problema de la carga de datos n+1
    }

    public function addFeature()
    {
        $this->newOption->addFeature(); //llama a la funcion addFeature de la clase NewOptionForm para agregar una nueva opción al array de opciones
    }

    public function removeFeature($index)
    {
        $this->newOption->removeFeature($index); //llama a la funcion removeFeature de la clase NewOptionForm para eliminar una opción del array de opciones
    }

    public function addOption()
    {

        $this->newOption->save(); //llama a la funcion save de la clase NewOptionForm para guardar los datos en la base de datos

        // vuelve a cargar los datos de las opciones con los nuevos datos guardados
        $this->options = Option::with('features')->get();

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
