<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentVerificationController extends Controller
{
    /**
     * Display payments for verification (including processed ones)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $method = $request->get('method');
        $status = $request->get('status', 'pending_verification'); // Default: pending

        $query = Payment::with(['user', 'order'])
            ->whereNotNull('receipt_path')
            ->orderBy('created_at', 'desc');

        // Filtrar por estado
        if ($status === 'all') {
            // Mostrar todos los pagos con comprobante
            $query->whereIn('status', ['pending_verification', 'approved', 'rejected']);
        } else {
            $query->where('status', $status);
        }

        // Filtrar por método de pago si se especifica
        if ($method) {
            $query->where('payment_method', $method);
        }

        $payments = $query->paginate($perPage);

        // Mantener parámetros en la paginación
        $payments->appends($request->only(['method', 'per_page', 'status']));

        // Si es una petición AJAX, devolver solo el contenido
        if ($request->ajax()) {
            return view('admin.payments.partials.verification-content', compact('payments'))->render();
        }

        return view('admin.payments.verification', compact('payments'));
    }

    /**
     * Verify payment (approve or reject)
     */
    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'reason' => 'required_if:status,rejected|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $status = $request->status;
            $reason = $request->reason;

            // Actualizar el estado del pago
            $payment->update([
                'status' => $status,
                'comments' => $status === 'rejected' ? $reason : $payment->comments,
                'verified_at' => now(),
                'verified_by' => auth()->id()
            ]);

            // Si el pago fue aprobado, buscar y actualizar órdenes relacionadas
            if ($status === 'approved') {
                $this->approveRelatedOrders($payment);
                $message = 'Pago aprobado correctamente. Las órdenes relacionadas han sido actualizadas.';
            } else {
                $this->rejectRelatedOrders($payment, $reason);
                $message = 'Pago rechazado. Las órdenes relacionadas han sido notificadas.';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la verificación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve related orders when payment is approved
     */
    private function approveRelatedOrders(Payment $payment)
    {
        // Para pagos que no tienen orden asociada, crear la orden
        if (!$payment->order_id && $payment->cart_data) {
            $this->createOrderFromPayment($payment);
        }

        // Si el pago ya tiene una orden asociada, actualizarla
        if ($payment->order_id) {
            $order = Order::find($payment->order_id);
            if ($order) {
                $order->update([
                    'status' => 2, // Pago verificado
                ]);
            }
        }

        // También buscar órdenes huérfanas que coincidan
        $orders = Order::where('user_id', $payment->user_id)
            ->where('total', $payment->amount)
            ->whereNull('payment_id')
            ->where('status', 1) // Estado pendiente
            ->get();

        foreach ($orders as $order) {
            $order->update([
                'status' => 2, // Pago verificado
                'payment_id' => $payment->id
            ]);
        }
    }

    /**
     * Create an order from payment data
     */
    private function createOrderFromPayment(Payment $payment)
    {
        if (!$payment->cart_data) {
            return;
        }

        // Calcular subtotal y shipping
        $cartData = $payment->cart_data;
        $subtotal = collect($cartData)->sum('subtotal');
        $shipping = 5.00; // Valor fijo por ahora

        // Dirección temporal
        $shippingAddress = [
            'address' => 'Dirección pendiente de confirmación',
            'city' => 'Pendiente',
            'province' => 'Pendiente',
            'phone' => $payment->user->phone ?? 'Pendiente',
            'notes' => 'Pago verificado - Pendiente dirección'
        ];

        // Determinar método de pago
        $paymentMethod = match ($payment->payment_method) {
            'bank_transfer' => 0,
            'qr_deuna' => 3,
            'cash_on_delivery' => 2,
            default => 0
        };

        $order = Order::create([
            'user_id' => $payment->user_id,
            'content' => $cartData,
            'shipping_address' => $shippingAddress,
            'payment_method' => $paymentMethod,
            'total' => $payment->amount,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'status' => 2, // Pago verificado
            'notes' => 'Orden creada desde pago verificado - ' . ucfirst($payment->payment_method)
        ]);

        // Asociar el pago con la orden
        $payment->update(['order_id' => $order->id]);
        $order->update(['payment_id' => $payment->id]);

        return $order;
    }

    /**
     * Handle rejected orders when payment is rejected
     */
    private function rejectRelatedOrders(Payment $payment, string $reason)
    {
        // Si el pago tiene una orden asociada, actualizar su estado
        if ($payment->order_id) {
            $order = Order::find($payment->order_id);
            if ($order) {
                $order->update([
                    'status' => 7, // Cancelado
                    'notes' => ($order->notes ? $order->notes . ' | ' : '') . 'Pago rechazado: ' . $reason
                ]);
            }
        }

        // Buscar órdenes relacionadas huérfanas
        $orders = Order::where('user_id', $payment->user_id)
            ->where('total', $payment->amount)
            ->where('status', 1) // Estado pendiente
            ->get();

        foreach ($orders as $order) {
            // Marcar como cancelado por rechazo de pago
            $order->update([
                'status' => 7, // Cancelado
                'notes' => ($order->notes ? $order->notes . ' | ' : '') . 'Pago rechazado: ' . $reason
            ]);

            // TODO: Enviar notificación al cliente sobre el rechazo
            // TODO: Permitir que el cliente suba un nuevo comprobante
            // TODO: Crear log de actividad
        }
    }

    /**
     * Get payment method IDs based on payment method string
     */
    private function getPaymentMethodIds(string $paymentMethod): array
    {
        return match ($paymentMethod) {
            'bank_transfer' => [0], // Transferencia
            'payphone' => [3], // QR PayPhone (asumiendo que 3 es QR)
            default => []
        };
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'pending_count' => Payment::where('status', 'pending')->whereNotNull('receipt_path')->count(),
            'approved_today' => Payment::where('status', 'approved')->whereDate('verified_at', today())->count(),
            'rejected_today' => Payment::where('status', 'rejected')->whereDate('verified_at', today())->count(),
            'total_amount_pending' => Payment::where('status', 'pending')->whereNotNull('receipt_path')->sum('amount')
        ];

        return response()->json($stats);
    }
}
