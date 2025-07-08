<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
        return view('admin.orders.show', compact('order', 'shippingAddress'));
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
            'status' => 'required|integer|in:1,2,3,4,5,6,7'
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
}
