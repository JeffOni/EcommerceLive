<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Pedidos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header con estadísticas -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Mis Pedidos</h3>
                            <p class="text-blue-100">Consulta el estado de todos tus pedidos</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                            <span class="text-2xl font-bold">{{ $orders->total() }}</span>
                            <p class="text-sm text-blue-100">Total de pedidos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de pedidos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($orders as $order)
                    <div
                        class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                        <!-- Header del pedido -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        Pedido #{{ $order->id }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($order->status->value == 6) bg-green-100 text-green-800
                                    @elseif($order->status->value == 7) bg-red-100 text-red-800
                                    @elseif($order->status->value >= 4) bg-blue-100 text-blue-800
                                    @elseif($order->status->value >= 2) bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $order->status_text }}
                                </span>
                            </div>
                        </div>

                        <!-- Contenido del pedido -->
                        <div class="p-6">
                            <!-- Información básica -->
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total:</span>
                                    <span
                                        class="font-semibold text-gray-900">${{ number_format($order->total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Método de pago:</span>
                                    <span class="text-gray-900">{{ $order->payment_method_text }}</span>
                                </div>
                                @if ($order->shipment)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tracking:</span>
                                        <span
                                            class="font-mono text-blue-600">{{ $order->shipment->tracking_number }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Barra de progreso -->
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-600 mb-1">
                                    <span>Progreso</span>
                                    <span>{{ $this->calculateProgress($order) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ $this->calculateProgress($order) }}%"></div>
                                </div>
                            </div>

                            <!-- Información del repartidor -->
                            @if ($order->shipment && $order->shipment->deliveryDriver && $order->status->value >= 4)
                                <div class="bg-blue-50 rounded-lg p-3 mb-4">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-blue-900">
                                                {{ $order->shipment->deliveryDriver->name }}
                                            </p>
                                            <p class="text-xs text-blue-600">
                                                {{ $order->shipment->deliveryDriver->phone }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Botones de acción -->
                            <div class="flex space-x-2">
                                <a href="{{ route('orders.tracking.show', $order->id) }}"
                                    class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-center py-2 px-4 rounded-lg text-sm font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                                    <i class="fas fa-search-location mr-1"></i>
                                    Ver Tracking
                                </a>
                                @if ($order->status->value >= 2)
                                    <a href="{{ route('orders.invoice', $order->id) }}"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg text-sm transition-colors duration-200"
                                        target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Footer con última actualización -->
                        <div class="bg-gray-50 px-6 py-3">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                Última actualización: {{ $order->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <!-- Estado vacío -->
                    <div class="col-span-full">
                        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                            <div
                                class="bg-gray-100 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No tienes pedidos aún</h3>
                            <p class="text-gray-600 mb-6">¡Explora nuestros productos y realiza tu primera compra!</p>
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Explorar Productos
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if ($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    @php
        // Helper function para calcular el progreso
        function calculateProgress($order)
        {
            $statusProgress = [
                1 => 10, // Pendiente
                2 => 25, // Pago Verificado
                3 => 50, // Preparando
                4 => 70, // Asignado
                5 => 90, // En Camino
                6 => 100, // Entregado
                7 => 0, // Cancelado
            ];
            return $statusProgress[$order->status->value] ?? 0;
        }
    @endphp
</x-app-layout>
