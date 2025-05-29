<?php

namespace App\Livewire\Products;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class AddToCart extends Component
{
    public $product;

    public $quantity = 1;

    public function addToCart()
    {

        Cart::instance('shopping');
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->quantity,
            'price' => $this->product->price,
            // 'weight' => 0, // Peso opcional, si no se usa puede ser 0
            'options' => [
                'image' => $this->product->image, // Suponiendo que el producto tiene una imagen
                'sku' => $this->product->sku, // SKU del producto
                'features' => []
                // Puedes agregar más opciones si es necesario
            ],
        ]);
        // Lógica para agregar al carrito
        $this->dispatch('swal', [
            'title' => 'Producto agregado al carrito!',
            'text' => 'El producto ' . $this->product->name . ' ha sido agregado exitosamente.',
            'icon' => 'success',
            'timer' => 3000,
            'showConfirmButton' => false,
        ]);

        // Resetear cantidad después de agregar
        // $this->quantity = 1; // Reinicia la cantidad después de agregar al carrito
    }

    public function mount($product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.products.add-to-cart');
    }
}
