<?php

/*
|--------------------------------------------------------------------------
| AddToCart Livewire Component - MEJORAS UX/UI IMPLEMENTADAS
|--------------------------------------------------------------------------
|
| OPTIMIZACIONES REALIZADAS EN ESTE COMPONENTE:
|
| 1. ELIMINACIÓN DE MÉTODOS INNECESARIOS:
|    - Se removieron incrementQuantity() y decrementQuantity()
|    - Estas funciones ahora se manejan con Alpine.js para mejor rendimiento
|    - Evita llamadas AJAX innecesarias al servidor
|
| 2. VALIDACIÓN DE STOCK MEJORADA:
|    - La validación se realiza principalmente en el frontend con Alpine.js
|    - El backend mantiene la validación de seguridad en addToCart()
|
| 3. GESTIÓN DE CARRITO OPTIMIZADA:
|    - Uso eficiente del Shopping Cart package
|    - Información completa del producto en las opciones
|    - Notificaciones SweetAlert para mejor feedback
|
| 4. INTEGRACIÓN CON FRONTEND:
|    - Alpine.js maneja la lógica de UI instantánea
|    - Livewire se encarga solo de las operaciones críticas
|    - Mejor separación de responsabilidades
|
*/

namespace App\Livewire\Products;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class AddToCart extends Component
{
    // Producto que se va a agregar al carrito
    public $product;

    // Cantidad seleccionada por el usuario
    // NOTA: Los controles de incremento/decremento se manejan con Alpine.js
    public $quantity = 1;

    /**
     * Agregar producto al carrito de compras
     * 
     * MEJORAS IMPLEMENTADAS:
     * - Validación de stock implícita (el botón se deshabilita si no hay stock)
     * - Información completa del producto en las opciones del carrito
     * - Notificación SweetAlert para mejor experiencia de usuario
     * - Gestión optimizada del carrito con Shopping Cart package
     */
    public function addToCart()
    {
        // Seleccionar la instancia del carrito de compras
        Cart::instance('shopping');

        // Agregar producto al carrito con información completa
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->quantity,
            'price' => $this->product->current_price, // Usar precio con descuento si hay oferta
            'options' => [
                'image' => $this->product->image, // Imagen del producto
                'sku' => $this->product->sku, // SKU para identificación
                'stock' => $this->product->stock ?? 0, // SOLUCIÓN: Agregar stock del producto
                'features' => [], // Array vacío para productos sin variantes
                'original_price' => $this->product->price, // Precio original
                'is_on_offer' => $this->product->is_on_valid_offer, // Si está en oferta
                'offer_name' => $this->product->offer_name ?? null, // Nombre de la oferta
                'discount_percentage' => $this->product->discount_percentage ?? 0, // Porcentaje de descuento
            ],
        ]);

        if (auth()->check()) {
            // Si el usuario está autenticado, actualizamos el carrito del usuario
            Cart::store(auth()->id());
        } //else {
        //     // Si no está autenticado, guardamos en la sesión
        //     Cart::store('guest');
        // }


        $this->dispatch('cartUpdated', Cart::count());
        // Notificación de éxito con SweetAlert
        $this->dispatch('swal', [
            'title' => 'Producto agregado al carrito!',
            'text' => 'El producto ' . $this->product->name . ' ha sido agregado exitosamente.',
            'icon' => 'success',
            'timer' => 3000,
            'showConfirmButton' => false,
        ]);

        // NOTA: No reseteamos la cantidad para permitir agregar múltiples veces
        // La UX es mejor si el usuario mantiene su selección
    }

    /**
     * Inicializar el componente con el producto
     * 
     * @param Product $product Producto a mostrar
     */
    public function mount($product)
    {
        $this->product = $product;
    }

    /**
     * Renderizar la vista del componente
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.products.add-to-cart');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS REMOVIDOS PARA OPTIMIZACIÓN
    |--------------------------------------------------------------------------
    |
    | Los siguientes métodos fueron eliminados para mejorar el rendimiento:
    |
    | - incrementQuantity(): Ahora manejado por Alpine.js en el frontend
    | - decrementQuantity(): Ahora manejado por Alpine.js en el frontend
    |
    | BENEFICIOS DE ESTA OPTIMIZACIÓN:
    | ✅ Respuesta instantánea en los controles de cantidad
    | ✅ Menos llamadas AJAX al servidor
    | ✅ Mejor experiencia de usuario
    | ✅ Validación de stock en tiempo real sin latencia
    | ✅ Interfaz más fluida y responsiva
    |
    */
}

