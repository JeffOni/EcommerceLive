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
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12); // Valor por defecto: 12
        $search = $request->get('search');

        $query = Product::orderBy('id', 'desc')
            ->with('subcategory.category.family'); // carga las relaciones completas

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhereHas('subcategory', function ($subcategoryQuery) use ($search) {
                        $subcategoryQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhereHas('category', function ($categoryQuery) use ($search) {
                                $categoryQuery->where('name', 'like', '%' . $search . '%')
                                    ->orWhereHas('family', function ($familyQuery) use ($search) {
                                        $familyQuery->where('name', 'like', '%' . $search . '%');
                                    });
                            });
                    });
            });
        }

        $products = $query->paginate($perPage);

        // Mantener parámetros de búsqueda y paginación en los enlaces
        $products->appends($request->only(['search', 'per_page']));

        // Si es una petición AJAX, devolver solo el contenido
        if ($request->ajax()) {
            return view('admin.products.partials.products-content', compact('products'))->render();
        }

        return view('admin.products.index', compact('products'));
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
            'custom_price' => 'nullable|numeric|min:0',
        ]); //valida los datos del request

        // Inicializar el array $data con los campos del formulario
        $data = [
            'sku' => $request->sku,
            'stock' => $request->stock,
            'custom_price' => $request->custom_price ? $request->custom_price : null,
            // Agrega aquí otros campos que necesites actualizar
        ];

        // Si hay una imagen nueva, procesarla y agregarla a $data
        if ($request->image) {
            // Asegura que la carpeta exista antes de guardar la imagen
            Storage::disk('public')->makeDirectory('products/variants');
            if ($variant->image_path) { //si la variante ya tenía una imagen
                Storage::delete($variant->image_path); //elimina la imagen anterior
            }
            $data['image_path'] = $request->image->store('products/variants', 'public'); //almacena la nueva imagen y guarda la ruta
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
