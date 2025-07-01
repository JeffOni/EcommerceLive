<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        // Configurar la instancia del carrito
        Cart::instance('shopping');

        // Obtener los items del carrito
        $cartItems = Cart::content()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'quantity' => $item->qty,
                'subtotal' => (float) ($item->price * $item->qty),
                'rowId' => $item->rowId,
                'options' => $item->options->toArray() ?? []
            ];
        })->values()->toArray();

        // Calcular totales
        $subtotal = (float) Cart::subtotal(2, '.', '');
        $tax = (float) Cart::tax(2, '.', '');
        $total = (float) Cart::total(2, '.', '');

        // Costo de envío
        $shipping = 5.00;
        $totalWithShipping = $total + $shipping;

        // Verificar que hay items en el carrito
        if (Cart::count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        // Pasar los datos a la vista
        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'total',
            'shipping',
            'totalWithShipping'
        ));
    }

    /**
     * Mostrar página de agradecimiento después del pago
     */
    public function thankYou()
    {
        return view('checkout.thank-you');
    }
}
