<!-- Vista para pantallas grandes (tabla) -->
<div class="hidden lg:block">
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-50                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            });
        }
    }

    // Nueva función para abrir modal de asignación de repartidor
    function openAssignDriverModal(shipmentId) {
        Swal.fire({
            title: 'Asignar Repartidor',
            html: `
                <select id=" driverSelect" class="swal2-select">
                        <option value="">Seleccionar repartidor...</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }} - {{ $driver->phone }}</option>
                        @endforeach
                        </select>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Asignar',
                        cancelButtonText: 'Cancelar',
                        preConfirm: () => {
                        const driverId = document.getElementById('driverSelect').value;
                        if (!driverId) {
                        Swal.showValidationMessage('Por favor selecciona un repartidor');
                        return false;
                        }
                        return driverId;
                        }
                        }).then((result) => {
                        if (result.isConfirmed) {
                        assignDriverToShipment(shipmentId, result.value);
                        }
                        });
                        }

                        // Función para asignar repartidor via AJAX
                        function assignDriverToShipment(shipmentId, driverId) {
                        fetch(`/admin/shipments/${shipmentId}/assign-driver`, {
                        method: 'PATCH',
                        headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                        delivery_driver_id: driverId
                        })
                        })
                        .then(response => response.json())
                        .then(data => {
                        if (data.success) {
                        Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                        }).then(() => {
                        location.reload(); // Recargar la página para mostrar los cambios
                        });
                        } else {
                        Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo asignar el repartidor'
                        });
                        }
                        })
                        .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al asignar el repartidor'
                        });
                        });
                        }case">
                        <input type="checkbox"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Tracking
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Orden
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Repartidor
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Fecha Estimada
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($shipments as $shipment)
                <tr class="transition-colors hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    </td>

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

                    <td class="px-6 py-4 whitespace-nowrap">
                        <select class="text-sm border-0 rounded-full status-badge status-{{ $shipment->status }}"
                            onchange="updateShipmentStatus({{ $shipment->id }}, this.value)">
                            <option value="pending" {{ $shipment->status === 'pending' ? 'selected' : '' }}>
                                Pendiente
                            </option>
                            <option value="assigned" {{ $shipment->status === 'assigned' ? 'selected' : '' }}>
                                Asignado
                            </option>
                            <option value="in_transit" {{ $shipment->status === 'in_transit' ? 'selected' : '' }}>
                                En Tránsito
                            </option>
                            <option value="delivered" {{ $shipment->status === 'delivered' ? 'selected' : '' }}>
                                Entregado
                            </option>
                            <option value="failed" {{ $shipment->status === 'failed' ? 'selected' : '' }}>
                                Fallido
                            </option>
                        </select>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        {{ $shipment->estimated_delivery_date ? $shipment->estimated_delivery_date->format('d/m/Y') :
                        'No definida' }}
                    </td>

                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.shipments.show', $shipment) }}"
                                class="text-indigo-600 hover:text-indigo-900" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.shipments.edit', $shipment) }}"
                                class="text-green-600 hover:text-green-900" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$shipment->deliveryDriver)
                            <button onclick="openAssignDriverModal({{ $shipment->id }})"
                                class="text-yellow-600 hover:text-yellow-900" title="Asignar Repartidor">
                                <i class="fas fa-user-plus"></i>
                            </button>
                            @endif
                            <button onclick="trackShipment('{{ $shipment->tracking_number }}')"
                                class="text-blue-600 hover:text-blue-900" title="Rastrear">
                                <i class="fas fa-map-marker-alt"></i>
                            </button>
                            <button onclick="deleteShipment({{ $shipment->id }})"
                                class="text-red-600 hover:text-red-900" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="mb-4 text-4xl text-gray-300 fas fa-shipping-fast"></i>
                            <h3 class="text-lg font-medium text-gray-900">No hay envíos</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer envío.</p>
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
        <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
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
                <span class="status-badge status-{{ $shipment->status }}">
                    {{ ucfirst(str_replace('_', ' ', $shipment->status->value)) }}
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
                <select class="text-xs border-gray-300 rounded-lg status-badge status-{{ $shipment->status }}"
                    onchange="updateShipmentStatus({{ $shipment->id }}, this.value)">
                    <option value="pending" {{ $shipment->status === 'pending' ? 'selected' : '' }}>
                        Pendiente
                    </option>
                    <option value="assigned" {{ $shipment->status === 'assigned' ? 'selected' : '' }}>
                        Asignado
                    </option>
                    <option value="in_transit" {{ $shipment->status === 'in_transit' ? 'selected' : '' }}>
                        En Tránsito
                    </option>
                    <option value="delivered" {{ $shipment->status === 'delivered' ? 'selected' : '' }}>
                        Entregado
                    </option>
                    <option value="failed" {{ $shipment->status === 'failed' ? 'selected' : '' }}>
                        Fallido
                    </option>
                </select>
            </div>
        </div>
        @empty
        <div class="py-12 text-center">
            <i class="mb-4 text-4xl text-gray-300 fas fa-shipping-fast"></i>
            <h3 class="text-lg font-medium text-gray-900">No hay envíos</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer envío.</p>
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
</script>