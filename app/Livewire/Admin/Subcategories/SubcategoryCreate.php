<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Family;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Subcategory;

class SubcategoryCreate extends Component
{
    public $families; //variable publica families
    public $subcategory = [ //variable publica subcategory inicializada como un arreglo para almacenar los datos del formulario
        'family_id' => '',
        'category_id' => '',
        'name' => ''
    ];

    public function mount() //metodo mount sirve para inicializar la clase y recuperar los datos de la tabla family
    {
        $this->families = Family::all(); //obtiene todos los registros de la tabla family
    }

    //metodo updatedSubcategoryId se ejecuta cada vez que se actualiza la variable subcategory y se encarga de inicializar la variable category_id como vacia
    //sirve para el ciclo de vida de la variable subcategory o como se conoce en livewire como reactive properties
    //ciclo de vida del compoente similar al hook updated en Vue.js o el hook watch en React.js
    public function updatedSubcategoryId()
    {
        $this->subcategory['category_id'] = ''; //se inicializa la variable category_id como vacia
    }


    //computed properties es una propiedad computada que se ejecuta cada vez que se actualiza la variable subcategory
    #[Computed()] //atributo computed para ejecutar la propiedad computada
    public function categories() //metodo categories para obtener las categorias de una familia
    {
        return Category::where('family_id', $this->subcategory['family_id'])->get();
        //obtiene las categorias de una familia comparando el id de la familia selecciona con la variable subcategory definida en el formulario
    }

    public function save() //metodo save para guardar los datos del formulario
    {
        $this->validate([ //metodo validate para validar los campos del formulario
            'subcategory.family_id' => 'required|exists:families,id', //campo family_id requerido
            'subcategory.category_id' => 'required|exists:categories,id', //campo category_id requerido
            'subcategory.name' => 'required' //campo name requerido
        ], [], [ //mensajes de error personalizados el primer array es para los campos requeridos y el segundo para los mensajes de error
            'subcategory.family_id' => 'familia', //mensaje de error para el campo family_id
            'subcategory.category_id' => 'categoría', //mensaje de error para el campo category_id
            'subcategory.name' => 'nombre' //mensaje de error para el campo name
        ]);

            Subcategory::create($this->subcategory); //guardar los datos del formulario forma2
         //guardar los datos del formulario forma
        // $subcategory = new Subcategory(); //nueva instancia de la clase Subcategory
        // $subcategory->family_id = $this->subcategory['family_id']; //asigna el valor de la familia seleccionada
        // $subcategory->category_id = $this->subcategory['category_id']; //asigna el valor de la categoria seleccionada
        // $subcategory->name = $this->subcategory['name']; //asigna el valor del nombre de la subcategoria
        // $subcategory->save(); //guarda los datos en la tabla subcategories

        session()->flash('swal', [//mensaje de exito
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Subcategoría Creada correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.subcategories.index'); //redirecciona a la ruta admin.subcategories.index
    }

    //metodo render para renderizar la vista livewire
    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-create');
    }
}
