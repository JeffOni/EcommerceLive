<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::orderBy('id', 'desc') //ordena los registros de la tabla por el id de forma descendente
            ->with('subcategory.category.family') //con esta funcion se carga la relacion de subcategoria
            ->paginate(); //muestra todos los registros en la tabla de forma paginada es decir carga los datos como se los va necesitando
        return view('admin.products.index', compact('products')); //retorna la vista de los productos y le pasa la variable products
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.products.create'); //retorna la vista de crear productos
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        Storage::delete($product->image_path); //elimina la imagen del producto

        $product->delete();
        //el session swal se pasa directo al return en lugar de una variable de sseion es mas directo y ligeramente mas eficiente

        //ejemplo con session flash
        // session()->flash('swal', [
        //     'icon' => 'success',
        //     'title' => '¡Éxito!',
        //     'text' => 'El producto se ha eliminado correctamente.',
        //     'timeout' => 3000
        // ]);
        return redirect()->route('admin.products.index')->with('swal', [ //redirecciona a la vista de productos y le pasa un mensaje de exito
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'El producto se ha eliminado correctamente.',
            'timeout' => 3000
        ]);
    }

    public function variants(Product $product, Variant $variant)
    {
        //muestra la vista de variantes de un producto
        //se pasa el producto para que se pueda usar en la vista
        //se pasa la variante para que se pueda usar en la vista
        //se puede usar el metodo load para cargar las relaciones de la variante

        return view('admin.products.variants', compact('product', 'variant')); //retorna la vista de variantes y le pasa el producto y la variante
    }

    public function variantsUpdate(Request $request, Product $product, Variant $variant)
    {
        //actualiza la variante de un producto
        //se pasa el producto para que se pueda usar en la vista
        //se pasa la variante para que se pueda usar en la vista
        //se puede usar el metodo load para cargar las relaciones de la variante
        $request->validate([
            'image' => 'nullable|image|max:2048',
            'sku' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]); //valida los datos del request

        // Inicializar el array $data con los campos del formulario
        $data = [
            'sku' => $request->sku,
            'stock' => $request->stock,
            // Agrega aquí otros campos que necesites actualizar
        ];

        // Si hay una imagen nueva, procesarla y agregarla a $data
        if ($request->image) {
            if ($variant->image_path) { //si la variante ya tenía una imagen
                Storage::delete($variant->image_path); //elimina la imagen anterior
            }
            $data['image_path'] = $request->image->store('products/variants'); //almacena la nueva imagen y guarda la ruta
        }

        $variant->update($data); //actualiza la variante con los datos

        session()->flash('swal', [ //se utiliza el metodo session para mostrar una alerta de SweetAlert
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'La variante se ha actualizado correctamente.',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.products.variants', [$product, $variant]); //redirecciona a la vista de variantes
    }
}
