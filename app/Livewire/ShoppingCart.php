<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use App\Models\Product;

/**
 * Componente Livewire para el Carrito de Compras
 * =============================================
 * 
 * Este componente maneja toda la funcionalidad del carrito de compras incluyendo:
 * - Gestión de cantidades de productos
 * - Eliminación de productos individuales
 * - Limpieza completa del carrito
 * - Actualización automática del carrito en tiempo real
 * - Persistencia del carrito para usuarios autenticados
 * 
 * @author Tu Nombre
 * @version 1.0
 */
class ShoppingCart extends Component
{
    /**
     * Inicialización del componente
     * ============================
     * 
     * Establece la instancia del carrito como 'shopping' para separar
     * diferentes tipos de carritos si fuera necesario (ej: wishlist)
     */
    public function mount()
    {
        // Configurar la instancia específica del carrito de compras
        Cart::instance('shopping');
    }
    /**
     * Incrementar la cantidad de un producto en el carrito
     * ===================================================
     * 
     * Aumenta en 1 la cantidad del producto especificado, respetando
     * las limitaciones de stock disponible para evitar sobreventa.
     * 
     * @param string $rowId Identificador único del producto en el carrito
     * @return void
     */
    public function increaseQuantity($rowId)
    {
        // Asegurar que estamos trabajando con la instancia correcta del carrito
        Cart::instance('shopping');

        // Obtener el item del carrito usando su ID único
        $item = Cart::get($rowId);

        if ($item) {
            // Obtener el stock disponible desde las opciones del producto
            // Si no existe la información de stock, usar 1 como valor por defecto
            $availableStock = $item->options->stock ?? 1;

            // Calcular la nueva cantidad propuesta
            $newQuantity = $item->qty + 1;

            // Validación de stock: prevenir que se exceda el stock disponible
            if ($newQuantity > $availableStock) {
                // Salir sin realizar cambios si se excede el stock
                return;
            }

            // Actualizar la cantidad del producto en el carrito
            Cart::update($rowId, $newQuantity);

            // Persistir el carrito para usuarios autenticados
            if (auth()->check()) {
                Cart::store(auth()->id());
            }
        }

        // Emitir evento para actualizar el contador del carrito en la interfaz
        $this->dispatch('cartUpdated', Cart::count());
    }
    /**
     * Decrementar la cantidad de un producto en el carrito
     * ===================================================
     * 
     * Disminuye en 1 la cantidad del producto especificado, manteniendo
     * un mínimo de 1 unidad. Si se quiere eliminar completamente el producto,
     * debe usarse el método removeItem().
     * 
     * @param string $rowId Identificador único del producto en el carrito
     * @return void
     */
    public function decreaseQuantity($rowId)
    {
        // Asegurar que estamos trabajando con la instancia correcta del carrito
        Cart::instance('shopping');

        // Obtener el item del carrito
        $item = Cart::get($rowId);

        // Verificar que el item existe y que la cantidad actual es mayor a 1
        if ($item && $item->qty > 1) {
            // Calcular la nueva cantidad (reducir en 1)
            $newQuantity = $item->qty - 1;

            // Actualizar la cantidad en el carrito
            Cart::update($rowId, $newQuantity);

            // Persistir cambios para usuarios autenticados
            if (auth()->check()) {
                Cart::store(auth()->id());
            }
        }

        // Emitir evento para actualizar el contador del carrito en tiempo real
        $this->dispatch('cartUpdated', Cart::count());
    }
    /**
     * Eliminar un producto específico del carrito
     * ==========================================
     * 
     * Remueve completamente un producto del carrito independientemente
     * de la cantidad que tenga. Esta acción es irreversible.
     * 
     * @param string $rowId Identificador único del producto en el carrito
     * @return void
     */
    public function removeItem($rowId)
    {
        // Asegurar que estamos trabajando con la instancia correcta del carrito
        Cart::instance('shopping');

        // Eliminar el producto del carrito usando su ID único
        Cart::remove($rowId);

        // Persistir cambios para usuarios autenticados
        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        // Emitir evento para actualizar el contador del carrito en la interfaz
        $this->dispatch('cartUpdated', Cart::count());
    }
    /**
     * Limpiar completamente el carrito de compras
     * ==========================================
     * 
     * Elimina todos los productos del carrito de una sola vez.
     * Esta acción es útil cuando el usuario quiere empezar de nuevo
     * o cuando se quiere limpiar el carrito después de una compra exitosa.
     * 
     * @return void
     */
    public function clearCart()
    {
        // Asegurar que estamos trabajando con la instancia correcta del carrito
        Cart::instance('shopping');

        // Destruir todo el contenido del carrito
        Cart::destroy();

        // Persistir el estado vacío para usuarios autenticados
        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        // Emitir evento para actualizar el contador del carrito (debería ser 0)
        $this->dispatch('cartUpdated', Cart::count());
    }

    /**
     * Renderizar la vista del carrito de compras
     * =========================================
     * 
     * Este método prepara los datos del carrito y los pasa a la vista.
     * Enriquece cada item del carrito con información actualizada de stock
     * desde la base de datos para mostrar disponibilidad en tiempo real.
     * 
     * @return \Illuminate\Contracts\View\View Vista del componente con datos del carrito
     */
    public function render()
    {
        // Obtener todos los productos del carrito y enriquecerlos con datos actualizados
        $cart = Cart::content()->map(function ($item) {
            // Buscar el producto en la base de datos para obtener información actualizada
            $product = Product::find($item->id);

            // Actualizar la información de stock en las opciones del item
            // Si el producto no existe o fue eliminado, usar 1 como stock por defecto
            $item->options->stock = $product ? $product->stock : 1;

            // Retornar el item con la información actualizada
            return $item;
        });

        // Retornar la vista con los datos del carrito procesados
        return view('livewire.shopping-cart', [
            'cart' => $cart
        ]);
    }
}
