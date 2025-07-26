<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    /**
     * Subir comprobante de transferencia bancaria
     */
    public function uploadTransferReceipt(Request $request)
    {
        $request->validate([
            'receipt_file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // 5MB
            'comments' => 'nullable|string|max:500'
        ]);

        try {
            // Obtener la dirección por defecto del usuario (igual que en CheckoutController)
            $address = \App\Models\Address::where('user_id', auth()->id())
                ->where('default', true)
                ->with(['province', 'canton', 'parish'])
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes una dirección de envío configurada'
                ], 400);
            }

            $shippingAddress = [
                'address' => $address->address,
                'reference' => $address->reference,
                'province' => $address->province->name ?? '',
                'canton' => $address->canton->name ?? '',
                'parish' => $address->parish->name ?? '',
                'postal_code' => $address->postal_code,
                'notes' => $address->notes,
            ];

            // Obtener datos del carrito
            Cart::instance('shopping');
            $cartData = Cart::content()->map(function ($item) {
                return [
                    'id' => $item->id,                     // ID del producto
                    'product_id' => $item->id,              // Para el observer
                    'variant_id' => $item->options->variant_id ?? null, // Si es variante
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'subtotal' => $item->price * $item->qty,
                    'sku' => $item->options->sku ?? null,
                    'features' => $item->options->features ?? [],
                ];
            })->toArray();

            $subtotal = (float) Cart::total(2, '.', '');
            $shipping = 5.00;
            $total = $subtotal + $shipping;

            // Subir archivo
            $file = $request->file('receipt_file');
            $filename = 'transfer_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_receipts', $filename, 'public');

            // Crear orden primero
            $order = Order::create([
                'user_id' => auth()->id(),
                'content' => $cartData,
                'total' => $total,
                'status' => 1, // Pendiente
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
            ]);

            // Guardar pago asociado a la orden
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'payment_method' => 'bank_transfer',
                'amount' => $total,
                'status' => 'pending_verification',
                'receipt_path' => $path,
                'comments' => $request->comments,
                'cart_data' => $cartData,
            ]);

            // Limpiar el carrito
            Cart::destroy();

            // SOLUCIÓN: Forzar la actualización del contador del carrito
            // Disparar evento JavaScript para actualizar el contador en la navegación
            session()->flash('cartCleared', true);

            // Log para depuración
            \Log::info('PaymentController - Transferencia procesada:', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'shipping_address' => $shippingAddress,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => '¡Felicidades! Tu comprobante ha sido enviado exitosamente. Verificaremos tu pago en las próximas 24 horas y te notificaremos por email.',
                'payment_id' => $payment->id,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en transferencia bancaria:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al subir el comprobante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir comprobante de pago QR De Una
     */
    public function uploadQrReceipt(Request $request)
    {
        $request->validate([
            'receipt_file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // 5MB
            'transaction_number' => 'nullable|string|max:100'
        ]);

        try {
            // Obtener la dirección por defecto del usuario (igual que en CheckoutController)
            $address = \App\Models\Address::where('user_id', auth()->id())
                ->where('default', true)
                ->with(['province', 'canton', 'parish'])
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes una dirección de envío configurada'
                ], 400);
            }

            $shippingAddress = [
                'address' => $address->address,
                'reference' => $address->reference,
                'province' => $address->province->name ?? '',
                'canton' => $address->canton->name ?? '',
                'parish' => $address->parish->name ?? '',
                'postal_code' => $address->postal_code,
                'notes' => $address->notes,
            ];

            // Obtener datos del carrito
            Cart::instance('shopping');
            $cartData = Cart::content()->map(function ($item) {
                return [
                    'id' => $item->id,                     // ID del producto
                    'product_id' => $item->id,              // Para el observer
                    'variant_id' => $item->options->variant_id ?? null, // Si es variante
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'subtotal' => $item->price * $item->qty,
                    'sku' => $item->options->sku ?? null,
                    'features' => $item->options->features ?? [],
                ];
            })->toArray();

            $subtotal = (float) Cart::total(2, '.', '');
            $shipping = 5.00;
            $total = $subtotal + $shipping;

            // Subir archivo
            $file = $request->file('receipt_file');
            $filename = 'qr_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_receipts', $filename, 'public');

            // Crear orden primero
            $order = Order::create([
                'user_id' => auth()->id(),
                'content' => $cartData,
                'total' => $total,
                'status' => 1, // Pendiente
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
            ]);

            // Guardar pago asociado a la orden
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'payment_method' => 'qr_deuna',
                'amount' => $total,
                'status' => 'pending_verification',
                'receipt_path' => $path,
                'transaction_number' => $request->transaction_number,
                'cart_data' => $cartData,
            ]);

            // Limpiar el carrito
            Cart::destroy();

            // SOLUCIÓN: Forzar la actualización del contador del carrito
            session()->flash('cartCleared', true);

            // Log para depuración
            \Log::info('PaymentController - QR De Una procesado:', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'shipping_address' => $shippingAddress,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => '¡Excelente! Tu comprobante QR ha sido recibido. Verificaremos tu pago De Una en las próximas 24 horas y te confirmaremos por email.',
                'payment_id' => $payment->id,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en pago QR De Una:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al subir el comprobante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirmar pago en efectivo (contra entrega)
     */
    public function confirmCashPayment(Request $request)
    {
        try {
            // Obtener datos del carrito
            Cart::instance('shopping');

            if (Cart::count() == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'El carrito está vacío'
                ], 400);
            }

            // Obtener la dirección por defecto del usuario (igual que en CheckoutController)
            $address = \App\Models\Address::where('user_id', auth()->id())
                ->where('default', true)
                ->with(['province', 'canton', 'parish'])
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes una dirección de envío configurada'
                ], 400);
            }

            $shippingAddress = [
                'address' => $address->address,
                'reference' => $address->reference,
                'province' => $address->province->name ?? '',
                'canton' => $address->canton->name ?? '',
                'parish' => $address->parish->name ?? '',
                'postal_code' => $address->postal_code,
                'notes' => $address->notes,
            ];

            $cartData = Cart::content()->map(function ($item) {
                return [
                    'id' => $item->id,                     // ID del producto
                    'product_id' => $item->id,              // Para el observer
                    'variant_id' => $item->options->variant_id ?? null, // Si es variante
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'subtotal' => $item->price * $item->qty,
                    'sku' => $item->options->sku ?? null,
                    'features' => $item->options->features ?? [],
                ];
            })->toArray();

            $subtotal = (float) Cart::total(2, '.', '');
            $shipping = 5.00;
            $total = $subtotal + $shipping;

            // Log para depuración
            \Log::info('PaymentController - Dirección por defecto obtenida:', [
                'shipping_address' => $shippingAddress,
                'user_id' => auth()->id()
            ]);

            // Crear la orden primero
            $order = Order::create([
                'user_id' => auth()->id(),
                'content' => $cartData,
                'shipping_address' => $shippingAddress,
                'payment_method' => 2, // Pago en efectivo contra entrega
                'total' => $total,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'status' => 2, // Pago confirmado (para pago en efectivo)
                'notes' => 'Pago en efectivo contra entrega'
            ]);

            // Crear el registro de pago asociado a la orden
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'payment_method' => 'cash_on_delivery',
                'amount' => $total,
                'status' => 'confirmed',
                'cart_data' => $cartData,
            ]);

            // Actualizar la orden con el payment_id
            $order->update(['payment_id' => $payment->id]);

            // Log del resultado
            \Log::info('Orden creada exitosamente:', [
                'order_id' => $order->id,
                'shipping_address_saved' => $order->shipping_address
            ]);

            // Limpiar el carrito
            Cart::destroy();

            // SOLUCIÓN: Forzar la actualización del contador del carrito
            session()->flash('cartCleared', true);

            return response()->json([
                'success' => true,
                'message' => '¡Perfecto! Tu pedido ha sido confirmado. Pagarás $' . number_format($total, 2) . ' en efectivo cuando recibas tus productos en tu domicilio.',
                'payment_id' => $payment->id,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}
