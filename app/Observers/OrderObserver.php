<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use App\Services\OrderPdfService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    protected $orderPdfService;

    // Array estático para mantener los estados anteriores por ID de orden
    private static $previousStatuses = [];

    public function __construct(OrderPdfService $orderPdfService)
    {
        $this->orderPdfService = $orderPdfService;
    }

    /**
     * Handle the Order "updating" event.
     */
    public function updating(Order $order)
    {
        Log::info("OrderObserver::updating disparado para orden {$order->id}");

        // Guardar el estado anterior en el array estático
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            self::$previousStatuses[$order->id] = $oldStatus;
            Log::info("Status está cambiando de {$oldStatus} a {$order->status}");
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        // Obtener el estado anterior desde el array estático
        $oldStatus = self::$previousStatuses[$order->id] ?? null;

        Log::info("OrderObserver::updated disparado para orden {$order->id}", [
            'old_status' => $oldStatus,
            'new_status' => $order->status
        ]);

        // Verificar si el estado cambió usando la variable temporal del array estático
        if (
            $oldStatus !== null &&
            $oldStatus !== $order->status &&
            $order->user
        ) {
            // Enviar notificación al cliente
            $order->user->notify(new OrderStatusChanged(
                $order,
                $oldStatus,
                $order->status
            ));
            Log::info("Notificación enviada para orden {$order->id}");
        }

        // Si el estado cambió a "Pago Verificado" (2) y no tiene PDF, generarlo
        if ($order->status == 2 && (!$order->pdf_path || !$this->orderPdfService->pdfExists($order))) {
            try {
                $this->orderPdfService->generateOrderPdf($order);
                Log::info("PDF regenerado para la orden verificada {$order->id}");
            } catch (\Exception $e) {
                Log::error("Error regenerando PDF para la orden {$order->id}: " . $e->getMessage());
            }
        }

        // Reducir stock cuando la orden pasa a 'En Camino' (OrderStatus::ENVIADO o 5)
        if (
            $oldStatus !== null &&
            $oldStatus != 5 && // No venía de 'En Camino'
            $order->status == 5 // Ahora está 'En Camino'
        ) {
            Log::info("Condición de reducción de stock cumplida para orden {$order->id}: oldStatus={$oldStatus}, newStatus={$order->status}");

            // El contenido de la orden debe ser un array con los productos/variantes y cantidades
            $content = $order->content;
            Log::info("Contenido de orden {$order->id}:", ['content' => $content]);

            if (is_array($content)) {
                // Verificar si el contenido tiene estructura de carrito
                $cartItems = isset($content['cart']) ? $content['cart'] : $content;
                Log::info("Items del carrito para orden {$order->id}:", ['cart_items' => $cartItems]);

                foreach ($cartItems as $item) {
                    $productId = $item['product_id'] ?? $item['id'] ?? null;
                    $variantId = isset($item['options']['variant_id']) ? $item['options']['variant_id'] : ($item['variant_id'] ?? null);
                    $quantity = $item['quantity'] ?? $item['qty'] ?? 1;

                    Log::info("Procesando item de orden {$order->id}:", [
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'quantity' => $quantity,
                        'item_completo' => $item
                    ]);

                    if ($variantId) {
                        // Es una variante del producto
                        $variant = \App\Models\Variant::find($variantId);
                        if ($variant && $variant->stock >= $quantity) {
                            $oldStock = $variant->stock;
                            $variant->stock -= $quantity;
                            $variant->save();
                            Log::info("Stock de variante reducido: {$variant->sku} de {$oldStock} a {$variant->stock}");
                        } else {
                            Log::warning("No se pudo reducir stock de variante {$variantId}: stock insuficiente o no existe", [
                                'variant_exists' => $variant ? 'true' : 'false',
                                'current_stock' => $variant ? $variant->stock : 'N/A',
                                'requested_quantity' => $quantity
                            ]);
                        }
                    } elseif ($productId) {
                        // Es un producto simple
                        $product = \App\Models\Product::find($productId);
                        if ($product && $product->stock >= $quantity) {
                            $oldStock = $product->stock;
                            $product->stock -= $quantity;
                            $product->save();
                            Log::info("Stock de producto reducido: {$product->sku} de {$oldStock} a {$product->stock}");
                        } else {
                            Log::warning("No se pudo reducir stock de producto {$productId}: stock insuficiente o no existe", [
                                'product_exists' => $product ? 'true' : 'false',
                                'current_stock' => $product ? $product->stock : 'N/A',
                                'requested_quantity' => $quantity
                            ]);
                        }
                    }
                }
                Log::info("Stock reducido completamente para la orden {$order->id} al pasar a 'En Camino'.");
            } else {
                Log::warning("Contenido de orden {$order->id} no es un array válido");
            }
        } else {
            Log::info("Condición de reducción de stock NO cumplida para orden {$order->id}", [
                'has_old_status' => $oldStatus !== null,
                'old_status' => $oldStatus ?? 'N/A',
                'new_status' => $order->status,
                'old_not_5' => $oldStatus !== null ? ($oldStatus != 5) : 'N/A',
                'new_is_5' => ($order->status == 5)
            ]);
        }

        // Limpiar la variable temporal del array estático
        unset(self::$previousStatuses[$order->id]);
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order)
    {
        // Notificar al cliente sobre la creación del pedido
        if ($order->user) {
            $order->user->notify(new OrderStatusChanged(
                $order,
                null,
                $order->status
            ));
        }

        // Generar PDF automáticamente al crear la orden
        try {
            $this->orderPdfService->generateOrderPdf($order);
            Log::info("PDF generado automáticamente para la orden {$order->id}");
        } catch (\Exception $e) {
            Log::error("Error generando PDF para la orden {$order->id}: " . $e->getMessage());
        }
    }
}
