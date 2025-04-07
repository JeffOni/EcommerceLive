<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
}
