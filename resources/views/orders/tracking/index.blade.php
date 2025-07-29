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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($order->status instanceof \App\Enums\OrderStatus && $order->status->value == 6) bg-green-100 text-green-800
                                    @elseif($order->status instanceof \App\Enums\OrderStatus && $order->status->value == 7) bg-red-100 text-red-800
                                    @elseif($order->status instanceof \App\Enums\OrderStatus && $order->status->value >= 4) bg-blue-100 text-blue-800
                                    @elseif($order->status instanceof \App\Enums\OrderStatus && $order->status->value >= 2) bg-yellow-100 text-yellow-800
                                    @elseif(is_int($order->status) && $order->status == 6) bg-green-100 text-green-800
                                    @elseif(is_int($order->status) && $order->status == 7) bg-red-100 text-red-800
                                    @elseif(is_int($order->status) && $order->status >= 4) bg-blue-100 text-blue-800
                                    @elseif(is_int($order->status) && $order->status >= 2) bg-yellow-100 text-yellow-800
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
                                <span class="font-semibold text-gray-900">${{ number_format($order->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Método de pago:</span>
                                <span class="text-gray-900">{{ $order->payment_method_text }}</span>
                            </div>
                            @if ($order->shipment)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tracking:</span>
                                <span class="font-mono text-blue-600">{{ $order->shipment->tracking_number }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Barra de progreso -->
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Progreso</span>
                                <span>{{ $order->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $order->progress }}%"></div>
                            </div>
                        </div>

                        <!-- Información del repartidor -->
                        @if ($order->shipment && $order->shipment->deliveryDriver && (($order->status instanceof
                        \App\Enums\OrderStatus && $order->status->value >= 4) || (is_int($order->status) &&
                        $order->status >= 4)))
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

                        <!-- Estado y seguimiento visual -->
                        @php
                        $status = \App\Enums\OrderStatus::from($order->status);
                        @endphp
                        <div class="mt-2 flex items-center space-x-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $status->color() }}-100 text-{{ $status->color() }}-800">
                                <i class="fas {{ $status->icon() }} mr-1"></i>
                                {{ $status->label() }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $status->description() }}</span>
                        </div>
                        <!-- Seguimiento visual del proceso -->
                        <div class="mt-2 flex items-center space-x-1">
                            @php
                            try {
                            $activeStates = \App\Enums\OrderStatus::activeStates();
                            } catch (\Exception $e) {
                            $activeStates = [];
                            }
                            @endphp
                            @foreach($activeStates as $step)
                            <span
                                class="w-3 h-3 rounded-full border-2 @if($order->status >= $step->value) border-{{ $step->color() }}-500 bg-{{ $step->color() }}-400 @else border-gray-300 bg-gray-100 @endif"></span>
                            @endforeach
                        </div>
                        <!-- Acciones -->
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('orders.tracking.show', $order) }}"
                                class="text-blue-600 hover:underline">Ver Detalles</a>
                            <a href="{{ route('orders.invoice', $order) }}"
                                class="text-green-600 hover:underline">Factura</a>
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
                        <div class="bg-gray-100 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center">
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
</x-app-layout>