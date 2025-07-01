<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Payment;

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
            // Obtener datos del carrito
            Cart::instance('shopping');
            $cartData = Cart::content()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'subtotal' => $item->price * $item->qty,
                ];
            })->toArray();

            $total = (float) Cart::total(2, '.', '') + 5.00; // +shipping

            // Subir archivo
            $file = $request->file('receipt_file');
            $filename = 'transfer_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_receipts', $filename, 'public');

            // Guardar en base de datos
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'payment_method' => 'bank_transfer',
                'amount' => $total,
                'status' => 'pending_verification',
                'receipt_path' => $path,
                'comments' => $request->comments,
                'cart_data' => $cartData,
            ]);

            // Limpiar el carrito
            Cart::destroy();

            return response()->json([
                'success' => true,
                'message' => '¡Felicidades! Tu comprobante ha sido enviado exitosamente. Verificaremos tu pago en las próximas 24 horas y te notificaremos por email.',
                'payment_id' => $payment->id
            ]);

        } catch (\Exception $e) {
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
            // Obtener datos del carrito
            Cart::instance('shopping');
            $cartData = Cart::content()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'subtotal' => $item->price * $item->qty,
                ];
            })->toArray();

            $total = (float) Cart::total(2, '.', '') + 5.00; // +shipping

            // Subir archivo
            $file = $request->file('receipt_file');
            $filename = 'qr_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_receipts', $filename, 'public');

            // Guardar en base de datos
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'payment_method' => 'qr_deuna',
                'amount' => $total,
                'status' => 'pending_verification',
                'receipt_path' => $path,
                'transaction_number' => $request->transaction_number,
                'cart_data' => $cartData,
            ]);

            // Limpiar el carrito
            Cart::destroy();

            return response()->json([
                'success' => true,
                'message' => '¡Excelente! Tu comprobante QR ha sido recibido. Verificaremos tu pago De Una en las próximas 24 horas y te confirmaremos por email.',
                'payment_id' => $payment->id
            ]);

        } catch (\Exception $e) {
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
            $cartData = Cart::content()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'subtotal' => $item->price * $item->qty,
                ];
            })->toArray();

            $total = (float) Cart::total(2, '.', '') + 5.00; // +shipping

            // Guardar registro de pago
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'payment_method' => 'cash_on_delivery',
                'amount' => $total,
                'status' => 'confirmed',
                'cart_data' => $cartData,
            ]);

            // Limpiar el carrito
            Cart::destroy();

            return response()->json([
                'success' => true,
                'message' => '¡Perfecto! Tu pedido ha sido confirmado. Pagarás $' . number_format($total, 2) . ' en efectivo cuando recibas tus productos en tu domicilio.',
                'payment_id' => $payment->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}
