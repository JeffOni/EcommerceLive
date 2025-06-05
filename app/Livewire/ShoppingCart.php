<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use App\Models\Product;

class ShoppingCart extends Component
{
    public function mount()
    {
        Cart::instance('shopping');
    }
    public function increaseQuantity($rowId)
    {
        Cart::instance('shopping');
        $item = Cart::get($rowId);
        if ($item) {
            $availableStock = $item->options->stock ?? 1;
            $newQuantity = $item->qty + 1;

            // Prevenir sobrepasar el stock disponible
            if ($newQuantity > $availableStock) {
                return;
            }

            Cart::update($rowId, $newQuantity);
            if (auth()->check()) {
                Cart::store(auth()->id());
            }
        }
        $this->dispatch('cartUpdated', Cart::count());
    }
    public function decreaseQuantity($rowId)
    {
        Cart::instance('shopping');
        $item = Cart::get($rowId);
        if ($item && $item->qty > 1) {
            $newQuantity = $item->qty - 1;
            Cart::update($rowId, $newQuantity);
            if (auth()->check()) {
                Cart::store(auth()->id());
            }
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
        $this->dispatch('cartUpdated', Cart::count());
    }
    public function clearCart()
    {
        Cart::instance('shopping');
        Cart::destroy();
        if (auth()->check()) {
            Cart::store(auth()->id());
        }
        $this->dispatch('cartUpdated', Cart::count());
    }

    public function render()
    {
        $cart = Cart::content()->map(function ($item) {
            $product = Product::find($item->id);
            $item->options->stock = $product ? $product->stock : 1;
            return $item;
        });
        return view('livewire.shopping-cart', [
            'cart' => $cart
        ]);
    }
}
