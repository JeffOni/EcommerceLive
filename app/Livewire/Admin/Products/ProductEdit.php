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

    public $image; //variable publica image para almacenar la imagen principal del producto
    public $image2; //variable publica image2 para almacenar la segunda imagen
    public $image3; //variable publica image3 para almacenar la tercera imagen

    /**
     * Validación en tiempo real cuando se actualiza la imagen
     */
    public function updatedImage()
    {
        // Si no hay imagen, no hacer nada
        if (!$this->image) {
            return;
        }

        try {
            $this->validateOnly('image', [
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif|max:2048'
            ]);

            // Si la validación es exitosa, disparar evento para ocultar spinner
            $this->dispatch('image-uploaded');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay error de validación, resetear la imagen para quitar el spinner
            $this->reset('image');

            // Re-lanzar la excepción para mostrar el error
            throw $e;
        }
    }

    /**
     * Validación en tiempo real cuando se actualiza la segunda imagen
     */
    public function updatedImage2()
    {
        if (!$this->image2) {
            return;
        }
        try {
            $this->validateOnly('image2', [
                'image2' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif|max:2048'
            ]);
            $this->dispatch('image2-uploaded');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset('image2');
            throw $e;
        }
    }

    /**
     * Validación en tiempo real cuando se actualiza la tercera imagen
     */
    public function updatedImage3()
    {
        if (!$this->image3) {
            return;
        }
        try {
            $this->validateOnly('image3', [
                'image3' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif|max:2048'
            ]);
            $this->dispatch('image3-uploaded');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset('image3');
            throw $e;
        }
    }

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
        // Usar getAttributes() para obtener todos los atributos del modelo de manera segura
        $attributes = $product->getAttributes();

        $this->productEdit = [
            'sku' => $attributes['sku'] ?? '',
            'name' => $attributes['name'] ?? '',
            'description' => $attributes['description'] ?? '',
            'image_path' => $attributes['image_path'] ?? '',
            'image_2' => $attributes['image_2'] ?? '',
            'image_3' => $attributes['image_3'] ?? '',
            'price' => $attributes['price'] ?? 0,
            'stock' => $attributes['stock'] ?? 0,
            'subcategory_id' => $attributes['subcategory_id'] ?? ''
        ];

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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif|max:2048',
            'image2' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif|max:2048',
            'image3' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif|max:2048',
            'productEdit.sku' => 'required|unique:products,sku,' . $this->product->id,
            'productEdit.name' => 'required|max:255',
            'productEdit.description' => 'nullable',
            'productEdit.price' => 'required|numeric|min:0',
            'productEdit.stock' => 'required|integer|min:0',
            'productEdit.subcategory_id' => 'required|exists:subcategories,id',
        ], [], [
            'productEdit.sku' => 'SKU',
            'productEdit.name' => 'Nombre',
            'productEdit.description' => 'Descripción',
            'productEdit.price' => 'Precio',
            'productEdit.stock' => 'Stock',
            'productEdit.subcategory_id' => 'Subcategoría',
            'image' => 'Imagen Principal',
            'image2' => 'Segunda Imagen',
            'image3' => 'Tercera Imagen',
        ]);

        // Imagen principal
        if ($this->image) {
            if (method_exists($this->image, 'getClientOriginalExtension')) {
                $extension = $this->image->getClientOriginalExtension();
                if (empty($extension)) {
                    $this->addError('image', 'La imagen debe tener una extensión válida.');
                    return;
                }
            }
            if (!empty($this->productEdit['image_path'])) {
                Storage::delete($this->productEdit['image_path']);
            }
            $this->productEdit['image_path'] = $this->image->store('products', 'public');
        }

        // Segunda imagen
        if ($this->image2) {
            if (method_exists($this->image2, 'getClientOriginalExtension')) {
                $extension = $this->image2->getClientOriginalExtension();
                if (empty($extension)) {
                    $this->addError('image2', 'La segunda imagen debe tener una extensión válida.');
                    return;
                }
            }
            if (!empty($this->productEdit['image_2'])) {
                Storage::delete($this->productEdit['image_2']);
            }
            $this->productEdit['image_2'] = $this->image2->store('products', 'public');
        }

        // Tercera imagen
        if ($this->image3) {
            if (method_exists($this->image3, 'getClientOriginalExtension')) {
                $extension = $this->image3->getClientOriginalExtension();
                if (empty($extension)) {
                    $this->addError('image3', 'La tercera imagen debe tener una extensión válida.');
                    return;
                }
            }
            if (!empty($this->productEdit['image_3'])) {
                Storage::delete($this->productEdit['image_3']);
            }
            $this->productEdit['image_3'] = $this->image3->store('products', 'public');
        }

        $this->product->update($this->productEdit);

        // Resetear las imágenes para que desaparezcan los spinners
        $this->reset(['image', 'image2', 'image3']);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'El producto se ha actualizado correctamente.',
        ]);

        return redirect()->route('admin.products.edit', $this->product);
    }

    public function render()
    {
        return view('livewire.admin.products.product-edit');
    }
}
