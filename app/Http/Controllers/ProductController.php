<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Mostrar listado público de productos con filtros
     */
    public function index(Request $request)
    {
        $query = Product::with(['subcategory.category.family'])
            ->where('is_active', true);

        // Filtro por tipo
        switch ($request->get('filter')) {
            case 'offers':
                $query->onValidOffer();
                break;
            case 'bestsellers':
                // Aquí puedes agregar lógica para más vendidos
                $query->inRandomOrder(); // Por ahora, orden aleatorio
                break;
            case 'new':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        // Ordenamiento
        switch ($request->get('sort')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Cantidad por página
        $perPage = (int) $request->get('per_page', 12);
        $perPage = in_array($perPage, [12, 24, 48]) ? $perPage : 12;

        $products = $query->paginate($perPage);

        // Mantener parámetros en la paginación
        $products->appends($request->query());

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
