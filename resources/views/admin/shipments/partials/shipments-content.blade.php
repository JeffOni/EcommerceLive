<!-- Vista para pantallas grandes (tabla) -->
<div class="hidden lg:block">
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">#</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tracking
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Orden
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Repartidor</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($shipments as $shipment)
                <tr id="shipment-row-{{ $shipment->id }}" class="transition-colors hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10">
                                <div
                                    class="flex items-center justify-center w-10 h-10 text-white bg-indigo-600 rounded-lg">
                                    <i class="text-sm fas fa-truck"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $shipment->tracking_number }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $shipment->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <a href="{{ route('admin.orders.show', $shipment->order) }}"
                                class="font-medium text-indigo-600 hover:text-indigo-500">
                                #{{ $shipment->order->id }}
                            </a>
                        </div>
                        <div class="text-sm text-gray-500">
                            ${{ number_format($shipment->order->total, 2) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($shipment->deliveryDriver)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8">
                                <div
                                    class="flex items-center justify-center w-8 h-8 text-white bg-green-600 rounded-full">
                                    {{ substr($shipment->deliveryDriver->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-2">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $shipment->deliveryDriver->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $shipment->deliveryDriver->phone }}
                                </div>
                            </div>
                        </div>
                        @else
                        <span class="text-sm text-gray-400">Sin asignar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @php
                        $shipmentStatus = $shipment->status->value ?? $shipment->status;
                        @endphp
                        @if($shipmentStatus === 'delivered')
                        <span
                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                            <i class="mr-1 fas fa-check-circle"></i>
                            Entregado
                        </span>
                        @elseif($shipmentStatus === 'failed')
                        <span
                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                            <i class="mr-1 fas fa-times-circle"></i>
                            Cancelado
                        </span>
                        @else
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="markOrderAsDelivered({{ $shipment->id }})"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="mr-1 fas fa-check"></i>
                                Entregado
                            </button>
                            <button onclick="cancelShipment({{ $shipment->id }})"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="mr-1 fas fa-times"></i>
                                Cancelar
                            </button>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="mb-4 text-4xl text-gray-300 fas fa-check-circle"></i>
                            <h3 class="text-lg font-medium text-gray-900">No hay envíos pendientes</h3>
                            <p class="mt-1 text-sm text-gray-500">Todos los envíos han sido procesados o no hay envíos
                                creados.</p>
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
</div>

<!-- Vista para pantallas pequeñas (cards) -->
<div class="lg:hidden">
    <div class="space-y-4">
        @forelse ($shipments as $shipment)
        <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
            <!-- Header de la card -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 text-white bg-indigo-600 rounded-lg">
                        <i class="text-sm fas fa-truck"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $shipment->tracking_number }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $shipment->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                <span
                    class="status-badge inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$shipment->status->value ?? $shipment->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $shipment->status->value ?? $shipment->status)) }}
                </span>
            </div>

            <!-- Información de la orden -->
            <div class="mb-4">
                <div class="text-sm text-gray-600">Orden:</div>
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.orders.show', $shipment->order) }}"
                        class="text-sm font-medium text-indigo-600">
                        #{{ $shipment->order->id }}
                    </a>
                    <span class="text-sm text-gray-900">
                        ${{ number_format($shipment->order->total, 2) }}
                    </span>
                </div>
            </div>

            <!-- Información del repartidor -->
            <div class="mb-4">
                <div class="text-sm text-gray-600">Repartidor:</div>
                @if ($shipment->deliveryDriver)
                <div class="flex items-center mt-1">
                    <div class="flex items-center justify-center w-6 h-6 text-xs text-white bg-green-600 rounded-full">
                        {{ substr($shipment->deliveryDriver->name, 0, 1) }}
                    </div>
                    <div class="ml-2">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $shipment->deliveryDriver->name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $shipment->deliveryDriver->phone }}
                        </div>
                    </div>
                </div>
                @else
                <div class="text-sm text-gray-400">Sin asignar</div>
                @endif
            </div>

            <!-- Fecha estimada -->
            @if ($shipment->estimated_delivery_date)
            <div class="mb-4">
                <div class="text-sm text-gray-600">Entrega estimada:</div>
                <div class="text-sm text-gray-900">
                    {{ $shipment->estimated_delivery_date->format('d/m/Y') }}
                </div>
            </div>
            @endif

            <!-- Acciones -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.shipments.show', $shipment) }}"
                        class="text-indigo-600 hover:text-indigo-700">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.shipments.edit', $shipment) }}"
                        class="text-green-600 hover:text-green-700">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if(!$shipment->deliveryDriver)
                    <button onclick="openAssignDriverModal({{ $shipment->id }})"
                        class="text-yellow-600 hover:text-yellow-700" title="Asignar Repartidor">
                        <i class="fas fa-user-plus"></i>
                    </button>
                    @endif
                    <button onclick="trackShipment('{{ $shipment->tracking_number }}')"
                        class="text-blue-600 hover:text-blue-700">
                        <i class="fas fa-map-marker-alt"></i>
                    </button>
                </div>

                @if(($shipment->status->value ?? $shipment->status) === 'delivered')
                <span
                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                    <i class="mr-1 fas fa-check-circle"></i>
                    Entregado
                </span>
                @elseif(($shipment->status->value ?? $shipment->status) === 'failed')
                <span
                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                    <i class="mr-1 fas fa-times-circle"></i>
                    Cancelado
                </span>
                @else
                <div class="flex space-x-2">
                    <button onclick="markOrderAsDelivered({{ $shipment->id }})"
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 border border-transparent rounded hover:bg-green-700">
                        <i class="mr-1 fas fa-check"></i>
                        Entregado
                    </button>
                    <button onclick="cancelShipment({{ $shipment->id }})"
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 border border-transparent rounded hover:bg-red-700">
                        <i class="mr-1 fas fa-times"></i>
                        Cancelar
                    </button>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="py-12 text-center">
            <i class="mb-4 text-4xl text-gray-300 fas fa-check-circle"></i>
            <h3 class="text-lg font-medium text-gray-900">No hay envíos pendientes</h3>
            <p class="mt-1 text-sm text-gray-500">Todos los envíos han sido procesados o no hay envíos creados.</p>
            <a href="{{ route('admin.shipments.create') }}"
                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                <i class="mr-2 fas fa-plus"></i>
                Crear Envío
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Paginación -->
@if ($shipments instanceof \Illuminate\Pagination\LengthAwarePaginator && $shipments->hasPages())
<div class="px-6 py-4 border-t border-gray-200">
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-500">
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
            text: 'Por favor, indica el motivo de la cancelación:',
            input: 'textarea',
            inputPlaceholder: 'Escribe el motivo de la cancelación...',
            inputAttributes: {
                'aria-label': 'Motivo de cancelación',
                'maxlength': 255
            },
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Cancelar envío',
            cancelButtonText: 'Cerrar',
            inputValidator: (value) => {
                if (!value || value.trim() === '') {
                    return 'Debes proporcionar un motivo para la cancelación';
                }
                if (value.length < 10) {
                    return 'El motivo debe tener al menos 10 caracteres';
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
                        // Actualizar la celda de acciones con el badge
                        const row = document.querySelector(`#shipment-row-${shipmentId}`);
                        if (row) {
                            const actionsCell = row.querySelector('td:last-child');
                            if (actionsCell) {
                                actionsCell.innerHTML = `
                                    <span class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                        <i class="mr-1 fas fa-times-circle"></i>
                                        Cancelado
                                    </span>
                                `;
                            }
                        }
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
                        // Actualizar la celda de acciones con el badge
                        const row = document.querySelector(`#shipment-row-${shipmentId}`);
                        if (row) {
                            const actionsCell = row.querySelector('td:last-child');
                            if (actionsCell) {
                                actionsCell.innerHTML = `
                                    <span class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                        <i class="mr-1 fas fa-check-circle"></i>
                                        Entregado
                                    </span>
                                `;
                            }
                        }
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
</script>