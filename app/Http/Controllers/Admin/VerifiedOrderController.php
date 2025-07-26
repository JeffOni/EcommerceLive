<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class VerifiedOrderController extends Controller
{
    /**
     * Display verified orders ready for shipping
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $status = $request->get('status');
        $paymentMethod = $request->get('payment_method');

        $query = Order::with(['user', 'shipment.deliveryDriver'])
            ->whereIn('status', [2, 3, 4]) // Pago verificado, preparando, listo para envío
            ->orderBy('created_at', 'desc');

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        // Aplicar filtro de estado si existe
        if ($status) {
            $query->where('status', $status);
        }

        // Aplicar filtro de método de pago si existe
        if ($paymentMethod !== null && $paymentMethod !== '') {
            $query->where('payment_method', $paymentMethod);
        }

        $orders = $query->paginate($perPage);

        // Mantener parámetros de búsqueda y paginación en los enlaces
        $orders->appends($request->only(['search', 'status', 'payment_method', 'per_page']));

        // Si es una petición AJAX, devolver solo el contenido
        if ($request->ajax()) {
            return view('admin.orders.partials.verified-orders-content', compact('orders'))->render();
        }

        return view('admin.orders.verified', compact('orders'));
    }

    /**
     * Get statistics for verified orders
     */
    public function getStats()
    {
        $stats = [
            'verified_count' => Order::where('status', 2)->count(), // Pago verificado
            'preparing_count' => Order::where('status', 3)->count(), // Preparando
            'ready_count' => Order::where('status', 4)->count(), // Listo para envío
            'total_value' => Order::whereIn('status', [2, 3, 4])->sum('total'),
            'pending_shipments' => Order::where('status', 4)->whereDoesntHave('shipment')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Mark orders as ready for shipping in bulk
     */
    public function bulkMarkReady(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $updated = Order::whereIn('id', $request->order_ids)
            ->whereIn('status', [2, 3]) // Solo órdenes verificadas o preparándose
            ->update(['status' => \App\Enums\OrderStatus::ASIGNADO]); // Listo para envío

        return response()->json([
            'success' => true,
            'message' => "Se marcaron {$updated} órdenes como listas para envío",
            'updated_count' => $updated
        ]);
    }

    /**
     * Get orders ready for shipment creation
     */
    public function readyForShipment(Request $request)
    {
        $orders = Order::with(['user', 'shipment'])
            ->where('status', 4) // Listo para envío
            ->whereDoesntHave('shipment') // Sin envío creado
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'orders' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'total' => $orders->total()
                ]
            ]);
        }

        return view('admin.orders.ready-for-shipment', compact('orders'));
    }
}
