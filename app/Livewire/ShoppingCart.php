<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use App\Models\Product;

class ShoppingCart extends Component
{

    public function mount()
    {
        // Aquí puedes inicializar cualquier dato necesario para el componente
        // Por ejemplo, cargar el carrito de compras desde la sesión o base de datos
        Cart::instance('shopping');
    }
    public function increaseQuantity($rowId)
    {
        Cart::instance('shopping');

        // Obtener el item del carrito
        $item = Cart::get($rowId);

        if ($item) {
            // Obtener el producto para verificar el stock
            $product = Product::find($item->id);

            if (!$product) {
                session()->flash('error', 'Producto no encontrado.');
                return;
            }

            // Calcular la nueva cantidad
            $newQuantity = $item->qty + 1;

            // Verificar si hay suficiente stock
            if ($newQuantity > $product->stock) {
                session()->flash('error', "Solo quedan {$product->stock} unidades disponibles de este producto.");
                return;
            }

            // Incrementar la cantidad si hay stock suficiente
            Cart::update($rowId, $newQuantity);

            if (auth()->check()) {
                // Si el usuario está autenticado, actualizamos el carrito del usuario
                Cart::store(auth()->id());
            }

            session()->flash('success', "Cantidad actualizada. Stock disponible: " . ($product->stock - $newQuantity));
        } else {
            // Maneja el caso en que el producto no está en el carrito
            session()->flash('error', 'El producto no está en el carrito.');
        }

        $this->dispatch('cartUpdated', Cart::count());
    }
    public function decreaseQuantity($rowId)
    {
        Cart::instance('shopping');

        $item = Cart::get($rowId);

        if ($item && $item->qty > 1) {
            // Disminuye la cantidad del producto en el carrito
            $newQuantity = $item->qty - 1;
            Cart::update($rowId, $newQuantity);

            if (auth()->check()) {
                Cart::store(auth()->id());
            }

            session()->flash('success', 'Cantidad actualizada correctamente.');
        } else {
            session()->flash('error', 'No se puede reducir más la cantidad.');
        }

        $this->dispatch('cartUpdated', Cart::count());
    }

    public function removeItem($rowId)
    {
        Cart::instance('shopping');
        Cart::remove($rowId);

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        session()->flash('success', 'Producto eliminado del carrito.');
        $this->dispatch('cartUpdated', Cart::count());
    }

    public function clearCart()
    {
        Cart::instance('shopping');
        Cart::destroy();

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        session()->flash('success', 'Carrito limpiado correctamente.');
        $this->dispatch('cartUpdated', Cart::count());
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
