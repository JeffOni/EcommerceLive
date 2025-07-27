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

        return view('admin.products.variants', compact('product', 'variant'));
    }

    public function variantsUpdate(Request $request, Product $product, Variant $variant)
    {
        // Intentar recuperar imagen temporal de la sesión si existe
        $tempImageData = session('temp_variant_image');

        try {
            $request->validate([
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif|max:2048',
                'sku' => 'required|string|max:255',
                'stock' => 'required|integer|min:0',
                'custom_price' => 'nullable|numeric|min:0',
            ], [], [
                'sku' => 'SKU',
                'stock' => 'Stock',
                'custom_price' => 'Precio personalizado',
                'image' => 'Imagen'
            ]); //valida los datos del request

            // Limpiar imagen temporal de la sesión ya que la validación pasó
            session()->forget('temp_variant_image');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación Y hay imagen temporal, activar el flag para mostrar toast
            if ($tempImageData) {
                session()->flash('show_variant_image_restored_toast', true);
            }
            throw $e; // Re-lanzar la excepción para que Laravel maneje el redirect
        }

        // Inicializar el array $data con los campos del formulario
        $data = [
            'sku' => $request->sku,
            'stock' => $request->stock,
            'custom_price' => $request->custom_price ? $request->custom_price : null,
        ];

        // Si hay una imagen nueva, procesarla y agregarla a $data
        if ($request->hasFile('image')) {
            // Asegura que la carpeta exista antes de guardar la imagen
            Storage::disk('public')->makeDirectory('products/variants');
            if ($variant->image_path) { //si la variante ya tenía una imagen
                Storage::disk('public')->delete($variant->image_path); //elimina la imagen anterior
            }
            $data['image_path'] = $request->file('image')->store('products/variants', 'public'); //almacena la nueva imagen y guarda la ruta
        }
        // Si había una imagen temporal guardada en sesión, usarla
        elseif ($tempImageData && Storage::disk('public')->exists('temp_uploads/' . $tempImageData['filename'])) {
            // Asegura que la carpeta exista
            Storage::disk('public')->makeDirectory('products/variants');
            if ($variant->image_path) {
                Storage::disk('public')->delete($variant->image_path);
            }

            // Mover imagen temporal a ubicación final
            $finalPath = 'products/variants/' . $tempImageData['filename'];
            Storage::disk('public')->move('temp_uploads/' . $tempImageData['filename'], $finalPath);

            $data['image_path'] = $finalPath;
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

    /**
     * Maneja la subida temporal de imágenes para variantes
     */
    public function uploadTempVariantImage(Request $request)
    {
        try {
            $validated = $request->validate([
                'image' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,gif,bmp,webp,svg,avif',
                    'max:2048',
                    // Validación simplificada solo para longitud de nombre
                    function ($attribute, $value, $fail) {
                        if ($value && mb_strlen($value->getClientOriginalName()) > 100) {
                            $fail('El nombre del archivo es demasiado largo (máximo 100 caracteres).');
                        }
                    }
                ]
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = uniqid('variant_') . '.' . $image->getClientOriginalExtension();

                // Crear directorio temporal en disco público
                Storage::disk('public')->makeDirectory('temp_uploads');

                // Guardar imagen temporalmente en disco público
                $path = $image->storeAs('temp_uploads', $filename, 'public');

                session([
                    'temp_variant_image' => [
                        'filename' => $filename,
                        'original_name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'url' => asset('storage/' . $path) // URL pública correcta
                    ]
                ]);

                return response()->json([
                    'success' => true,
                    'preview_url' => asset('storage/' . $path),
                    'filename' => $filename
                ]);
            }

            return response()->json(['success' => false, 'message' => 'No se pudo subir la imagen'], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error uploading variant image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al subir la imagen'
            ], 500);
        }
    }

    /**
     * Limpia el flag de sesión para el toast de imagen recuperada
     */
    public function clearToastFlag(Request $request)
    {
        session()->forget('show_variant_image_restored_toast');
        return response()->json(['success' => true]);
    }

    /**
     * Toggle del estado is_active del producto
     */
    public function toggleStatus(Request $request, Product $product)
    {
        try {
            $request->validate([
                'is_active' => 'required|boolean'
            ]);

            $product->is_active = $request->is_active;
            $product->save();

            $status = $product->is_active ? 'activado' : 'desactivado';
            $message = "El producto ha sido {$status} exitosamente.";

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $product->is_active
            ]);

        } catch (\Exception $e) {
            \Log::error('Error toggling product status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado del producto'
            ], 500);
        }
    }
}
