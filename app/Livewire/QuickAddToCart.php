<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class QuickAddToCart extends Component
{
    public $product;

    public function addToCart()
    {
        // Validar que el producto tenga stock disponible
        if (!$this->product->hasAvailableStock()) {
            $this->dispatch('swal', [
                'title' => 'Producto no disponible',
                'text' => 'Este producto no tiene stock disponible.',
                'icon' => 'error',
                'timer' => 3000,
                'showConfirmButton' => false,
            ]);
            return;
        }

        Cart::instance('shopping');

        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => 1,
            'price' => $this->product->current_price,
            'options' => [
                'image' => $this->product->image,
                'sku' => $this->product->sku,
                'stock' => $this->product->getAvailableStock(),
                'features' => [],
                'original_price' => $this->product->price,
                'is_on_offer' => $this->product->is_on_valid_offer,
                'offer_name' => $this->product->offer_name ?? null,
                'discount_percentage' => $this->product->discount_percentage ?? 0,
            ],
        ]);

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        $this->dispatch('cartUpdated', Cart::count());

        $this->dispatch('swal', [
            'title' => '¡Agregado al carrito!',
            'text' => $this->product->name . ' ha sido agregado al carrito.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        return view('livewire.quick-add-to-cart');
    }
}
