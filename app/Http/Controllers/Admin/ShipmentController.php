<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\DeliveryDriver;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $status = $request->get('status');
        $driverId = $request->get('driver_id');

        $query = Shipment::with(['order.user', 'deliveryDriver'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('id', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('order.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('deliveryDriver', function ($driverQuery) use ($search) {
                        $driverQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Aplicar filtro de estado si existe
        if ($status) {
            $query->where('status', $status);
        }

        // Aplicar filtro de repartidor si existe
        if ($driverId) {
            $query->where('delivery_driver_id', $driverId);
        }

        $shipments = $query->paginate($perPage);

        // Mantener parámetros de búsqueda y paginación en los enlaces
        $shipments->appends($request->only(['search', 'status', 'driver_id', 'per_page']));

        // Obtener repartidores activos para el filtro
        $drivers = DeliveryDriver::active()->orderBy('name')->get();

        // Si es una petición AJAX, devolver solo el contenido
        if ($request->ajax()) {
            return view('admin.shipments.partials.shipments-content', compact('shipments', 'drivers'))->render();
        }

        return view('admin.shipments.index', compact('shipments', 'drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $orderId = $request->get('order_id');
        $order = null;

        if ($orderId) {
            $order = Order::with('user')->findOrFail($orderId);

            // Verificar que la orden no tenga ya un envío
            if ($order->hasShipment()) {
                return redirect()->route('admin.shipments.index')
                    ->with('error', 'Esta orden ya tiene un envío asignado');
            }
        }

        $drivers = DeliveryDriver::active()->orderBy('name')->get();
        $orders = Order::whereDoesntHave('shipment')
            ->whereIn('status', [2, 3]) // Pago verificado o preparando
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.shipments.create', compact('drivers', 'orders', 'order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_driver_id' => 'nullable|exists:delivery_drivers,id',
            'pickup_address' => 'required|array',
            'delivery_address' => 'required|array',
            'delivery_fee' => 'required|numeric|min:0',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_time_minutes' => 'nullable|integer|min:0'
        ]);

        $order = Order::findOrFail($request->order_id);

        // Verificar que la orden no tenga ya un envío
        if ($order->hasShipment()) {
            return redirect()->back()->withErrors(['order_id' => 'Esta orden ya tiene un envío asignado']);
        }

        $shipmentData = [
            'tracking_number' => Shipment::generateTrackingNumber(),
            'order_id' => $request->order_id,
            'pickup_address' => $request->pickup_address,
            'delivery_address' => $request->delivery_address,
            'delivery_fee' => $request->delivery_fee,
            'distance_km' => $request->distance_km,
            'estimated_time_minutes' => $request->estimated_time_minutes,
            'status' => 1 // Pendiente
        ];

        $shipment = Shipment::create($shipmentData);

        // Si se asignó un repartidor directamente
        if ($request->delivery_driver_id) {
            $driver = DeliveryDriver::findOrFail($request->delivery_driver_id);
            $shipment->assignDriver($driver);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Envío creado correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.shipments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        $shipment->load(['order.user', 'deliveryDriver']);
        return view('admin.shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        $drivers = DeliveryDriver::active()->orderBy('name')->get();
        $shipment->load(['order.user', 'deliveryDriver']);

        return view('admin.shipments.edit', compact('shipment', 'drivers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'delivery_driver_id' => 'nullable|exists:delivery_drivers,id',
            'status' => 'required|in:pending,picked_up,in_transit,out_for_delivery,delivered,failed,returned',
            'scheduled_pickup_date' => 'nullable|date',
            'scheduled_delivery_date' => 'nullable|date',
            'actual_pickup_date' => 'nullable|date',
            'actual_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        // Validaciones específicas según el estado
        if (in_array($request->status, ['picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned'])) {
            if (!$request->actual_pickup_date && !$shipment->actual_pickup_date) {
                return redirect()->back()->withErrors(['actual_pickup_date' => 'La fecha real de recogida es requerida para este estado.']);
            }
        }

        if (in_array($request->status, ['delivered', 'failed', 'returned'])) {
            if (!$request->actual_delivery_date && !$shipment->actual_delivery_date) {
                return redirect()->back()->withErrors(['actual_delivery_date' => 'La fecha real de entrega/finalización es requerida para este estado.']);
            }
        }

        $updateData = $request->only([
            'delivery_driver_id',
            'status',
            'scheduled_pickup_date',
            'scheduled_delivery_date',
            'actual_pickup_date',
            'actual_delivery_date',
            'notes'
        ]);

        // Auto-completar fechas según el estado
        if ($request->status == 'picked_up' && !$shipment->actual_pickup_date && !$request->actual_pickup_date) {
            $updateData['actual_pickup_date'] = now();
        }

        if (in_array($request->status, ['delivered', 'failed', 'returned']) && !$shipment->actual_delivery_date && !$request->actual_delivery_date) {
            $updateData['actual_delivery_date'] = now();
        }

        $shipment->update($updateData);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Envío actualizado correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.shipments.show', $shipment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()->route('admin.shipments.index')->with('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'El envío se ha eliminado correctamente.',
            'timeout' => 3000
        ]);
    }

    /**
     * Assign driver to shipment
     */
    public function assignDriver(Request $request, Shipment $shipment)
    {
        $request->validate([
            'delivery_driver_id' => 'required|exists:delivery_drivers,id'
        ]);

        $driver = DeliveryDriver::findOrFail($request->delivery_driver_id);
        $shipment->assignDriver($driver);

        return response()->json([
            'success' => true,
            'message' => 'Repartidor asignado correctamente'
        ]);
    }

    /**
     * Mark shipment as picked up
     */
    public function markPickedUp(Request $request, Shipment $shipment)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $shipment->markAsPickedUp($request->notes);

        return response()->json([
            'success' => true,
            'message' => 'Envío marcado como recogido'
        ]);
    }

    /**
     * Mark shipment as in transit
     */
    public function markInTransit(Request $request, Shipment $shipment)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $success = $shipment->markAsInTransit($request->notes);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Envío marcado como en tránsito'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo marcar el envío como en tránsito'
        ]);
    }

    /**
     * Mark shipment as delivered
     */
    public function markDelivered(Request $request, Shipment $shipment)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'proof' => 'nullable|array'
        ]);

        $shipment->markAsDelivered($request->proof, $request->notes);

        // Actualizar el estado de la orden asociada
        $shipment->order->update(['status' => \App\Enums\OrderStatus::ENTREGADO->value]);

        return response()->json([
            'success' => true,
            'message' => 'Envío marcado como entregado y orden actualizada'
        ]);
    }

    /**
     * Mark shipment as failed
     */
    public function markFailed(Request $request, Shipment $shipment)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $shipment->markAsFailed($request->reason);

        return response()->json([
            'success' => true,
            'message' => 'Envío marcado como fallido'
        ]);
    }

    /**
     * Update shipment status
     */
    public function updateStatus(Request $request, Shipment $shipment)
    {
        $request->validate([
            'status' => 'required|string|in:pending,assigned,in_transit,delivered,failed'
        ]);

        $oldStatus = $shipment->status;
        $newStatus = $request->status;

        // Actualizar el estado del envío
        $shipment->update(['status' => $newStatus]);

        // Si se marca como entregado, también actualizar la orden relacionada
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $shipment->order->update(['status' => 6]); // 6 = Entregado en órdenes
        }

        return response()->json([
            'success' => true,
            'message' => 'Estado del envío actualizado correctamente'
        ]);
    }

    /**
     * Update shipment location
     */
    public function updateLocation(Request $request, Shipment $shipment)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $shipment->updateLocation($request->latitude, $request->longitude);

        return response()->json([
            'success' => true,
            'message' => 'Ubicación actualizada'
        ]);
    }

    /**
     * Cancel a shipment
     */
    public function cancelShipment(Request $request, Shipment $shipment)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:255'
        ]);

        // Actualizar el estado del envío a cancelado
        $shipment->update([
            'status' => \App\Enums\ShipmentStatus::FAILED,
            'notes' => 'Cancelado: ' . $request->reason
        ]);

        // Actualizar el estado de la orden asociada
        $shipment->order->update(['status' => \App\Enums\OrderStatus::CANCELADO]);

        return response()->json([
            'success' => true,
            'message' => 'Envío cancelado correctamente'
        ]);
    }
}
