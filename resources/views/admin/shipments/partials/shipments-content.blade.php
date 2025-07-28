@php
// Ya no necesitamos estas variables porque usamos directamente el estado de la orden
@endphp

<!-- View Toggle Buttons -->
<div class="px-3 py-3 bg-white border-b border-gray-200 sm:px-6 sm:py-4">
    <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <!-- View Controls -->
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
            <span class="flex-shrink-0 text-sm font-medium text-gray-700">Vista:</span>
            <div class="flex w-full p-1 bg-gray-100 rounded-lg sm:w-auto">
                <button onclick="toggleView('card')" id="card-view-btn"
                    class="flex-1 px-3 py-2 text-xs font-medium text-white transition-all duration-200 rounded-md shadow-sm sm:flex-none sm:px-4 sm:text-sm bg-primary-600 view-toggle">
                    <i class="mr-1 text-xs sm:mr-2 fas fa-th-large sm:text-sm"></i>
                    <span class="hidden sm:inline">Tarjetas</span>
                    <span class="sm:hidden">Cards</span>
                </button>
                <button onclick="toggleView('table')" id="table-view-btn"
                    class="flex-1 px-3 py-2 text-xs font-medium text-gray-600 transition-all duration-200 rounded-md sm:flex-none sm:px-4 sm:text-sm view-toggle hover:text-gray-900">
                    <i class="mr-1 text-xs sm:mr-2 fas fa-table sm:text-sm"></i>
                    <span class="hidden sm:inline">Tabla</span>
                    <span class="sm:hidden">Table</span>
                </button>
            </div>
        </div>

        <!-- Total Count -->
        <div class="text-xs text-gray-600 sm:text-sm">
            Total: {{ $shipments->total() ?? $shipments->count() }} envíos
        </div>
    </div>
</div>

