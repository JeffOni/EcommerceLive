<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends Controller
{
    /**
     * Mostrar la página de seguimiento de pedidos del cliente
     */
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with(['payment', 'shipment.deliveryDriver'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.tracking.index', compact('orders'));
    }

    /**
     * Mostrar detalles de tracking de un pedido específico
     */
    public function show($id)
    {
        $user = Auth::user();

        $order = Order::with([
            'payment',
            'shipment.deliveryDriver',
            'orderDetails.product'
        ])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        // Calcular el progreso del pedido
        $progress = $this->calculateOrderProgress($order);

        // Obtener el historial de estados
        $timeline = $this->getOrderTimeline($order);

        return view('orders.tracking.show', compact('order', 'progress', 'timeline'));
    }

    /**
     * API para obtener el estado actual de un pedido (AJAX)
     */
    public function status($id)
    {
        $user = Auth::user();

        $order = Order::with(['payment', 'shipment.deliveryDriver'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $progress = $this->calculateOrderProgress($order);
        $timeline = $this->getOrderTimeline($order);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'status_text' => $order->status_text,
                'payment_status' => $order->payment?->status ?? 'pending',
                'shipment' => $order->shipment ? [
                    'tracking_number' => $order->shipment->tracking_number,
                    'estimated_delivery' => $order->shipment->estimated_delivery_date?->format('d/m/Y'),
                    'driver' => $order->shipment->deliveryDriver ? [
                        'name' => $order->shipment->deliveryDriver->name,
                        'phone' => $order->shipment->deliveryDriver->phone,
                        'vehicle' => $order->shipment->deliveryDriver->vehicle_info
                    ] : null
                ] : null
            ],
            'progress' => $progress,
            'timeline' => $timeline
        ]);
    }

    /**
     * Calcular el progreso del pedido en porcentaje
     */
    private function calculateOrderProgress($order)
    {
        $statusProgress = [
            1 => 10,  // Pendiente
            2 => 25,  // Pago Verificado
            3 => 50,  // Preparando
            4 => 70,  // Asignado
            5 => 90,  // En Camino
            6 => 100, // Entregado
            7 => 0    // Cancelado
        ];

        return $statusProgress[$order->status->value] ?? 0;
    }

    /**
     * Generar línea de tiempo del pedido
     */
    private function getOrderTimeline($order)
    {
        $timeline = [];

        // Pedido creado
        $timeline[] = [
            'title' => 'Pedido Realizado',
            'description' => 'Tu pedido ha sido registrado exitosamente',
            'date' => $order->created_at,
            'status' => 'completed',
            'icon' => 'fas fa-shopping-cart'
        ];

        // Pago verificado
        if ($order->payment && $order->payment->verified_at) {
            $timeline[] = [
                'title' => 'Pago Verificado',
                'description' => 'Tu pago ha sido verificado y confirmado',
                'date' => $order->payment->verified_at,
                'status' => 'completed',
                'icon' => 'fas fa-credit-card'
            ];
        } elseif ($order->status->value >= 2) {
            $timeline[] = [
                'title' => 'Pago Verificado',
                'description' => 'Tu pago ha sido verificado y confirmado',
                'date' => $order->updated_at,
                'status' => 'completed',
                'icon' => 'fas fa-credit-card'
            ];
        } else {
            $timeline[] = [
                'title' => 'Verificando Pago',
                'description' => 'Estamos verificando tu comprobante de pago',
                'date' => null,
                'status' => 'pending',
                'icon' => 'fas fa-clock'
            ];
        }

        // Preparando
        if ($order->status->value >= 3) {
            $timeline[] = [
                'title' => 'Preparando Pedido',
                'description' => 'Estamos preparando tu pedido para el envío',
                'date' => $order->updated_at,
                'status' => $order->status->value > 3 ? 'completed' : 'current',
                'icon' => 'fas fa-box'
            ];
        } elseif ($order->status->value >= 2) {
            $timeline[] = [
                'title' => 'Preparando Pedido',
                'description' => 'Próximamente comenzaremos a preparar tu pedido',
                'date' => null,
                'status' => 'pending',
                'icon' => 'fas fa-box'
            ];
        }

        // Asignado/En camino
        if ($order->shipment) {
            if ($order->status->value >= 4) {
                $timeline[] = [
                    'title' => 'Asignado a Repartidor',
                    'description' => "Repartidor: {$order->shipment->deliveryDriver->name}",
                    'date' => $order->shipment->created_at,
                    'status' => $order->status->value > 4 ? 'completed' : 'current',
                    'icon' => 'fas fa-user'
                ];
            }

            if ($order->status->value >= 5) {
                $timeline[] = [
                    'title' => 'En Camino',
                    'description' => 'Tu pedido está en camino hacia tu dirección',
                    'date' => $order->shipment->shipped_at ?? $order->updated_at,
                    'status' => $order->status->value > 5 ? 'completed' : 'current',
                    'icon' => 'fas fa-truck'
                ];
            }
        }

        // Entregado
        if ($order->status->value == 6) {
            $timeline[] = [
                'title' => 'Entregado',
                'description' => '¡Tu pedido ha sido entregado exitosamente!',
                'date' => $order->shipment?->delivered_at ?? $order->updated_at,
                'status' => 'completed',
                'icon' => 'fas fa-check-circle'
            ];
        }

        // Cancelado
        if ($order->status->value == 7) {
            $timeline[] = [
                'title' => 'Pedido Cancelado',
                'description' => 'El pedido ha sido cancelado',
                'date' => $order->updated_at,
                'status' => 'cancelled',
                'icon' => 'fas fa-times-circle'
            ];
        }

        return $timeline;
    }
}
