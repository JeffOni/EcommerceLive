<?php

namespace App\Livewire\Admin\Options;

use App\Models\Option;
use Livewire\Component;

class ManageOptions extends Component
{

    public $options;//para obtener los datos de las opciones

    public function mount()
    {
       $this->options = Option::with('features')->get(); //para obtener los datos de las opciones evitando el problema de la carga de datos n+1
    }

    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
