<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ShipmentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $shipmentService;

    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }
    //
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15); // Valor por defecto: 15
        $search = $request->get('search');
        $status = $request->get('status');

        $query = \App\Models\Order::with(['user'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhere('total', 'like', '%' . $search . '%')
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

        $orders = $query->paginate($perPage);

        // Mantener parámetros de búsqueda y paginación en los enlaces
        $orders->appends($request->only(['search', 'status', 'per_page']));

        // Si es una petición AJAX, devolver solo el contenido
        if ($request->ajax()) {
            return view('admin.orders.partials.orders-content', compact('orders'))->render();
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show(\App\Models\Order $order)
    {
        // Procesar y asegurar la estructura de la dirección de envío
        $shippingAddress = $this->formatShippingAddress($order->shipping_address);

        // Verificar si el envío es a otra persona
        $recipientInfo = null;

        // Verificar si hay información del receptor en los campos nuevos (receiver_*)
        if (!empty($order->shipping_address['receiver_name'])) {
            $recipientInfo = [
                'name' => trim(($order->shipping_address['receiver_name'] ?? '') . ' ' . ($order->shipping_address['receiver_last_name'] ?? '')),
                'document' => $order->shipping_address['receiver_document_number'] ?? 'No especificado',
                'phone' => $order->shipping_address['receiver_phone'] ?? 'No especificado',
                'email' => $order->shipping_address['receiver_email'] ?? null,
            ];
        }
        // Compatibilidad con campos antiguos (recipient_*)
        elseif (!empty($order->shipping_address['recipient_name'])) {
            $recipientInfo = [
                'name' => $order->shipping_address['recipient_name'],
                'document' => $order->shipping_address['recipient_document'] ?? 'No especificado',
                'phone' => $order->shipping_address['phone'] ?? 'No especificado',
                'email' => null,
            ];
        }

        return view('admin.orders.show', compact('order', 'shippingAddress', 'recipientInfo'));
    }

    /**
     * Asegura y formatea la dirección de envío para la vista admin.
     */
    private function formatShippingAddress($address)
    {
        // Estructura base esperada
        $defaults = [
            'recipient_name' => null,
            'recipient_document' => null,
            'phone' => null,
            'address' => null,
            'parish' => null,
            'canton' => null,
            'province' => null,
            'postal_code' => null,
            'reference' => null,
            'type' => null,
            'full_address' => null,
        ];
        $address = is_array($address) ? $address : [];
        $result = array_merge($defaults, $address);

        // Si la dirección específica está vacía pero hay provincia/cantón/parroquia, usar esos datos
        if (empty($result['address'])) {
            $addressParts = array_filter([
                $result['parish'],
                $result['canton'],
                $result['province']
            ]);
            if (!empty($addressParts)) {
                $result['address'] = implode(', ', $addressParts);
            } else {
                $result['address'] = 'No especificada';
            }
        }

        // Generar full_address si no existe pero hay datos suficientes
        if (empty($result['full_address'])) {
            $parts = array_filter([
                $result['address'],
                $result['parish'],
                $result['canton'],
                $result['province'],
                $result['postal_code'] ? 'CP: ' . $result['postal_code'] : null
            ]);
            $result['full_address'] = implode(', ', $parts);
        }

        // Normalizar valores vacíos
        foreach ($result as $k => $v) {
            if ($v === '' || $v === null) {
                $result[$k] = null;
            }
        }

        return $result;
    }

    public function updateStatus(\App\Models\Order $order, Request $request)
    {
        $request->validate([
            'status' => 'required|integer|in:1,2,3,4,5,6,7,8'
        ]);

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    }

    public function downloadPDF(\App\Models\Order $order)
    {
        if (!$order->pdf_path || !file_exists(storage_path('app/' . $order->pdf_path))) {
            return redirect()->back()->with('swal', [
                'title' => 'Error',
                'text' => 'El PDF no está disponible.',
                'icon' => 'error'
            ]);
        }

        return response()->download(storage_path('app/' . $order->pdf_path));
    }

    /**
     * Verificar si la orden tiene un envío
     */
    public function checkShipment(\App\Models\Order $order)
    {
        return response()->json([
            'hasShipment' => $order->hasShipment(),
            'shipment' => $order->hasShipment() ? $order->shipment : null
        ]);
    }

    /**
     * Asignar repartidor a una orden - FLUJO COMPLETO DESDE CERO
     */
    public function assignDriver(Request $request, \App\Models\Order $order)
    {
        $request->validate([
            'delivery_driver_id' => 'required|exists:delivery_drivers,id'
        ]);

        try {
            \Log::info("Iniciando asignación de repartidor para orden #{$order->getKey()}");

            $driver = \App\Models\DeliveryDriver::findOrFail($request->input('delivery_driver_id'));
            \Log::info("Repartidor seleccionado: {$driver->getAttribute('name')} (ID: {$driver->getKey()})");

            // PASO 1: Verificar que la orden esté en estado válido
            $validStates = [1, 2, 3]; // PENDIENTE, PAGADO, PREPARANDO
            if (!in_array($order->status, $validStates)) {
                \Log::warning("Orden #{$order->getKey()} no está en estado válido: {$order->status}");
                return response()->json([
                    'success' => false,
                    'message' => 'Esta orden no puede tener un repartidor asignado en su estado actual.'
                ]);
            }

            // PASO 2: Verificar límite de repartidor (máximo 5 envíos activos)
            $activeShipments = \App\Models\Shipment::where('delivery_driver_id', $driver->getKey())
                ->whereIn('status', [1, 2, 4]) // PENDING, ASSIGNED, IN_TRANSIT
                ->count();

            \Log::info("Repartidor {$driver->getAttribute('name')} tiene {$activeShipments} envíos activos");

            if ($activeShipments >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'El repartidor ya tiene 5 envíos activos. No puede recibir más asignaciones.'
                ]);
            }

            // PASO 3: Eliminar cualquier envío existente sin repartidor
            if ($order->hasShipment()) {
                $existingShipment = $order->shipment()->first();
                if (!$existingShipment->getAttribute('delivery_driver_id')) {
                    \Log::info("Eliminando envío sin repartidor: #{$existingShipment->getKey()}");
                    $existingShipment->delete();
                }
            }

            // PASO 4: Crear nuevo envío CON repartidor asignado desde el inicio
            $trackingNumber = 'SHP-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

            $shipment = \App\Models\Shipment::create([
                'tracking_number' => $trackingNumber,
                'order_id' => $order->getKey(),
                'status' => \App\Enums\ShipmentStatus::ASSIGNED->value,
                'pickup_address' => json_encode([
                    'address' => 'Tienda Principal',
                    'city' => 'Santa Cruz',
                    'province' => 'Santa Cruz'
                ]),
                'delivery_address' => $order->shipping_address,
                'delivery_driver_id' => $driver->getKey(),
                'assigned_at' => now()
            ]);

            \Log::info("Envío creado: #{$shipment->getKey()} con repartidor asignado");

            // PASO 5: Actualizar estado de la orden a ASIGNADO
            $order->update(['status' => \App\Enums\OrderStatus::ASIGNADO->value]);
            \Log::info("Orden #{$order->getKey()} actualizada a estado ASIGNADO");

            return response()->json([
                'success' => true,
                'message' => "Repartidor {$driver->getAttribute('name')} asignado correctamente a la orden #{$order->getKey()}"
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en asignación completa: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al asignar el repartidor: ' . $e->getMessage()
            ]);
        }
    }    /**
         * Cambiar orden a estado "En Camino" y crear/actualizar envío
         */
    public function markAsInTransit(Request $request, \App\Models\Order $order)
    {
        try {
            // Verificar que la orden esté en estado ASIGNADO (4)
            if ($order->status !== 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'La orden debe estar asignada a un repartidor para marcarla como "En Camino".'
                ]);
            }

            // Cambiar estado de la orden a ENVIADO (5)
            $order->update(['status' => \App\Enums\OrderStatus::ENVIADO->value]);

            // Si tiene envío, cambiar su estado a IN_TRANSIT
            if ($order->hasShipment()) {
                $shipment = $order->shipment()->first();
                $shipment->changeStatus(\App\Enums\ShipmentStatus::IN_TRANSIT->value);
            }

            return response()->json([
                'success' => true,
                'message' => "Orden #{$order->getKey()} marcada como 'En Camino'."
            ]);

        } catch (\Exception $e) {
            \Log::error('Error marcando orden como en camino: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al cambiar el estado. Inténtalo de nuevo.'
            ]);
        }
    }
}
