<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use Livewire\Component;
use App\Models\Family;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class ProductEdit extends Component
{
    use WithFileUploads; //se utiliza el trait WithFileUploads para poder subir archivos

    public $product; //varible publica product para almacenar los datos del producto

    public $families; //variable publica families para almacenar y extraer los datos de la tabla families

    public $family_id = ''; //variable publica family_id para almacenar el id de la familia seleccionada

    public $category_id = ''; //variable publica category_id para almacenar el id de la categoria seleccionada

    public $productEdit;

    public $image; //variable publica image para almacenar la imagen del producto

    public function updatedFamilyId($value)
    //metodo updatedFamilyId se ejecuta cada vez que se actualiza la variable family_id para resetera los campos en category y subcategory
    {
        $this->category_id = ''; //se inicializa la variable category_id como vacia
        $this->productEdit['subcategory_id'] = ''; //se inicializa la variable subcategory_id dentro de la variable product como vacia
    }

    public function updatedCategoryId($value)
    //metodo updatedCategoryId se ejecuta cada vez que se actualiza la variable category_id para resetear el campo en subcategory
    {
        $this->productEdit['subcategory_id'] = ''; //se inicializa la variable subcategory_id dentro de la variable product como vacia
    }

    #[On('variantGenerated')]
    public function updateProduct()
    {
        $this->product = $this->product->fresh(); //se refresca el producto para obtener los datos actualizados
    }

    public function mount($product)
    {
        $this->productEdit =  $product->only('sku', 'name', 'description', 'image_path', 'price', 'stock', 'subcategory_id'); //solo se obtienen los campos necesarios del producto con only

        $this->families = Family::all(); //cargar todos los datos de la tabla families

        $this->category_id = $product->subcategory->category->id; //se obtiene el id de la categoria del producto

        $this->family_id = $product->subcategory->category->family->id; //se obtiene el id de la familia del producto
    }

    public function boot() //la funcion boot es similar a la funcion mount pero se ejecuta cada vez que se renderiza el componente
    {
        $this->withValidator(function ($validator) { //se utiliza el metodo withValidator para validar los campos del formulario
            if ($validator->fails()) { //si la validacion falla
                $this->dispatch('swal', [ //se utiliza el metodo dispatch para mostrar una alerta de SweetAlert
                    'icon' => 'error',
                    'title' => '¡Error!',
                    'text' => 'Por favor, completa todos los campos requeridos.',
                ]);
            }
        });
    }

    #[Computed()]
    public function categories() //metodo categories para obtener las categorias de una familia
    {
        return Category::where('family_id', $this->family_id)->get();
        //obtiene las categorias de una familia comparando el id de la familia selecciona con la variable family_id definida en el formulario
    }

    #[Computed()]
    public function subcategories() //metodo subcategories para obtener las subcategorias de una categoria
    {
        return Subcategory::where('category_id', $this->category_id)->get();
        //obtiene las subcategorias de una categoria comparando el id de la categoria selecciona con la variable category_id definida en el formulario
    }

    public function store()
    {
        $this->validate([
            'image' => 'nullable|image|max:1024', //maximo 1mb
            //el campo sku es requerido y debe ser unico, pero se excluye pasando el id del producto en el caso de editar
            'productEdit.sku' => 'required|unique:products,sku,'.$this->product->id,
            'productEdit.name' => 'required|max:255', //el campo name es requerido
            'productEdit.description' => 'nullable', //el campo description puede ser nulo
            'productEdit.price' => 'required|numeric|min:0', //el campo price es requerido y debe ser numerico
            'productEdit.stock' => 'required|integer|min:0', //el campo stock es requerido y debe ser entero
            'productEdit.subcategory_id' => 'required|exists:subcategories,id', //el campo subcategory_id es requerido
        ], [], [
            'productEdit.sku' => 'SKU',
            'productEdit.name' => 'Nombre',
            'productEdit.description' => 'Descripción',
            'productEdit.price' => 'Precio',
            'productEdit.stock' => 'Stock',
            'productEdit.subcategory_id' => 'Subcategoría',
        ]);

        if ($this->image) {//si la imagen es diferente de nulo o sea si se subio una nueva imagen
            Storage::delete($this->productEdit['image_path']); //elimina la imagen anterior
            $this->productEdit['image_path'] = $this->image->store('products'); //almacena la nueva imagen en la carpeta products
        }

        $this->product->update($this->productEdit); //actualiza el producto con los nuevos datos referenciados en productEdit

        $this->dispatch('swal', [ //se utiliza el metodo dispatch para mostrar una alerta de SweetAlert
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'El producto se ha actualizado correctamente.',
        ]);

        return redirect()->route('admin.products.edit', $this->product); //redirecciona a la vista de editar producto y le pasa el id del producto editado para que se actulice
    }

    public function render()
    {
        return view('livewire.admin.products.product-edit');
    }
}
