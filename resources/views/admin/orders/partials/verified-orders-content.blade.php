@if ($orders->count() > 0)
<div class="p-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach ($orders as $order)
        <div class="order-card p-6 rounded-xl">
            <!-- Header de la orden -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-900">Orden #{{ $order->id }}</h3>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span
                    class="status-badge {{ $order->status == 2 ? 'status-verificado' : ($order->status == 3 ? 'status-preparando' : 'status-listo') }}">
                    {{ $order->status_label }}
                </span>
            </div>

            <!-- Información del cliente -->
            <div class="mb-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-user text-gray-400 mr-2"></i>
                    <span class="font-medium text-gray-900">{{ $order->user->name }}</span>
                </div>
                <div class="flex items-center mb-2">
                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                    <span class="text-sm text-gray-600">{{ $order->user->email }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                    <span class="text-sm text-gray-600">
                        {{ $order->shipping_address['canton'] ?? ($order->shipping_address['city'] ?? 'N/A') }},
                        {{ $order->shipping_address['province'] ?? ($order->shipping_address['state'] ?? 'N/A') }}
                    </span>
                </div>
            </div>

            <!-- Total y método de pago -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <span class="text-2xl font-bold text-green-600">${{ number_format($order->total, 2) }}</span>
                </div>
                <span class="payment-method-badge {{ $order->payment_method == 0
                                ? 'method-transferencia'
                                : ($order->payment_method == 1
                                    ? 'method-tarjeta'
                                    : 'method-efectivo') }}">
                    {{ $order->payment_method_label }}
                </span>
            </div>

            <!-- Productos -->
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-box mr-1"></i>
                    {{ count($order->content ?? []) }} producto(s)
                </p>
                @if (isset($order->content) && is_array($order->content))
                <div class="space-y-1">
                    @foreach (array_slice($order->content, 0, 2) as $item)
                    <p class="text-xs text-gray-500">
                        {{ $item['name'] ?? 'Producto' }}
                        @if (isset($item['qty']))
                        (x{{ $item['qty'] }})
                        @endif
                    </p>
                    @endforeach
                    @if (count($order->content) > 2)
                    <p class="text-xs text-gray-400">
                        +{{ count($order->content) - 2 }} producto(s) más
                    </p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Acciones según el estado -->
            <div class="flex flex-wrap gap-2">
                @if ($order->status == 2)
                <!-- Pago Verificado -->
                <button onclick="updateOrderStatus({{ $order->id }}, 3)" class="action-btn btn-prepare flex-1">
                    <i class="fas fa-cogs mr-1"></i> Preparar
                </button>
                <button onclick="updateOrderStatus({{ $order->id }}, 4)" class="action-btn btn-ready flex-1">
                    <i class="fas fa-check mr-1"></i> Listo
                </button>
                @elseif($order->status == 3)
                <!-- Preparando -->
                <button onclick="updateOrderStatus({{ $order->id }}, 4)" class="action-btn btn-ready flex-1">
                    <i class="fas fa-check mr-1"></i> Listo para Envío
                </button>
                @elseif($order->status == 4)
                <!-- Listo para Envío -->
                @if (!$order->hasShipment())
                <button onclick="createShipment({{ $order->id }})" class="action-btn btn-ship flex-1">
                    <i class="fas fa-shipping-fast mr-1"></i> Crear Envío
                </button>
                @else
                <a href="{{ route('admin.shipments.show', $order->shipment) }}"
                    class="action-btn btn-view flex-1 text-center">
                    <i class="fas fa-eye mr-1"></i> Ver Envío
                </a>
                @endif
                @endif

                <!-- Acciones comunes -->
                <button onclick="downloadPDF({{ $order->id }})" class="action-btn btn-view">
                    <i class="fas fa-download"></i>
                </button>

                <a href="{{ route('admin.orders.show', $order) }}" class="action-btn btn-view">
                    <i class="fas fa-eye"></i>
                </a>

                @if ($order->status != 7)
                <button onclick="updateOrderStatus({{ $order->id }}, 7)" class="action-btn btn-cancel">
                    <i class="fas fa-times"></i>
                </button>
                @endif
            </div>

            <!-- Estado del envío si existe -->
            @if ($order->hasShipment())
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-shipping-fast mr-1"></i>
                        Envío: {{ $order->shipment->tracking_number }}
                    </span>
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">
                        {{ ucfirst(str_replace('_', ' ', $order->shipment->status->value)) }}
                    </span>
                </div>
                @if ($order->shipment->deliveryDriver)
                <p class="text-xs text-gray-500 mt-1">
                    Repartidor: {{ $order->shipment->deliveryDriver->name }}
                </p>
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="flex justify-center mt-8">
        {{ $orders->links() }}
    </div>
</div>
@else
<div class="text-center py-16">
    <div class="mx-auto w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-4">
        <i class="fas fa-clipboard-list text-3xl text-blue-600"></i>
    </div>
    <h4 class="text-xl font-semibold text-gray-900 mb-2">No hay órdenes verificadas</h4>
    <p class="text-gray-600 mb-6">Las órdenes aparecerán aquí una vez que los pagos sean verificados</p>
    <div class="flex justify-center space-x-4">
        <a href="{{ route('admin.payments.verification') }}"
            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
            <i class="fas fa-receipt mr-2"></i> Verificar Pagos
        </a>
        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-shopping-cart mr-2"></i> Ver Todas las Órdenes
        </a>
    </div>
</div>
@endif