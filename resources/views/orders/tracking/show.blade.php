<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Pedido #{{ $order->id }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Realizado el {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <a href="{{ route('orders.tracking.index') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a Mis Pedidos
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Información principal del pedido -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Estado actual y progreso -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">Estado del Pedido</h3>
                                    <p class="text-blue-100">{{ $order->status_text }}</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full px-4 py-2">
                                    <span class="text-2xl font-bold">{{ $progress }}%</span>
                                </div>
                            </div>

                            <!-- Barra de progreso -->
                            <div class="mt-4">
                                <div class="w-full bg-white bg-opacity-20 rounded-full h-3">
                                    <div class="bg-white h-3 rounded-full transition-all duration-1000 ease-in-out"
                                        style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Línea de tiempo del pedido -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Seguimiento del Pedido</h3>

                            <div class="relative">
                                @foreach ($timeline as $index => $event)
                                <div class="flex items-start mb-8 last:mb-0">
                                    <!-- Icono y línea -->
                                    <div class="flex flex-col items-center mr-4">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full
                                                @if ($event['status'] === 'completed') bg-green-100 text-green-600
                                                @elseif($event['status'] === 'current') bg-blue-100 text-blue-600 ring-4 ring-blue-50
                                                @elseif($event['status'] === 'cancelled') bg-red-100 text-red-600
                                                @else bg-gray-100 text-gray-400 @endif">
                                            <i class="{{ $event['icon'] }} text-sm"></i>
                                        </div>
                                        @if (!$loop->last)
                                        <div class="w-0.5 h-16 mt-2
                                                    @if ($event['status'] === 'completed') bg-green-200
                                                    @elseif($event['status'] === 'current') bg-blue-200
                                                    @elseif($event['status'] === 'cancelled') bg-red-200
                                                    @else bg-gray-200 @endif">
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Contenido del evento -->
                                    <div class="flex-1 pb-8">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $event['title'] }}</h4>
                                            @if ($event['date'])
                                            <span class="text-xs text-gray-500">
                                                {{ $event['date']->format('d/m/Y H:i') }}
                                            </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $event['description'] }}</p>

                                        @if ($event['status'] === 'current' && $event['title'] === 'En Camino')
                                        <div class="mt-3 bg-blue-50 rounded-lg p-3">
                                            <p class="text-sm text-blue-800">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Tu pedido está en camino. El repartidor se pondrá en contacto
                                                contigo.
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Productos del pedido -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Productos del Pedido</h3>

                            @if ($order->content && is_array($order->content))
                            <div class="space-y-4">
                                @foreach ($order->content as $item)
                                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-gray-400"></i>
                                    </div>

                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $item['name'] ?? 'Producto' }}</h4>
                                        <p class="text-sm text-gray-600">
                                            Cantidad: {{ $item['quantity'] ?? $item['qty'] ?? 1 }} ×
                                            ${{ number_format($item['price'] ?? 0, 2) }}
                                        </p>
                                        @if (!empty($item['sku']))
                                        <p class="text-xs text-gray-500">SKU: {{ $item['sku'] }}</p>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">
                                            ${{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? $item['qty']
                                            ?? 1), 2) }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500">No hay información de productos disponible</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar con información adicional -->
                <div class="space-y-6">
                    <!-- Información del envío -->
                    @if ($order->shipment)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Envío</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Número de seguimiento:</span>
                                    <span class="text-sm font-mono text-blue-600">{{ $order->shipment->tracking_number
                                        }}</span>
                                </div>

                                @if ($order->shipment->estimated_delivery_date)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Entrega estimada:</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $order->shipment->estimated_delivery_date->format('d/m/Y') }}
                                    </span>
                                </div>
                                @endif

                                @if ($order->shipment->deliveryDriver)
                                <div class="border-t pt-3 mt-3">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Repartidor Asignado</h4>
                                    <div class="bg-blue-50 rounded-lg p-3">
                                        <p class="text-sm font-medium text-blue-900">
                                            {{ $order->shipment->deliveryDriver->name }}</p>
                                        <p class="text-xs text-blue-600">
                                            {{ $order->shipment->deliveryDriver->phone }}</p>
                                        @if ($order->shipment->deliveryDriver->vehicle_info)
                                        <p class="text-xs text-blue-600 mt-1">
                                            {{ $order->shipment->deliveryDriver->vehicle_info }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Resumen del pedido -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen del Pedido</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Subtotal:</span>
                                    <span class="text-sm text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Envío:</span>
                                    <span class="text-sm text-gray-900">${{ number_format($order->shipping_cost ?? 0, 2)
                                        }}</span>
                                </div>

                                @if ($order->discount > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Descuento:</span>
                                    <span class="text-sm text-green-600">-${{ number_format($order->discount, 2)
                                        }}</span>
                                </div>
                                @endif

                                <div class="border-t pt-3">
                                    <div class="flex justify-between">
                                        <span class="text-base font-semibold text-gray-900">Total:</span>
                                        <span class="text-base font-semibold text-gray-900">${{
                                            number_format($order->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dirección de entrega -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dirección de Entrega</h3>

                            <div class="text-sm text-gray-600 space-y-1">
                                <p class="font-medium text-gray-900">
                                    {{ $order->shipping_address['receiver_full_name'] ??
                                    ($order->shipping_address['name'] ?? $order->user->name) }}
                                </p>
                                <p>{{ $order->shipping_address['address'] ?? ($order->shipping_address['street'] ??
                                    'Dirección no especificada') }}
                                </p>
                                <p>
                                    {{ $order->shipping_address['parish'] ?? ($order->shipping_address['city'] ?? 'N/A')
                                    }},
                                    {{ $order->shipping_address['canton'] ?? '' }}
                                    @if (isset($order->shipping_address['province']) ||
                                    isset($order->shipping_address['state']))
                                    ,
                                    {{ $order->shipping_address['province'] ?? $order->shipping_address['state'] }}
                                    @endif
                                </p>
                                @if (isset($order->shipping_address['postal_code']) &&
                                $order->shipping_address['postal_code'])
                                <p>CP: {{ $order->shipping_address['postal_code'] }}</p>
                                @elseif(isset($order->shipping_address['zip_code']) &&
                                $order->shipping_address['zip_code'])
                                <p>CP: {{ $order->shipping_address['zip_code'] }}</p>
                                @endif
                                @if ($order->shipping_address['receiver_phone'] ?? ($order->shipping_address['phone'] ??
                                null))
                                <p class="pt-2">
                                    <i class="fas fa-phone mr-1"></i>
                                    {{ $order->shipping_address['receiver_phone'] ?? $order->shipping_address['phone']
                                    }}
                                </p>
                                @endif
                                @if (isset($order->shipping_address['reference']) &&
                                $order->shipping_address['reference'])
                                <p class="text-gray-500 text-xs mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Ref: {{ $order->shipping_address['reference'] }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="space-y-3">
                        @if (($order->status instanceof \App\Enums\OrderStatus && $order->status->value >= 2) ||
                        (is_int($order->status) && $order->status >= 2))
                        <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
                            class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white text-center py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-200 block">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Descargar Factura
                        </a>
                        @endif

                        <button onclick="refreshOrderStatus()"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Actualizar Estado
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para actualización en tiempo real -->
    <script>
        function refreshOrderStatus() {
            const button = event.target;
            const originalText = button.innerHTML;

            // Mostrar loading
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualizando...';
            button.disabled = true;

            fetch(`{{ route('orders.tracking.status', $order->id) }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Recargar la página para mostrar los cambios
                        window.location.reload();
                    } else {
                        console.error('Error al actualizar el estado');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        // Auto-refresh cada 30 segundos si el pedido está en proceso
        @if ((($order->status instanceof \App\Enums\OrderStatus && $order->status->value >= 2) || (is_int($order->status) && $order->status >= 2)) && (($order->status instanceof \App\Enums\OrderStatus && $order->status->value < 6) || (is_int($order->status) && $order->status < 6)))
            setInterval(() => {
                fetch(`{{ route('orders.tracking.status', $order->id) }}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.progress !== {{ $progress }}) {
                            // Solo recargar si hay cambios
                            window.location.reload();
                        }
                    })
                    .catch(error => console.error('Error en auto-refresh:', error));
            }, 30000);
        @endif
    </script>
</x-app-layout>