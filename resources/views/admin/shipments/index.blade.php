<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Envíos',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-blue-200/30 to-purple-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-200/30 to-blue-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-64 h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-blue-100/40 to-purple-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <!-- Contenedor principal con backdrop blur -->
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header con gradiente -->
                <div class="px-8 py-6 bg-gradient-to-r from-indigo-600 to-purple-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="text-xl text-white fas fa-shipping-fast"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Gestión de Envíos</h2>
                                <p class="text-sm text-indigo-100">Administra y rastrea todos los envíos</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-white/80">
                                <i class="mr-1 fas fa-truck"></i>
                                {{ $shipments->total() ?? $shipments->count() }} envíos
                            </div>
                            <a href="{{ route('admin.shipments.create') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 glass-effect rounded-xl hover:bg-white/20">
                                <i class="mr-2 fas fa-plus"></i>
                                Nuevo Envío
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas con filtros -->
                <div class="px-8 py-4 bg-white border-b border-gray-200">
                    <div
                        class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
                        <!-- Filtros -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">Filtrar por:</span>
                            <select id="status-filter"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Todos los estados</option>
                                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>
                                    Pendiente</option>
                                <option value="assigned" {{ request('status')=='assigned' ? 'selected' : '' }}>
                                    Asignado</option>
                                <option value="in_transit" {{ request('status')=='in_transit' ? 'selected' : '' }}>
                                    En Tránsito</option>
                                <option value="delivered" {{ request('status')=='delivered' ? 'selected' : '' }}>
                                    Entregado</option>
                                <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>
                                    Fallido</option>
                            </select>

                            <select id="driver-filter"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Todos los repartidores</option>
                                @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ request('driver_id')==$driver->id ? 'selected' : ''
                                    }}>
                                    {{ $driver->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Búsqueda -->
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Buscar envíos..."
                                    value="{{ request('search') }}"
                                    class="w-64 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <i class="absolute text-gray-400 fas fa-search left-3 top-3"></i>
                            </div>
                            <select id="items-per-page"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="15" {{ request('per_page')=='15' ? 'selected' : '' }}>15 por
                                    página</option>
                                <option value="25" {{ request('per_page')=='25' ? 'selected' : '' }}>25 por
                                    página</option>
                                <option value="50" {{ request('per_page')=='50' ? 'selected' : '' }}>50 por
                                    página</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="overflow-hidden bg-white" id="shipments-content">
                    @include('admin.shipments.partials.shipments-content')
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        let searchTimeout;

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const statusFilter = document.getElementById('status-filter');
                const driverFilter = document.getElementById('driver-filter');
                const itemsPerPage = document.getElementById('items-per-page');

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            filterShipments();
                        }, 300);
                    });
                }

                if (statusFilter) {
                    statusFilter.addEventListener('change', filterShipments);
                }

                if (driverFilter) {
                    driverFilter.addEventListener('change', filterShipments);
                }

                if (itemsPerPage) {
                    itemsPerPage.addEventListener('change', filterShipments);
                }

                // Manejar paginación AJAX
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.pagination a')) {
                        e.preventDefault();
                        const url = e.target.closest('.pagination a').href;
                        loadShipmentsPage(url);
                    }
                });
            });

            function filterShipments() {
                const search = document.getElementById('search-input').value;
                const status = document.getElementById('status-filter').value;
                const driverId = document.getElementById('driver-filter').value;
                const perPage = document.getElementById('items-per-page').value;

                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (status) params.append('status', status);
                if (driverId) params.append('driver_id', driverId);
                if (perPage) params.append('per_page', perPage);

                const url = `{{ route('admin.shipments.index') }}?${params.toString()}`;
                loadShipmentsPage(url);

                window.history.pushState({}, '', url);
            }

            function loadShipmentsPage(url) {
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('shipments-content').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cargar los envíos',
                            icon: 'error'
                        });
                    });
            }

            function updateShipmentStatus(shipmentId, status) {
                let statusText = '';
                switch(status) {
                    case 'pending': statusText = 'pendiente'; break;
                    case 'assigned': statusText = 'asignado'; break;
                    case 'in_transit': statusText = 'en tránsito'; break;
                    case 'delivered': statusText = 'entregado'; break;
                    case 'failed': statusText = 'fallido'; break;
                    default: statusText = status;
                }

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Deseas cambiar el estado de este envío a "${statusText}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6366F1',
                    cancelButtonColor: '#EF4444',
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Usar ruta específica para marcar como entregado o ruta general para otros estados
                        const url = status === 'delivered' 
                            ? `/admin/shipments/${shipmentId}/mark-delivered`
                            : `/admin/shipments/${shipmentId}/status`;
                        
                        const body = status === 'delivered' 
                            ? JSON.stringify({})
                            : JSON.stringify({ status: status });

                        fetch(url, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: body
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: '¡Éxito!',
                                        text: 'Estado actualizado correctamente',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    
                                    // Si se marcó como entregado, eliminar la fila
                                    if (status === 'delivered') {
                                        const row = document.querySelector(`#shipment-row-${shipmentId}`);
                                        if (row) {
                                            row.remove();
                                        }
                                    } else {
                                        filterShipments();
                                    }
                                } else {
                                    throw new Error(data.message || 'Error al actualizar');
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo actualizar el estado',
                                    icon: 'error'
                                });
                            });
                    }
                });
            }

            function trackShipment(trackingNumber) {
                window.open(`/track/${trackingNumber}`, '_blank');
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
    @endpush

    @push('css')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .status-badge {
            @apply px-2 py-1 text-xs font-medium rounded-full;
        }

        .status-pending {
            @apply bg-yellow-100 text-yellow-800;
        }

        .status-assigned {
            @apply bg-blue-100 text-blue-800;
        }

        .status-in_transit {
            @apply bg-orange-100 text-orange-800;
        }

        .status-delivered {
            @apply bg-green-100 text-green-800;
        }

        .status-failed {
            @apply bg-red-100 text-red-800;
        }
    </style>
    @endpush

</x-admin-layout>