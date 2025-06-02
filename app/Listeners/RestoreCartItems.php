<?php

namespace App\Listeners;


use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RestoreCartItems
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        try {
            // Obtener el usuario que acaba de hacer login
            $user = $event->user;

            // Seleccionar la instancia del carrito de compras
            Cart::instance('shopping');

            // Guardar el carrito actual de la sesión (si existe) temporalmente
            $sessionCart = Cart::content();

            // Restaurar el carrito guardado del usuario
            Cart::restore($user->id);

            // Si había items en la sesión, los agregamos al carrito restaurado
            if ($sessionCart->isNotEmpty()) {
                foreach ($sessionCart as $item) {
                    // Verificar si el producto ya existe para evitar duplicados
                    $existingItem = Cart::search(function ($cartItem) use ($item) {
                        return $cartItem->id === $item->id;
                    });

                    if ($existingItem->isEmpty()) {
                        Cart::add([
                            'id' => $item->id,
                            'name' => $item->name,
                            'qty' => $item->qty,
                            'price' => $item->price,
                            'options' => $item->options->toArray()
                        ]);
                    }
                }
            }

            // Guardar el carrito actualizado
            Cart::store($user->id);

        } catch (\Exception $e) {
            // Log del error pero no interrumpir el login
            \Log::warning('Error al restaurar carrito del usuario: ' . $e->getMessage());
        }
    }
}
