<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OfferController extends Controller
{
    /**
     * Mostrar lista de productos con ofertas
     */
    public function index()
    {
        $products = Product::with(['subcategory.category.family'])
            ->where('is_on_offer', true)
            ->orderBy('offer_ends_at', 'asc')
            ->paginate(15);

        $stats = [
            'total_offers' => Product::where('is_on_offer', true)->count(),
            'active_offers' => Product::onValidOffer()->count(),
            'expired_offers' => Product::where('is_on_offer', true)
                ->where('offer_ends_at', '<', now())
                ->count(),
            'total_savings' => $this->calculateTotalSavings()
        ];

        return view('admin.offers.index', compact('products', 'stats'));
    }

    /**
     * Mostrar formulario para crear oferta
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->where('is_on_offer', false)
            ->with(['subcategory.category.family'])
            ->orderBy('name')
            ->get();

        return view('admin.offers.create', compact('products'));
    }

    /**
     * Crear nueva oferta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'offer_name' => 'required|string|max:255',
            'offer_type' => 'required|in:percentage,fixed_amount',
            'offer_value' => 'required|numeric|min:0',
            'starts_at' => 'required|date|after_or_equal:today',
            'ends_at' => 'required|date|after:starts_at',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id'
        ]);

        $productsUpdated = 0;

        foreach ($validated['products'] as $productId) {
            $product = Product::find($productId);

            if ($product && !$product->is_on_offer) {
                $offerPrice = $this->calculateOfferPrice($product->price, $validated['offer_type'], $validated['offer_value']);
                $percentage = $this->calculatePercentage($product->price, $offerPrice);

                $product->update([
                    'is_on_offer' => true,
                    'offer_price' => $offerPrice,
                    'offer_percentage' => $percentage,
                    'offer_starts_at' => $validated['starts_at'],
                    'offer_ends_at' => $validated['ends_at'],
                    'offer_name' => $validated['offer_name']
                ]);

                $productsUpdated++;
            }
        }

        return redirect()->route('admin.offers.index')
            ->with('success', "Oferta '{$validated['offer_name']}' creada exitosamente para {$productsUpdated} productos.");
    }

    /**
     * Editar oferta individual de un producto
     */
    public function edit(Product $product)
    {
        if (!$product->is_on_offer) {
            return redirect()->route('admin.offers.index')
                ->with('error', 'Este producto no tiene oferta activa.');
        }

        return view('admin.offers.edit', compact('product'));
    }

    /**
     * Actualizar oferta
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'offer_name' => 'required|string|max:255',
            'offer_type' => 'required|in:percentage,fixed_amount',
            'offer_value' => 'required|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        $offerPrice = $this->calculateOfferPrice($product->price, $validated['offer_type'], $validated['offer_value']);
        $percentage = $this->calculatePercentage($product->price, $offerPrice);

        $product->update([
            'offer_price' => $offerPrice,
            'offer_percentage' => $percentage,
            'offer_starts_at' => $validated['starts_at'],
            'offer_ends_at' => $validated['ends_at'],
            'offer_name' => $validated['offer_name']
        ]);

        return redirect()->route('admin.offers.index')
            ->with('success', "Oferta de '{$product->name}' actualizada exitosamente.");
    }

    /**
     * Eliminar oferta
     */
    public function destroy(Product $product)
    {
        if ($product->is_on_offer) {
            $product->update([
                'is_on_offer' => false,
                'offer_price' => null,
                'offer_percentage' => null,
                'offer_starts_at' => null,
                'offer_ends_at' => null,
                'offer_name' => null
            ]);

            return redirect()->route('admin.offers.index')
                ->with('success', "Oferta de '{$product->name}' eliminada exitosamente.");
        }

        return redirect()->route('admin.offers.index')
            ->with('error', 'Este producto no tiene oferta activa.');
    }

    /**
     * Limpiar ofertas vencidas
     */
    public function cleanExpired()
    {
        $expiredProducts = Product::where('is_on_offer', true)
            ->where('offer_ends_at', '<', now())
            ->get();

        foreach ($expiredProducts as $product) {
            $product->update([
                'is_on_offer' => false,
                'offer_price' => null,
                'offer_percentage' => null,
                'offer_starts_at' => null,
                'offer_ends_at' => null,
                'offer_name' => null
            ]);
        }

        return redirect()->route('admin.offers.index')
            ->with('success', "Se limpiaron {$expiredProducts->count()} ofertas vencidas.");
    }

    /**
     * Calcular precio con oferta
     */
    private function calculateOfferPrice($originalPrice, $type, $value)
    {
        switch ($type) {
            case 'percentage':
                return $originalPrice - ($originalPrice * ($value / 100));
            case 'fixed_amount':
                return max(0, $originalPrice - $value);
            default:
                return $originalPrice;
        }
    }

    /**
     * Calcular porcentaje de descuento
     */
    private function calculatePercentage($originalPrice, $offerPrice)
    {
        if ($originalPrice <= 0)
            return 0;
        return round((($originalPrice - $offerPrice) / $originalPrice) * 100);
    }

    /**
     * Calcular ahorros totales
     */
    private function calculateTotalSavings()
    {
        return Product::onValidOffer()
            ->get()
            ->sum(function ($product) {
                return $product->price - $product->offer_price;
            });
    }
}
