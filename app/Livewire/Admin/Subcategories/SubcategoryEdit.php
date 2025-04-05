<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Category;
use Livewire\Component;
use App\Models\Family;
use Livewire\Attributes\Computed;

class SubcategoryEdit extends Component
{
    public $subcategory; //varaible para obtener los datos de la subcategoria por id

    public $families; //variable publica families
    public $subcategoryEdit;
    //= [ //variable publica subcategory inicializada como un arreglo para almacenar los datos del formulario
    //     'family_id' => '',
    //     'category_id' => '',
    //     'name' => ''
    // ];//tambien se puede solo definir la variable publica en lugar de tener el array ya que se lo esta llamadno en la function munt

    public function mount($subcategory) //metodo mount sirve para inicializar la clase y recuperar los datos de la tabla family y subcategory
    //tambien se recibe el el campos subcategory para no usar this
    {
        $this->families = Family::all(); //obtiene todos los registros de la tabla family
        $this->subcategoryEdit = [
            'family_id' => $subcategory->category->family_id,
            'category_id' => $subcategory->category_id,
            'name' => $subcategory->name
        ];
    }

    public function updatedSubcategoryEditId()
    {
        $this->subcategoryEdit['category_id'] = ''; //se inicializa la variable category_id como vacia
    }

    //computed properties es una propiedad computada que se ejecuta cada vez que se actualiza la variable subcategory
    #[Computed()] //atributo computed para ejecutar la propiedad computada
    public function categories() //metodo categories para obtener las categorias de una familia
    {
        return Category::where('family_id', $this->subcategoryEdit['family_id'])->get();
        //obtiene las categorias de una familia comparando el id de la familia selecciona con la variable subcategory definida en el formulario
    }

    public function save()
    {
        $this->validate([ //validacion de los campos del formulario
            'subcategoryEdit.family_id' => 'required|exists:families,id',
            'subcategoryEdit.category_id' => 'required|exists:categories,id',
            'subcategoryEdit.name' => 'required'
        ], [], [
            'subcategoryEdit.family_id' => 'familia',
            'subcategoryEdit.category_id' => 'categoría',
            'subcategoryEdit.name' => 'nombre'
        ]);

        $this->subcategory->update($this->subcategoryEdit); //actualiza los datos de la subcategoria con los datos del formulario

        // session()->flash('swal', [ //mensaje de exito con script de sweetalert normal
        //     'icon' => 'success',
        //     'title' => '!Bien hecho!',
        //     'text' => 'Subcategoría Actualizada correctamente',
        //     'timeout' => 3000
        // ]);

        $this->dispatch('swal', [ //mensaje de exito con script de sweetalert con livewire no necesita redireccionar a otra vista o variable de session
            'icon' => 'success',
            'title' => '!Bien hecho!',
            'text' => 'Subcategoría Actualizada correctamente',
            'timeout' => 3000
        ]);
    }

    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-edit');
    }
}
