<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Services\ShipmentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    protected $shipmentService;

    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    public function index()
    {
        // Configurar la instancia del carrito
        Cart::instance('shopping');

        // Verificar que hay items en el carrito
        if (Cart::count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        // Obtener la dirección por defecto del usuario
        $defaultAddress = null;
        if (auth()->check()) {
            $defaultAddress = Address::where('user_id', auth()->id())
                ->where('default', true)
                ->with(['province', 'canton', 'parish'])
                ->first();
        }

        // Obtener los items del carrito
        $cartItems = Cart::content()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'quantity' => $item->qty,
                'subtotal' => (float) ($item->price * $item->qty),
                'rowId' => $item->rowId,
                'options' => $item->options->toArray() ?? []
            ];
        })->values()->toArray();

        // Calcular totales
        $subtotal = (float) Cart::subtotal(2, '.', '');
        $tax = (float) Cart::tax(2, '.', '');
        $total = (float) Cart::total(2, '.', '');

        // Costo de envío
        $shipping = 5.00;
        $totalWithShipping = $total + $shipping;

        // Pasar los datos a la vista incluyendo la dirección por defecto
        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'total',
            'shipping',
            'totalWithShipping',
            'defaultAddress'
        ));
    }

    /**
     * Mostrar página de agradecimiento después del pago
     */
    public function thankYou(Request $request)
    {
        $orderId = $request->get('order');
        $order = null;

        if ($orderId) {
            $order = Order::where('id', $orderId)
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('checkout.thank-you', compact('order'));
    }

    /**
     * Descargar factura PDF de una orden
     */
    public function downloadInvoice(Order $order)
    {
        // Verificar que el usuario puede acceder a esta orden
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Generar el PDF de la factura
        $pdf = Pdf::loadView('orders.invoice', compact('order'));

        return $pdf->download("factura-pedido-{$order->id}.pdf");
    }

    /**
     * Crear una nueva orden (centralizado para todos los métodos de pago)
     */
    public function store(Request $request)
    {
        // Validar método de pago y tipo de entrega
        $request->validate([
            'payment_method' => 'required|in:2,3,4', // Solo métodos permitidos actualmente
            'delivery_type' => 'required|in:delivery,pickup',
        ], [
            'payment_method.required' => 'Debes seleccionar un método de pago.',
            'payment_method.in' => 'El método de pago seleccionado no es válido.',
            'delivery_type.required' => 'Debes seleccionar un tipo de entrega.',
            'delivery_type.in' => 'El tipo de entrega seleccionado no es válido.',
        ]);

        try {
            // Usar el método centralizado para crear la orden
            $order = $this->createOrderFromCart((int) $request->payment_method, $request->delivery_type);

            if (!$order) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al crear la orden. Verifica que tengas una dirección de envío configurada y que tu carrito no esté vacío.'
                    ], 400);
                }
                return redirect()->route('checkout.index')
                    ->with('error', 'Error al crear el pedido. Verifica que tengas una dirección de envío configurada.');
            }

            Cart::destroy();

            if ($request->expectsJson()) {
                $message = match ((int) $request->payment_method) {
                    2 => 'Tu pedido ha sido creado. Por favor, realiza la transferencia bancaria y sube el comprobante.',
                    3 => '¡Perfecto! Tu pedido ha sido confirmado. Pagarás $' . number_format((float) $order->total, 2) . ' en efectivo cuando recibas tus productos.',
                    4 => 'Tu pedido ha sido creado. Por favor, realiza el pago con QR y sube el comprobante.',
                    default => 'Tu pedido ha sido creado exitosamente.'
                };

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'order_id' => $order->id,
                    'redirect_url' => route('checkout.thank-you', ['order' => $order->id])
                ]);
            }

            return redirect()->route('checkout.thank-you', ['order' => $order->id])
                ->with('success', 'Tu pedido ha sido creado exitosamente.');

        } catch (\Exception $e) {
            \Log::error('Error al crear la orden:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el pedido: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('checkout.index')
                ->with('error', 'Error al crear el pedido. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Manejar pago con transferencia bancaria (con comprobante)
     */
    public function storeTransferPayment(Request $request)
    {
        \Log::info('Iniciando storeTransferPayment para usuario: ' . auth()->id());

        // Verificar que el carrito no esté vacío ANTES de hacer cualquier cosa
        Cart::instance('shopping');
        if (Cart::count() == 0) {
            \Log::error('Intento de procesamiento con carrito vacío para usuario: ' . auth()->id());
            return response()->json([
                'success' => false,
                'message' => 'Tu carrito está vacío. Por favor, agrega productos antes de continuar.'
            ], 400);
        }

        $request->validate([
            'receipt_file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // 5MB
            'comments' => 'nullable|string|max:500'
        ]);

        try {
            \Log::info('Validación pasada, creando orden desde carrito...');

            // Crear la orden primero
            $order = $this->createOrderFromCart(2); // Método de pago: transferencia

            if (!$order) {
                \Log::error('No se pudo crear la orden desde el carrito');
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la orden. Verifica que tengas productos en el carrito y una dirección por defecto.'
                ], 500);
            }

            \Log::info('Orden creada exitosamente: ' . $order->id);

            // Guardar el archivo de comprobante
            \Log::info('Guardando archivo de comprobante...');
            $file = $request->file('receipt_file');
            $filename = 'transfer_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_receipts', $filename, 'public');
            \Log::info('Archivo guardado en: ' . $path);

            // Crear el registro de pago asociado
            \Log::info('Creando registro de pago...');
            $payment = \App\Models\Payment::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'payment_method' => 'bank_transfer',
                'amount' => $order->total,
                'status' => 'pending_verification',
                'receipt_path' => $path,
                'comments' => $request->comments,
            ]);
            \Log::info('Pago creado exitosamente: ' . $payment->id);

            // Actualizar la orden con el payment_id
            $order->update(['payment_id' => $payment->id]);
            \Log::info('Orden actualizada con payment_id: ' . $payment->id);

            // IMPORTANTE: Limpiar carrito SOLO después de todo exitoso
            Cart::destroy();
            \Log::info('Carrito limpiado exitosamente');

            return response()->json([
                'success' => true,
                'message' => '¡Felicidades! Tu comprobante ha sido enviado exitosamente. Verificaremos tu pago en las próximas 24 horas y te notificaremos por email.',
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'redirect_url' => route('checkout.thank-you', ['order' => $order->id])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en transferencia bancaria:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la transferencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manejar pago con QR (con comprobante)
     */
    public function storeQrPayment(Request $request)
    {
        \Log::info('Iniciando storeQrPayment para usuario: ' . auth()->id());

        // Verificar que el carrito no esté vacío ANTES de hacer cualquier cosa
        Cart::instance('shopping');
        if (Cart::count() == 0) {
            \Log::error('Intento de procesamiento QR con carrito vacío para usuario: ' . auth()->id());
            return response()->json([
                'success' => false,
                'message' => 'Tu carrito está vacío. Por favor, agrega productos antes de continuar.'
            ], 400);
        }

        $request->validate([
            'receipt_file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // 5MB
            'transaction_number' => 'nullable|string|max:100'
        ]);

        try {
            // Crear la orden primero
            $order = $this->createOrderFromCart(4); // Método de pago: QR

            if (!$order) {
                \Log::error('No se pudo crear la orden desde el carrito para QR');
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la orden. Verifica que tengas productos en el carrito y una dirección por defecto.'
                ], 500);
            }

            \Log::info('Orden QR creada exitosamente: ' . $order->id);

            // Guardar el archivo de comprobante
            $file = $request->file('receipt_file');
            $filename = 'qr_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_receipts', $filename, 'public');

            // Crear el registro de pago asociado
            $payment = \App\Models\Payment::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'payment_method' => 'qr_de_una',
                'amount' => $order->total,
                'status' => 'pending_verification',
                'receipt_path' => $path,
                'transaction_number' => $request->transaction_number,
            ]);

            // Actualizar la orden con el payment_id
            $order->update(['payment_id' => $payment->id]);

            // IMPORTANTE: Limpiar carrito SOLO después de todo exitoso
            Cart::destroy();
            \Log::info('Carrito QR limpiado exitosamente');

            return response()->json([
                'success' => true,
                'message' => '¡Perfecto! Tu comprobante de pago QR ha sido recibido. Verificaremos tu pago y te notificaremos por email.',
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'redirect_url' => route('checkout.thank-you', ['order' => $order->id])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en pago QR:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago QR: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método privado para crear orden desde el carrito (centralizado)
     */
    private function createOrderFromCart(int $paymentMethod, string $deliveryType = 'delivery'): ?\App\Models\Order
    {
        Cart::instance('shopping');

        \Log::info('Verificando carrito - Count: ' . Cart::count());
        if (Cart::count() == 0) {
            \Log::error('El carrito está vacío al intentar crear orden');
            return null;
        }

        // Solo verificar dirección si es envío a domicilio
        $address = null;
        if ($deliveryType === 'delivery') {
            // Obtener la dirección por defecto del usuario
            \Log::info('Buscando dirección por defecto para usuario: ' . auth()->id());
            $address = \App\Models\Address::where('user_id', auth()->id())
                ->where('default', true)
                ->with(['province', 'canton', 'parish'])
                ->first();

            if (!$address) {
                \Log::error('No se encontró dirección por defecto para el usuario: ' . auth()->id());
                return null;
            }
        }

        \Log::info('Dirección encontrada - ID: ' . $address->id);

        // Calcular costos según tipo de entrega
        $subtotal = (float) Cart::subtotal(2, '.', '');
        $tax = (float) Cart::tax(2, '.', '');
        $total = (float) Cart::total(2, '.', '');

        // Shipping depende del tipo de entrega
        $shipping = $deliveryType === 'pickup' ? 0.00 : 5.00;
        $totalWithShipping = $total + $shipping;

        // Dirección de envío (solo para delivery)
        $shipping_address = null;
        if ($deliveryType === 'delivery' && $address) {
            $shipping_address = [
                'address' => $address->address,
                'reference' => $address->reference,
                'province' => $address->province->name ?? '',
                'canton' => $address->canton->name ?? '',
                'parish' => $address->parish->name ?? '',
                'postal_code' => $address->postal_code,
                'notes' => $address->notes,
                'receiver_type' => $address->receiver,
                'receiver_name' => $address->receiver_name,
                'receiver_last_name' => $address->receiver_last_name,
                'receiver_full_name' => $address->receiver_full_name,
                'receiver_phone' => $address->receiver_phone,
                'receiver_email' => $address->receiver_email,
                'receiver_document_type' => $address->receiver_document_type,
                'receiver_document_number' => $address->receiver_document_number,
            ];
        }

        $cartContent = Cart::content()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'quantity' => $item->qty,
                'subtotal' => (float) ($item->price * $item->qty),
                'rowId' => $item->rowId,
                'options' => $item->options->toArray() ?? []
            ];
        })->values()->toArray();

        \Log::info('Creando orden con datos:', [
            'user_id' => auth()->id(),
            'payment_method' => $paymentMethod,
            'total' => $totalWithShipping,
            'items_count' => count($cartContent)
        ]);

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'status' => 1,
            'payment_method' => $paymentMethod,
            'delivery_type' => $deliveryType,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $totalWithShipping,
            'content' => $cartContent,
            'shipping_address' => $shipping_address,
        ]);

        \Log::info('Orden creada exitosamente con ID: ' . $order->id);

        // Crear envío automáticamente solo para órdenes con delivery
        if ($deliveryType === 'delivery') {
            try {
                $this->shipmentService->createShipmentForOrder($order);
                \Log::info('Envío creado para orden: ' . $order->id);
            } catch (\Exception $e) {
                // Log el error pero no falla la orden
                \Log::error('Error creando envío para orden ' . $order->id . ': ' . $e->getMessage());
            }
        } else {
            \Log::info('Orden de retiro en tienda, no se crea envío: ' . $order->id);
        }

        return $order;
    }
}