<!-- Card View (Mobile-first with improved desktop layout) -->
<div id="card-view" class="p-3 view-content sm:p-5 lg:p-8">
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 sm:gap-4 lg:gap-6">
        @forelse ($shipments as $shipment)
        <div class="overflow-hidden transition-all duration-300 bg-white border border-gray-200 rounded-lg shadow-sm shipment-card group sm:rounded-xl hover:shadow-md hover:border-primary-300"
            id="shipment-card-{{ $shipment->id }}">
            <!-- Header -->
            <div class="p-3 border-b border-gray-100 sm:p-4 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center space-x-3">
                    <div
                        class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full sm:w-12 sm:h-12 bg-gradient-to-r from-indigo-500 to-purple-500">
                        <i class="text-sm text-white sm:text-base fas fa-truck"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 truncate sm:text-base">
                            {{ $shipment->tracking_number }}
                        </h3>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500 truncate">
                                {{ $shipment->created_at->format('d/m/Y H:i') }}
                            </p>
                            @php
                            $orderStatus = $shipment->order->status ?? 1;
                            @endphp

                            @if($orderStatus == 6)
                            <span
                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-green-800 bg-green-100 rounded-full ml-2">
                                Entregado
                            </span>
                            @elseif($orderStatus == 7)
                            <span
                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-red-800 bg-red-100 rounded-full ml-2">
                                Cancelado
                            </span>
                            @elseif($shipment->deliveryDriver && $orderStatus == 4)
                            <span
                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full ml-2">
                                Esperando
                            </span>
                            @elseif($shipment->deliveryDriver && $orderStatus == 5)
                            <span
                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-blue-800 bg-blue-100 rounded-full ml-2">
                                Tránsito
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-gray-800 bg-gray-100 rounded-full ml-2">
                                Pendiente
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-3 space-y-3 sm:p-4">
                <!-- Order Info -->
                @if($shipment->order)
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="mb-1 text-xs text-gray-500">Orden</div>
                        <a href="{{ route('admin.orders.show', $shipment->order) }}"
                            class="block text-sm font-medium text-indigo-600 truncate hover:text-indigo-500">
                            #{{ $shipment->order->id }}
                        </a>
                    </div>
                    <div class="ml-3 text-right">
                        <div class="mb-1 text-xs text-gray-500">Total</div>
                        <div class="text-sm font-semibold text-gray-900">
                            ${{ number_format($shipment->order->total ?? 0, 2) }}
                        </div>
                    </div>
                </div>
                @else
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="mb-1 text-xs text-gray-500">Orden</div>
                        <span class="text-sm font-medium text-gray-400">Sin orden asociada</span>
                    </div>
                    <div class="ml-3 text-right">
                        <div class="mb-1 text-xs text-gray-500">Total</div>
                        <div class="text-sm font-semibold text-gray-400">$0.00</div>
                    </div>
                </div>
                @endif

                <!-- Driver Info -->
                <div>
                    <div class="mb-2 text-xs text-gray-500">Repartidor</div>
                    @if ($shipment->deliveryDriver)
                    <div class="flex items-center">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-6 h-6 rounded-full sm:w-8 sm:h-8 bg-gradient-to-r from-green-500 to-emerald-500">
                            <span class="text-xs font-medium text-white sm:text-sm">
                                {{ substr($shipment->deliveryDriver->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0 ml-2">
                            <div class="text-sm font-medium text-gray-900 truncate">
                                {{ $shipment->deliveryDriver->name }}
                            </div>
                            <div class="text-xs text-gray-500 truncate">
                                {{ $shipment->deliveryDriver->phone }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-sm italic text-gray-400">Sin asignar</div>
                    @endif
                </div>

                @if ($shipment->estimated_delivery_date)
                <!-- Delivery Date -->
                <div>
                    <div class="mb-1 text-xs text-gray-500">Entrega estimada</div>
                    <div class="text-sm text-gray-900">
                        {{ $shipment->estimated_delivery_date->format('d/m/Y') }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer Actions -->
            <div class="px-3 py-3 border-t border-gray-100 sm:px-4 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.shipments.show', $shipment) }}"
                            class="text-indigo-600 transition-colors hover:text-indigo-700" title="Ver">
                            <i class="text-sm fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.shipments.edit', $shipment) }}"
                            class="text-green-600 transition-colors hover:text-green-700" title="Editar">
                            <i class="text-sm fas fa-edit"></i>
                        </a>
                        @if(!$shipment->deliveryDriver)
                        <button onclick="openAssignDriverModal({{ $shipment->id }})"
                            class="text-yellow-600 transition-colors hover:text-yellow-700" title="Asignar Repartidor">
                            <i class="text-sm fas fa-user-plus"></i>
                        </button>
                        @endif
                        <button onclick="trackShipment('{{ $shipment->tracking_number }}')"
                            class="text-blue-600 transition-colors hover:text-blue-700" title="Rastrear">
                            <i class="text-sm fas fa-map-marker-alt"></i>
                        </button>
                    </div>

                    @if($orderStatus == 6)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                        <i class="mr-1 fas fa-check-circle"></i>
                        Entregado
                    </span>
                    @elseif($orderStatus == 7)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                        <i class="mr-1 fas fa-times-circle"></i>
                        Cancelado
                    </span>
                    @elseif($shipment->deliveryDriver && in_array($orderStatus, [4, 5]))
                    {{-- Orden asignada a conductor (4) o en tránsito (5) - mostrar badge esperando viaje solo si está
                    asignada --}}
                    @if($orderStatus == 4)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                        <i class="mr-1 fas fa-clock"></i>
                        Esperando viaje
                    </span>
                    @else
                    {{-- En tránsito - mostrar botones de entrega/cancelar --}}
                    <div class="flex space-x-1">
                        <button onclick="markOrderAsDelivered({{ $shipment->id }})"
                            class="inline-flex items-center px-1.5 py-1 text-xs font-medium text-white bg-green-600 border border-transparent rounded hover:bg-green-700 transition-colors"
                            title="Marcar como entregado">
                            <i class="text-xs fas fa-check"></i>
                        </button>
                        <button onclick="cancelShipment({{ $shipment->id }})"
                            class="inline-flex items-center px-1.5 py-1 text-xs font-medium text-white bg-red-600 border border-transparent rounded hover:bg-red-700 transition-colors"
                            title="Cancelar envío">
                            <i class="text-xs fas fa-times"></i>
                        </button>
                    </div>
                    @endif
                    @else
                    {{-- Orden pendiente o sin conductor asignado --}}
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                        <i class="mr-1 fas fa-clock"></i>
                        Pendiente
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="py-12 text-center col-span-full">
            <i class="mb-4 text-4xl text-gray-300 fas fa-shipping-fast"></i>
            <h3 class="text-lg font-medium text-gray-900">No hay envíos</h3>
            <p class="mt-1 text-sm text-gray-500">No se encontraron envíos con los filtros aplicados.</p>
            <a href="{{ route('admin.shipments.create') }}"
                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                <i class="mr-2 fas fa-plus"></i>
                Crear Envío
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Table View -->
<div id="table-view" class="hidden overflow-x-auto view-content">
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th
                    class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-4 sm:py-3">
                    Tracking
                </th>
                <th
                    class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-4 sm:py-3">
                    Orden
                </th>
                <th
                    class="hidden px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:table-cell sm:px-4 sm:py-3">
                    Repartidor
                </th>
                <th
                    class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:px-4 sm:py-3">
                    Estado
                </th>
                <th
                    class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase sm:px-4 sm:py-3">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($shipments as $shipment)
            <tr id="shipment-row-{{ $shipment->id }}" class="transition-colors hover:bg-gray-50">
                <!-- Tracking -->
                <td class="px-3 py-2 sm:px-4 sm:py-3">
                    <div class="flex items-center">
                        <div
                            class="flex items-center justify-center w-6 h-6 text-white bg-indigo-600 rounded sm:w-8 sm:h-8">
                            <i class="text-xs fas fa-truck"></i>
                        </div>
                        <div class="ml-2">
                            <div class="text-xs font-medium text-gray-900 sm:text-sm">
                                {{ $shipment->tracking_number }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $shipment->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </td>

                <!-- Order -->
                <td class="px-3 py-2 sm:px-4 sm:py-3">
                    @if($shipment->order)
                    <div class="text-xs sm:text-sm">
                        <a href="{{ route('admin.orders.show', $shipment->order) }}"
                            class="font-medium text-indigo-600 hover:text-indigo-500">
                            #{{ $shipment->order->id }}
                        </a>
                    </div>
                    <div class="text-xs text-gray-500">
                        ${{ number_format($shipment->order->total ?? 0, 2) }}
                    </div>
                    @else
                    <div class="text-xs sm:text-sm">
                        <span class="font-medium text-gray-400">Sin orden</span>
                    </div>
                    <div class="text-xs text-gray-500">
                        $0.00
                    </div>
                    @endif
                </td>

                <!-- Driver (hidden on mobile) -->
                <td class="hidden px-3 py-2 sm:table-cell sm:px-4 sm:py-3">
                    @if ($shipment->deliveryDriver)
                    <div class="flex items-center">
                        <div
                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-green-600 rounded-full">
                            {{ substr($shipment->deliveryDriver->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 ml-2">
                            <div class="text-xs font-medium text-gray-900 truncate sm:text-sm">
                                {{ $shipment->deliveryDriver->name }}
                            </div>
                        </div>
                    </div>
                    @else
                    <span class="text-xs text-gray-400 sm:text-sm">Sin asignar</span>
                    @endif
                </td>

                <!-- Status -->
                <td class="px-3 py-2 sm:px-4 sm:py-3">
                    @php
                    $orderStatus2 = $shipment->order->status ?? 1;
                    @endphp

                    @if($orderStatus2 == 6)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                        Entregado
                    </span>
                    @elseif($orderStatus2 == 7)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                        Cancelado
                    </span>
                    @elseif($shipment->deliveryDriver && $orderStatus2 == 4)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                        Esperando viaje
                    </span>
                    @elseif($shipment->deliveryDriver && $orderStatus2 == 5)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                        En tránsito
                    </span>
                    @else
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                        Pendiente
                    </span>
                    @endif
                </td>

                <!-- Actions -->
                <td class="px-3 py-2 text-center sm:px-4 sm:py-3">
                    @if($orderStatus2 == 6)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                        <i class="mr-1 fas fa-check-circle"></i>
                        <span class="hidden sm:inline">Entregado</span>
                    </span>
                    @elseif($orderStatus2 == 7)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                        <i class="mr-1 fas fa-times-circle"></i>
                        <span class="hidden sm:inline">Cancelado</span>
                    </span>
                    @elseif($shipment->deliveryDriver && in_array($orderStatus2, [4, 5]))
                    {{-- Orden asignada a conductor (4) o en tránsito (5) --}}
                    @if($orderStatus2 == 4)
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                        <i class="mr-1 fas fa-clock"></i>
                        <span class="hidden sm:inline">Esperando viaje</span>
                    </span>
                    @else
                    {{-- En tránsito - mostrar botones de entrega/cancelar --}}
                    <div class="flex items-center justify-center space-x-1">
                        <button onclick="markOrderAsDelivered({{ $shipment->id }})"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 border border-transparent rounded hover:bg-green-700"
                            title="Marcar como entregado">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="cancelShipment({{ $shipment->id }})"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 border border-transparent rounded hover:bg-red-700"
                            title="Cancelar envío">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endif
                    @else
                    {{-- Orden pendiente o sin conductor asignado --}}
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                        <i class="mr-1 fas fa-clock"></i>
                        <span class="hidden sm:inline">Pendiente</span>
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="mb-4 text-4xl text-gray-300 fas fa-shipping-fast"></i>
                        <h3 class="text-lg font-medium text-gray-900">No hay envíos</h3>
                        <p class="mt-1 text-sm text-gray-500">No se encontraron envíos con los filtros aplicados.</p>
                        <a href="{{ route('admin.shipments.create') }}"
                            class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                            <i class="mr-2 fas fa-plus"></i>
                            Crear Envío
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if ($shipments instanceof \Illuminate\Pagination\LengthAwarePaginator && $shipments->hasPages())
<div class="px-3 py-3 border-t border-gray-200 sm:px-4 sm:py-4">
    <div class="flex flex-col items-center justify-between space-y-2 sm:flex-row sm:space-y-0">
        <div class="text-xs text-gray-500 sm:text-sm">
            Mostrando {{ $shipments->firstItem() }} a {{ $shipments->lastItem() }} de {{ $shipments->total() }}
            resultados
        </div>
        <div class="pagination">
            {{ $shipments->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endif

<script>
    let currentView = 'card';

// Función para cambiar entre vistas (igual que delivery-drivers)
function toggleView(viewType) {
    // Actualizar la variable global
    currentView = viewType;
    
    // Ocultar todas las vistas
    document.querySelectorAll('.view-content').forEach(view => {
        view.classList.add('hidden');
    });

    // Mostrar la vista seleccionada
    const targetView = document.getElementById(viewType + '-view');
    if (targetView) {
        targetView.classList.remove('hidden');
    }

    // Actualizar botones
    document.querySelectorAll('.view-toggle').forEach(btn => {
        btn.classList.remove('bg-primary-600', 'text-white', 'shadow-sm');
        btn.classList.add('text-gray-600', 'hover:text-gray-900');
    });

    const selectedBtn = document.getElementById(viewType + '-view-btn');
    if (selectedBtn) {
        selectedBtn.classList.add('bg-primary-600', 'text-white', 'shadow-sm');
        selectedBtn.classList.remove('text-gray-600', 'hover:text-gray-900');
    }

    // Guardar preferencia
    localStorage.setItem('shipments-view-preference', viewType);
}

// Load saved preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('shipments-view-preference') || 'card';
    toggleView(savedView);
});

function deleteShipment(shipmentId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/shipments/${shipmentId}`;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';

            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function cancelShipment(shipmentId) {
    Swal.fire({
        title: 'Cancelar envío',
        text: 'Indica el motivo:',
        input: 'textarea',
        inputPlaceholder: 'Motivo de la cancelación...',
        inputAttributes: {
            'aria-label': 'Motivo de cancelación',
            'maxlength': 200,
            'rows': 3
        },
        width: '400px',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Cancelar',
        cancelButtonText: 'Cerrar',
        buttonsStyling: true,
        customClass: {
            popup: 'swal-small',
            input: 'swal-small-input'
        },
        inputValidator: (value) => {
            if (!value || value.trim() === '') {
                return 'Debes proporcionar un motivo';
            }
            if (value.length < 5) {
                return 'Mínimo 5 caracteres';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/shipments/${shipmentId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reason: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Cancelado!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    // Update both views
                    updateShipmentStatus(shipmentId, 'cancelled');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al cancelar el envío.',
                    icon: 'error'
                });
                console.error('Error:', error);
            });
        }
    });
}

function markOrderAsDelivered(shipmentId) {
    Swal.fire({
        title: '¿Confirmar entrega?',
        text: '¿Está seguro que desea marcar este envío como entregado?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#EF4444',
        confirmButtonText: 'Sí, entregar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/shipments/${shipmentId}/mark-delivered`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Entregado!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    // Update both views
                    updateShipmentStatus(shipmentId, 'delivered');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al marcar como entregado.',
                    icon: 'error'
                });
                console.error('Error:', error);
            });
        }
    });
}

function updateShipmentStatus(shipmentId, status) {
    const statusBadges = {
        delivered: '<span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full"><i class="mr-1 fas fa-check-circle"></i>Entregado</span>',
        cancelled: '<span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full"><i class="mr-1 fas fa-times-circle"></i>Cancelado</span>'
    };
    
    // Update table view
    const tableRow = document.querySelector(`#shipment-row-${shipmentId}`);
    if (tableRow) {
        const actionsCell = tableRow.querySelector('td:last-child');
        if (actionsCell) {
            actionsCell.innerHTML = statusBadges[status];
        }
    }
    
    // Update card view
    const card = document.querySelector(`#shipment-card-${shipmentId}`);
    if (card) {
        const actionsDiv = card.querySelector('.flex.items-center.justify-between:last-child > div:last-child');
        if (actionsDiv) {
            actionsDiv.innerHTML = statusBadges[status];
        }
    }
}

function trackShipment(trackingNumber) {
    window.open(`/track/${trackingNumber}`, '_blank');
}

function openAssignDriverModal(shipmentId) {
    // Esta función debería ser implementada según la lógica de asignación de repartidores
    console.log('Asignar repartidor para envío:', shipmentId);
}
</script>