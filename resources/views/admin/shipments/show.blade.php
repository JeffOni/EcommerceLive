<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Envíos',
        'route' => route('admin.shipments.index'),
    ],
    [
        'name' => 'Detalles del Envío',
    ],
]">
    <x-slot name="action">
        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
            <a href="{{ route('admin.shipments.index') }}"
                class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Regresar
            </a>
            <a href="{{ route('admin.shipments.edit', $shipment) }}"
                class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 overflow-x-hidden">
        @php
        // Obtener estado seguro
        $orderStatus = $shipment->order->status ?? 1;
        $isDelivered = $orderStatus == 6; // Estado entregado en orders
        $isCancelled = $orderStatus == 7; // Estado cancelado en orders
        $hasDeliveryDriver = $shipment->delivery_driver_id && $shipment->deliveryDriver;
        $isInTransit = in_array($orderStatus, [4, 5]); // Estados en tránsito

        // Determinar estado de shipment basado en order
        if ($isDelivered) {
        $shipmentStatus = 'delivered';
        $statusColor = 'bg-green-100 text-green-800';
        $statusText = 'Entregado';
        $statusIcon = 'fas fa-check-circle';
        } elseif ($isCancelled) {
        $shipmentStatus = 'cancelled';
        $statusColor = 'bg-red-100 text-red-800';
        $statusText = 'Cancelado';
        $statusIcon = 'fas fa-times-circle';
        } elseif ($hasDeliveryDriver && !$isInTransit) {
        $shipmentStatus = 'assigned';
        $statusColor = 'bg-blue-100 text-blue-800';
        $statusText = 'Esperando viaje';
        $statusIcon = 'fas fa-motorcycle';
        } elseif ($isInTransit) {
        $shipmentStatus = 'in_transit';
        $statusColor = 'bg-orange-100 text-orange-800';
        $statusText = 'En tránsito';
        $statusIcon = 'fas fa-truck';
        } else {
        $shipmentStatus = 'pending';
        $statusColor = 'bg-yellow-100 text-yellow-800';
        $statusText = 'Pendiente';
        $statusIcon = 'fas fa-clock';
        }
        @endphp

        <!-- Header con información principal -->
        <div class="text-center mb-6">
            <div class="flex flex-col items-center space-y-4 sm:flex-row sm:justify-between sm:space-y-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Envío #{{ $shipment->tracking_number }}
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Asociado a la Orden #{{ $shipment->order->id }}
                    </p>
                </div>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusColor }}">
                    <i class="{{ $statusIcon }} mr-2"></i>
                    {{ $statusText }}
                </span>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Información del Cliente y Orden -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-shopping-cart text-green-600 mr-2"></i>
                        Información de la Orden
                    </h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Cliente:</span>
                            <span class="text-sm text-gray-900">{{ $shipment->order->user->name ?? 'No disponible'
                                }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Email:</span>
                            <span class="text-sm text-gray-900 break-all sm:break-normal">{{
                                $shipment->order->user->email ?? 'No disponible' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Teléfono:</span>
                            <span class="text-sm text-gray-900">{{ $shipment->order->user->phone ?? 'No disponible'
                                }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Total:</span>
                            <span class="text-sm font-semibold text-gray-900">${{ number_format($shipment->order->total,
                                2) }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Fecha orden:</span>
                            <span class="text-sm text-gray-900">{{ $shipment->order->created_at->format('d/m/Y H:i')
                                }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del Envío -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-truck text-blue-600 mr-2"></i>
                        Detalles del Envío
                    </h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Tracking:</span>
                            <span class="text-sm font-mono text-gray-900">{{ $shipment->tracking_number }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Fecha creación:</span>
                            <span class="text-sm text-gray-900">{{ $shipment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Costo de envío:</span>
                            <span class="text-sm font-semibold text-gray-900">${{
                                number_format((float)$shipment->delivery_fee, 2) }}</span>
                        </div>
                        @if($shipment->distance_km)
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Distancia:</span>
                            <span class="text-sm text-gray-900">{{ $shipment->distance_km }} km</span>
                        </div>
                        @endif
                        @if($shipment->estimated_delivery_time)
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-sm font-medium text-gray-500">Tiempo estimado:</span>
                            <span class="text-sm text-gray-900">{{ $shipment->estimated_delivery_time }} min</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dirección de Entrega -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                        Dirección de Entrega
                    </h3>
                </div>
                <div class="p-4">
                    @if($shipment->delivery_address)
                    @php
                    $address = $shipment->delivery_address;
                    if (is_string($address)) {
                    $address = json_decode($address, true) ?? [];
                    } elseif (!is_array($address)) {
                    $address = [];
                    }
                    @endphp
                    <div class="space-y-2 text-sm text-gray-700">
                        @if(isset($address['street']))
                        <p class="font-medium">{{ $address['street'] }}</p>
                        @endif
                        @if(isset($address['city']) || isset($address['province']))
                        <p>{{ $address['city'] ?? '' }}{{ isset($address['city']) && isset($address['province']) ? ', '
                            : '' }}{{ $address['province'] ?? '' }}</p>
                        @endif
                        @if(isset($address['postal_code']))
                        <p>CP: {{ $address['postal_code'] }}</p>
                        @endif
                        @if(isset($address['phone']))
                        <p><strong>Teléfono:</strong> {{ $address['phone'] }}</p>
                        @endif
                        @if(isset($address['reference']))
                        <p class="text-gray-600 italic">Referencia: {{ $address['reference'] }}</p>
                        @endif
                    </div>
                    @else
                    <p class="text-sm text-gray-500">No disponible</p>
                    @endif
                </div>
            </div>

            @if($shipment->deliveryDriver)
            <!-- Repartidor Asignado -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-motorcycle text-purple-600 mr-2"></i>
                        Repartidor Asignado
                    </h3>
                </div>
                <div class="p-4">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-lg font-medium">
                                {{ substr($shipment->deliveryDriver->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">{{ $shipment->deliveryDriver->name }}</p>
                            <p class="text-sm text-gray-500">{{ $shipment->deliveryDriver->phone }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $shipment->deliveryDriver->is_active ? 'Activo' : 'Inactivo' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Botones de Acción Condicionales -->
        @if($isInTransit && !$isDelivered && !$isCancelled)
        <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
            <button onclick="markAsDelivered()"
                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Marcar como Entregado
            </button>
            <button onclick="cancelShipment()"
                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Cancelar Envío
            </button>
        </div>
        @endif

        <!-- Timeline del Envío -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history text-blue-600 mr-2"></i>
                    Historial del Envío
                </h3>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-plus text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">Envío creado</div>
                            <div class="text-sm text-gray-500">{{ $shipment->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    @if($shipment->updated_at != $shipment->created_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-edit text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">Última actualización</div>
                            <div class="text-sm text-gray-500">{{ $shipment->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($shipment->deliveryDriver)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-motorcycle text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">Repartidor asignado</div>
                            <div class="text-sm text-gray-500">{{ $shipment->deliveryDriver->name }}</div>
                        </div>
                    </div>
                    @endif

                    @if($shipment->actual_pickup_date)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-box text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">Paquete recogido</div>
                            <div class="text-sm text-gray-500">{{ $shipment->actual_pickup_date->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($isInTransit)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-truck text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">En tránsito</div>
                            <div class="text-sm text-gray-500">El paquete está en camino</div>
                        </div>
                    </div>
                    @endif

                    @if($isDelivered)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">Paquete entregado</div>
                            <div class="text-sm text-gray-500">
                                @if($shipment->actual_delivery_date)
                                {{ $shipment->actual_delivery_date->format('d/m/Y H:i') }}
                                @else
                                Entregado
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($isCancelled)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-times text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">Envío cancelado</div>
                            <div class="text-sm text-gray-500">El envío fue cancelado</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function markAsDelivered() {
            Swal.fire({
                title: '¿Marcar como entregado?',
                text: 'Esta acción cambiará el estado del envío a entregado',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, entregar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("admin.shipments.mark-delivered", $shipment) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Entregado!',
                                text: 'El envío ha sido marcado como entregado',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Error al entregar');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo marcar como entregado',
                            icon: 'error'
                        });
                    });
                }
            });
        }

        function cancelShipment() {
            Swal.fire({
                title: '¿Cancelar envío?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("admin.shipments.cancel", $shipment) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Cancelado!',
                                text: 'El envío ha sido cancelado',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Error al cancelar');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo cancelar el envío',
                            icon: 'error'
                        });
                    });
                }
            });
        }
    </script>
</x-admin-layout>

<!-- Repartidor -->
@if($shipment->deliveryDriver)
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
    <h3 class="flex items-center mb-3 text-lg font-semibold text-gray-900">
        <i class="mr-2 text-purple-600 fas fa-motorcycle"></i>
        Repartidor
    </h3>
    <div class="space-y-2">
        <div class="flex flex-col sm:flex-row sm:justify-between">
            <span class="text-sm font-medium text-gray-500">Nombre:</span>
            <span class="text-sm text-gray-900">{{ $shipment->deliveryDriver->name }}</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-between">
            <span class="text-sm font-medium text-gray-500">Teléfono:</span>
            <span class="text-sm text-gray-900">{{ $shipment->deliveryDriver->phone ?? 'No disponible'
                }}</span>
        </div>
        @if($shipment->deliveryDriver->vehicle_type)
        <div class="flex flex-col sm:flex-row sm:justify-between">
            <span class="text-sm font-medium text-gray-500">Vehículo:</span>
            <span class="text-sm text-gray-900">{{ $shipment->deliveryDriver->vehicle_type }}</span>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Notas -->
@if($shipment->delivery_notes)
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
    <h3 class="flex items-center mb-3 text-lg font-semibold text-gray-900">
        <i class="mr-2 text-yellow-600 fas fa-sticky-note"></i>
        Notas
    </h3>
    <p class="text-sm text-gray-700">{{ $shipment->delivery_notes }}</p>
</div>
@endif

<!-- Acciones -->
@php
$orderStatus = $shipment->order->status ?? 1;
@endphp

@if($isDelivered)
<!-- Estado entregado -->
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="flex items-center justify-center">
        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-800 bg-green-100 rounded-full">
            <i class="mr-2 fas fa-check-circle"></i>
            Entregado
        </span>
    </div>
</div>
@elseif($isCancelled)
<!-- Estado cancelado -->
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="flex items-center justify-center">
        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-800 bg-red-100 rounded-full">
            <i class="mr-2 fas fa-times-circle"></i>
            Cancelado
        </span>
    </div>
</div>
@elseif($shipment->deliveryDriver && in_array($orderStatus, [4, 5]))
{{-- Orden asignada a conductor (4) o en tránsito (5) --}}
@if($orderStatus == 4)
<!-- Esperando viaje -->
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="flex items-center justify-center">
        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">
            <i class="mr-2 fas fa-clock"></i>
            Esperando viaje
        </span>
    </div>
</div>
@else
<!-- En tránsito - mostrar botones de acción -->
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
    <h3 class="mb-3 text-lg font-semibold text-gray-900">Acciones</h3>
    <div class="flex flex-col gap-2 sm:flex-row">
        <button onclick="markAsDelivered({{ $shipment->id }})"
            class="flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-md hover:bg-green-700">
            <i class="mr-2 fas fa-check"></i>
            Marcar como Entregado
        </button>
        <button onclick="cancelShipment({{ $shipment->id }})"
            class="flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium text-white transition-colors bg-red-600 rounded-md hover:bg-red-700">
            <i class="mr-2 fas fa-times"></i>
            Cancelar Envío
        </button>
    </div>
</div>
@endif
@else
<!-- Orden pendiente o sin conductor asignado -->
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="flex items-center justify-center">
        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-gray-100 rounded-full">
            <i class="mr-2 fas fa-clock"></i>
            Pendiente
        </span>
    </div>
</div>
@endif

<!-- Botón Volver -->
<div class="pt-4">
    <a href="{{ route('admin.shipments.index') }}"
        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-gray-600 rounded-md hover:bg-gray-700">
        <i class="mr-2 fas fa-arrow-left"></i>
        Volver a Envíos
    </a>
</div>
</div>
</div>

<!-- Scripts para las acciones -->
<script>
    function markAsDelivered(shipmentId) {
            Swal.fire({
                title: '¿Marcar como entregado?',
                text: 'Esta acción actualizará el estado del envío y la orden',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, entregar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/shipments/${shipmentId}/mark-delivered`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Entregado!',
                                text: 'El envío ha sido marcado como entregado',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Error al actualizar');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo marcar como entregado',
                            icon: 'error'
                        });
                    });
                }
            });
        }

        function cancelShipment(shipmentId) {
            Swal.fire({
                title: '¿Cancelar envío?',
                text: 'Esta acción cancelará el envío y la orden',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Motivo de cancelación',
                        input: 'textarea',
                        inputPlaceholder: 'Ingresa el motivo de la cancelación...',
                        inputValidator: (value) => {
                            if (!value || value.length < 10) {
                                return 'El motivo debe tener al menos 10 caracteres';
                            }
                        }
                    }).then((reasonResult) => {
                        if (reasonResult.isConfirmed) {
                            fetch(`/admin/shipments/${shipmentId}/cancel`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    reason: reasonResult.value
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: '¡Cancelado!',
                                        text: 'El envío ha sido cancelado',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    throw new Error(data.message || 'Error al cancelar');
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo cancelar el envío',
                                    icon: 'error'
                                });
                            });
                        }
                    });
                }
            });
        }
</script>
</x-admin-layout>