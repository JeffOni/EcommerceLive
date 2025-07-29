<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Mis Pedidos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header con estadísticas -->
            <div class="mb-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-6 text-white bg-gradient-to-r from-blue-500 to-purple-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Mis Pedidos</h3>
                            <p class="text-blue-100">Consulta el estado de todos tus pedidos</p>
                        </div>
                        <div class="px-4 py-2 bg-white rounded-lg bg-opacity-20">
                            <span class="text-2xl font-bold">{{ $orders->total() }}</span>
                            <p class="text-sm text-blue-100">Total de pedidos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de pedidos -->
            <div class="grid items-stretch grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($orders as $order)
                <div
                    class="flex flex-col h-full overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-lg hover:shadow-xl">
                    <!-- Header del pedido -->
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                        <div class="flex items-start justify-between">
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
                    <div class="flex-1 p-6">
                        <!-- Información básica -->
                        <div class="mb-4 space-y-3">
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
                            <div class="flex justify-between mb-1 text-xs text-gray-600">
                                <span>Progreso</span>
                                <span>{{ $order->progress_percentage }}%</span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 transition-all duration-500 rounded-full bg-gradient-to-r from-blue-500 to-purple-600"
                                    style="width: {{ $order->progress_percentage }}%"></div>
                            </div>
                        </div>

                        <!-- Información del repartidor -->
                        @if ($order->shipment && $order->shipment->deliveryDriver && (($order->status instanceof
                        \App\Enums\OrderStatus && $order->status->value >= 4) || (is_int($order->status) &&
                        $order->status >= 4)))
                        <div class="p-3 mb-4 rounded-lg bg-blue-50">
                            <div class="flex items-center">
                                <div class="p-2 mr-3 bg-blue-100 rounded-full">
                                    <i class="text-blue-600 fas fa-user"></i>
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
                        <div class="flex items-center mt-2 space-x-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $status->color() }}-100 text-{{ $status->color() }}-800">
                                <i class="fas {{ $status->icon() }} mr-1"></i>
                                {{ $status->label() }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $status->description() }}</span>
                        </div>
                        <!-- Seguimiento visual del proceso -->
                        <div class="flex items-center mt-2 space-x-1">
                            @foreach(\App\Enums\OrderStatus::activeStates() as $step)
                            <span
                                class="w-3 h-3 rounded-full border-2 @if($order->status >= $step->value) border-{{ $step->color() }}-500 bg-{{ $step->color() }}-400 @else border-gray-300 bg-gray-100 @endif"></span>
                            @endforeach
                        </div>
                        <!-- Acciones -->
                        <div class="flex mt-4 space-x-2">
                            <a href="{{ route('orders.tracking.show', $order) }}"
                                class="text-blue-600 hover:underline">Ver Detalles</a>
                            <a href="{{ route('orders.invoice', $order) }}"
                                class="text-green-600 hover:underline">Factura</a>
                        </div>
                    </div>

                    <!-- Footer con última actualización -->
                    <div class="px-6 py-3 mt-auto border-t bg-gray-50">
                        <p class="text-xs text-gray-500">
                            <i class="mr-1 fas fa-clock"></i>
                            Última actualización: {{ $order->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <!-- Estado vacío -->
                <div class="col-span-full">
                    <div class="p-12 text-center bg-white rounded-lg shadow-lg">
                        <div class="flex items-center justify-center w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full">
                            <i class="text-3xl text-gray-400 fas fa-shopping-bag"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-semibold text-gray-900">No tienes pedidos aún</h3>
                        <p class="mb-6 text-gray-600">¡Explora nuestros productos y realiza tu primera compra!</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center px-6 py-3 font-medium text-white transition-all duration-200 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700">
                            <i class="mr-2 fas fa-shopping-cart"></i>
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