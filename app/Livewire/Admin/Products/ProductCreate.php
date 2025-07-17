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
    public $image2; //variable publica image2 para almacenar la segunda imagen del producto
    public $image3; //variable publica image3 para almacenar la tercera imagen del producto

    // URLs temporales para preservar preview durante validación
    public $temporaryImageUrl;
    public $temporaryImageUrl2;
    public $temporaryImageUrl3;

    // Flags para saber qué imágenes están cargadas
    public $imageUploaded = false;
    public $image2Uploaded = false;
    public $image3Uploaded = false;

    public $product = [ //variable publica product inicializada como un arreglo para almacenar los datos del formulario
        'sku' => '',
        'name' => '',
        'description' => '',
        'general_features' => '',
        'recommended_preparation' => '',
        'image_path' => '',
        'image_2' => '',
        'image_3' => '',
        'price' => '',
        'subcategory_id' => ''
    ];

    // Reglas de validación para las imágenes
    protected $rules = [
        'image' => 'required|image|max:1024',
        'image2' => 'nullable|image|max:1024',
        'image3' => 'nullable|image|max:1024',
        'product.sku' => 'required|unique:products,sku',
        'product.name' => 'required|max:255',
        'product.description' => 'nullable',
        'product.general_features' => 'nullable',
        'product.recommended_preparation' => 'nullable',
        'product.price' => 'required|numeric|min:0',
        'product.subcategory_id' => 'required|exists:subcategories,id',
    ];

    // Nombres de atributos para mensajes de error
    protected $validationAttributes = [
        'product.sku' => 'SKU',
        'product.name' => 'Nombre',
        'product.description' => 'Descripción',
        'product.general_features' => 'Características Generales',
        'product.recommended_preparation' => 'Preparación Recomendada',
        'product.price' => 'Precio',
        'product.subcategory_id' => 'Subcategoría',
        'image' => 'Imagen Principal',
        'image2' => 'Segunda Imagen',
        'image3' => 'Tercera Imagen',
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

    /**
     * Se ejecuta cuando se actualiza la imagen principal
     */
    public function updatedImage()
    {
        if ($this->image) {
            // Validar la imagen inmediatamente
            $this->validateOnly('image');

            try {
                // Crear URL temporal para preview
                $this->temporaryImageUrl = $this->image->temporaryUrl();
                $this->imageUploaded = true;

                // Emitir evento para mostrar notificación
                $this->dispatch('imageUploaded', ['type' => 'imagen principal']);
            } catch (\Exception $e) {
                $this->addError('image', 'Error al procesar la imagen');
                $this->image = null;
                $this->imageUploaded = false;
            }
        }
    }

    /**
     * Se ejecuta cuando se actualiza la segunda imagen
     */
    public function updatedImage2()
    {
        if ($this->image2) {
            // Validar la imagen inmediatamente
            $this->validateOnly('image2');

            try {
                // Crear URL temporal para preview
                $this->temporaryImageUrl2 = $this->image2->temporaryUrl();
                $this->image2Uploaded = true;

                // Emitir evento para mostrar notificación
                $this->dispatch('imageUploaded', ['type' => 'segunda imagen']);
            } catch (\Exception $e) {
                $this->addError('image2', 'Error al procesar la segunda imagen');
                $this->image2 = null;
                $this->image2Uploaded = false;
            }
        }
    }

    /**
     * Se ejecuta cuando se actualiza la tercera imagen
     */
    public function updatedImage3()
    {
        if ($this->image3) {
            // Validar la imagen inmediatamente
            $this->validateOnly('image3');

            try {
                // Crear URL temporal para preview
                $this->temporaryImageUrl3 = $this->image3->temporaryUrl();
                $this->image3Uploaded = true;

                // Emitir evento para mostrar notificación
                $this->dispatch('imageUploaded', ['type' => 'tercera imagen']);
            } catch (\Exception $e) {
                $this->addError('image3', 'Error al procesar la tercera imagen');
                $this->image3 = null;
                $this->image3Uploaded = false;
            }
        }
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
        try {
            $this->validate(); //se valida el formulario usando las reglas definidas en la propiedad $rules

            $this->product['image_path'] = $this->image->store('products', 'public'); //se almacena la imagen en la carpeta products dentro de la carpeta public

            // Almacenar las imágenes adicionales si se proporcionan
            if ($this->image2) {
                $this->product['image_2'] = $this->image2->store('products', 'public');
            }
            if ($this->image3) {
                $this->product['image_3'] = $this->image3->store('products', 'public');
            }

            $product = Product::create($this->product); //se crea un nuevo producto en la tabla products con los datos del formulario
            //se almacena el producto en la variable product

            // Resetear las imágenes para que desaparezcan los spinners
            $this->reset(['image', 'image2', 'image3']);

            session()->flash('swal', [ //se almacena un mensaje en la session para mostrar una alerta de SweetAlert]
                'icon' => 'success',
                'title' => '¡Producto creado!',
                'text' => 'El producto se ha creado correctamente',
            ]);

            return redirect()->route('admin.products.edit', $product); // redirecciona correctamente usando return

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Preservar las URLs temporales de las imágenes en caso de error de validación
            $this->preserveTemporaryImages();

            // Re-lanzar la excepción para que se muestren los errores
            throw $e;
        }
    }

    /**
     * Preserva las URLs temporales de las imágenes después de un error de validación
     */
    private function preserveTemporaryImages()
    {
        try {
            if ($this->image && $this->imageUploaded) {
                $this->temporaryImageUrl = $this->image->temporaryUrl();
            }
            if ($this->image2 && $this->image2Uploaded) {
                $this->temporaryImageUrl2 = $this->image2->temporaryUrl();
            }
            if ($this->image3 && $this->image3Uploaded) {
                $this->temporaryImageUrl3 = $this->image3->temporaryUrl();
            }
        } catch (\Exception $e) {
            // Si no se pueden generar las URLs temporales, no hacer nada
            // Las imágenes se perderán pero no causará errores
        }
    }

    public function render()
    {
        return view('livewire.admin.products.product-create');
    }
}
