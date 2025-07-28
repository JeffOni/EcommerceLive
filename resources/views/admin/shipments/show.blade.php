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

    <div class="w-full max-w-sm mx-auto px-3 py-4 sm:max-w-2xl sm:px-6 lg:max-w-5xl lg:px-8">
        @php
        // Obtener estado seguro
        $orderStatus = $shipment->order->status ?? 1;
        $isDelivered = $orderStatus == 6; // Estado entregado en orders
        $isCancelled = $orderStatus == 7; // Estado cancelado en orders

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
        } else {
        $shipmentStatus = 'pending';
        $statusColor = 'bg-yellow-100 text-yellow-800';
        $statusText = 'Pendiente';
        $statusIcon = 'fas fa-clock';
        }
        @endphp

        <!-- Header -->
        <div class="mb-4 text-center sm:text-left">
            <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">
                        Envío #{{ $shipment->tracking_number }}
                    </h1>
                    <p class="text-sm text-gray-600">Orden #{{ $shipment->order->id }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                    <i class="{{ $statusIcon }} mr-2"></i>
                    {{ $statusText }}
                </span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-4">
            <!-- Información del Cliente -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Cliente
                </h3>
                <div class="space-y-2">
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <span class="text-sm font-medium text-gray-500">Nombre:</span>
                        <span class="text-sm text-gray-900">{{ $shipment->order->user->name ?? 'No disponible' }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <span class="text-sm font-medium text-gray-500">Email:</span>
                        <span class="text-sm text-gray-900">{{ $shipment->order->user->email ?? 'No disponible'
                            }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <span class="text-sm font-medium text-gray-500">Teléfono:</span>
                        <span class="text-sm text-gray-900">{{ $shipment->order->user->phone ?? 'No disponible'
                            }}</span>
                    </div>
                </div>
            </div>

            <!-- Información del Envío -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-truck text-green-600 mr-2"></i>
                    Detalles del Envío
                </h3>
                <div class="space-y-2">
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <span class="text-sm font-medium text-gray-500">Fecha de creación:</span>
                        <span class="text-sm text-gray-900">{{ $shipment->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <span class="text-sm font-medium text-gray-500">Costo de envío:</span>
                        <span class="text-sm text-gray-900">${{ number_format((float)$shipment->delivery_fee, 2)
                            }}</span>
                    </div>
                    @if($shipment->distance_km)
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <span class="text-sm font-medium text-gray-500">Distancia:</span>
                        <span class="text-sm text-gray-900">{{ $shipment->distance_km }} km</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Dirección de Entrega -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                    Dirección de Entrega
                </h3>
                @if($shipment->delivery_address)
                @php
                $address = $shipment->delivery_address;
                if (is_string($address)) {
                $address = json_decode($address, true) ?? [];
                } elseif (!is_array($address)) {
                $address = [];
                }
                @endphp
                <div class="space-y-1 text-sm text-gray-700">
                    @if(isset($address['street']))
                    <p>{{ $address['street'] }}</p>
                    @endif
                    @if(isset($address['city']) || isset($address['province']))
                    <p>{{ $address['city'] ?? '' }}{{ isset($address['city']) && isset($address['province']) ? ', ' : ''
                        }}{{ $address['province'] ?? '' }}</p>
                    @endif
                    @if(isset($address['postal_code']))
                    <p>CP: {{ $address['postal_code'] }}</p>
                    @endif
                    @if(isset($address['reference']))
                    <p class="text-gray-600 italic">Referencia: {{ $address['reference'] }}</p>
                    @endif
                </div>
                @else
                <p class="text-sm text-gray-500">No disponible</p>
                @endif
            </div>

            <!-- Repartidor -->
            @if($shipment->deliveryDriver)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-motorcycle text-purple-600 mr-2"></i>
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-center">
                    <span
                        class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                        <i class="fas fa-check-circle mr-2"></i>
                        Entregado
                    </span>
                </div>
            </div>
            @elseif($isCancelled)
            <!-- Estado cancelado -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-center">
                    <span
                        class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                        <i class="fas fa-times-circle mr-2"></i>
                        Cancelado
                    </span>
                </div>
            </div>
            @elseif($shipment->deliveryDriver && in_array($orderStatus, [4, 5]))
            {{-- Orden asignada a conductor (4) o en tránsito (5) --}}
            @if($orderStatus == 4)
            <!-- Esperando viaje -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-center">
                    <span
                        class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                        <i class="fas fa-clock mr-2"></i>
                        Esperando viaje
                    </span>
                </div>
            </div>
            @else
            <!-- En tránsito - mostrar botones de acción -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Acciones</h3>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <button onclick="markAsDelivered({{ $shipment->id }})"
                        class="flex-1 flex justify-center items-center py-2 px-4 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Marcar como Entregado
                    </button>
                    <button onclick="cancelShipment({{ $shipment->id }})"
                        class="flex-1 flex justify-center items-center py-2 px-4 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar Envío
                    </button>
                </div>
            </div>
            @endif
            @else
            <!-- Orden pendiente o sin conductor asignado -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-center">
                    <span
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                        <i class="fas fa-clock mr-2"></i>
                        Pendiente
                    </span>
                </div>
            </div>
            @endif

            <!-- Botón Volver -->
            <div class="pt-4">
                <a href="{{ route('admin.shipments.index') }}"
                    class="w-full flex justify-center items-center py-2 px-4 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
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