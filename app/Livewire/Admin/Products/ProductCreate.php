<?php

namespace App\Livewire\Admin\Products;

use App\Models\Family;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{

    use WithFileUploads; //se utiliza el trait WithFileUploads para poder subir archivos

    public $families; //variable publica families para almacenar y extraer los datos de la tabla families

    public $family_id = ''; //variable publica family_id para almacenar el id de la familia seleccionada

    public $category_id = ''; //variable publica category_id para almacenar el id de la categoria seleccionada

    public $image; //variable publica image para almacenar la imagen del producto

    public $product = [ //variable publica product inicializada como un arreglo para almacenar los datos del formulario
        'sku' => '',
        'name' => '',
        'description' => '',
        'image_path' => '',
        'price' => '',
        'subcategory_id' => ''
    ];

    public function updatedFamilyId($value)
    //metodo updatedFamilyId se ejecuta cada vez que se actualiza la variable family_id para resetera los campos en category y subcategory
    {
        $this->category_id = ''; //se inicializa la variable category_id como vacia
        $this->product['subcategory_id'] = ''; //se inicializa la variable subcategory_id dentro de la variable product como vacia
    }

    public function updatedCategoryId($value)
    //metodo updatedCategoryId se ejecuta cada vez que se actualiza la variable category_id para resetear el campo en subcategory
    {
        $this->product['subcategory_id'] = ''; //se inicializa la variable subcategory_id dentro de la variable product como vacia
    }

    public function mount() //la funcion mount se ejecuta una sola vez al cargar el componente
    {
        $this->families = Family::all(); //cargar todos los datos de la tabla families
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
        $this->validate([ //se valida el formulario
            'image' => 'required|image|max:1024', //el campo image definido en la variable $image es una imagen y su tamaño maximo es de 1MB
            'product.sku' => 'required|unique:products,sku', //el campo sku es requerido y debe ser unico en la tabla products
            'product.name' => 'required|max:255', //el campo name es requerido
            'product.description' => 'nullable', //el campo description puede ser nulo
            'product.price' => 'required|numeric|min:0', //el campo price es requerido y debe ser numerico
            'product.subcategory_id' => 'required|exists:subcategories,id', //el campo subcategory_id es requerido
        ],[], [ //se definen los nombres de los campos para mostrar en el mensaje de error
            'product.sku' => 'SKU',
            'product.name' => 'Nombre',
            'product.description' => 'Descripción',
            'product.price' => 'Precio',
            'product.subcategory_id' => 'Subcategoría',
            'image' => 'Imagen',
        ]);

        $this->product['image_path'] = $this->image->store('products', 'public'); //se almacena la imagen en la carpeta products dentro de la carpeta public
        //el nombre de la imagen se almacena en la variable image_path dentro de la variable product

        $product = Product::create($this->product); //se crea un nuevo producto en la tabla products con los datos del formulario
        //se almacena el producto en la variable product

        session()->flash('swal', [ //se almacena un mensaje en la session para mostrar una alerta de SweetAlert]
            'icon' => 'success',
            'title' => '¡Producto creado!',
            'text' => 'El producto se ha creado correctamente',
        ]);

        return redirect()->route('admin.products.edit', $product); //redirecciona a la vista de editar producto y le pasa el id del producto creado

    }

    public function render()
    {
        return view('livewire.admin.products.product-create');
    }
}
