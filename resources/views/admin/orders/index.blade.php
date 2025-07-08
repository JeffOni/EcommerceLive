<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pedidos',
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
                <div class="px-8 py-6 bg-gradient-to-r from-green-600 to-blue-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="text-xl text-white fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Gestión de Pedidos</h2>
                                <p class="text-sm text-green-100">Administra y supervisa todos los pedidos</p>
                            </div>
                        </div>
                        <div class="text-sm text-white/80">
                            <i class="mr-1 fas fa-list"></i>
                            {{ $orders->total() ?? $orders->count() }} pedidos
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas con filtros -->
                <div class="px-8 py-4 bg-white border-b border-gray-200">
                    <div
                        class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
                        <!-- Filtros por estado -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">Filtrar por:</span>
                            <select id="status-filter"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Todos los estados</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pendiente
                                </option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Pago Verificado
                                </option>
                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Preparando
                                </option>
                                <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Asignado
                                </option>
                                <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>En Camino
                                </option>
                                <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>Entregado
                                </option>
                                <option value="7" {{ request('status') == '7' ? 'selected' : '' }}>Cancelado
                                </option>
                            </select>
                        </div>

                        <!-- Búsqueda y configuración -->
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Buscar pedidos..."
                                    value="{{ request('search') }}"
                                    class="w-64 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <i class="absolute text-gray-400 fas fa-search left-3 top-3"></i>
                            </div>
                            <select id="items-per-page"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15 por página
                                </option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 por
                                    página</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 por
                                    página</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 por
                                    página</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="overflow-hidden bg-white" id="orders-content">
                    @include('admin.orders.partials.orders-content')
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            let searchTimeout;

            document.addEventListener('DOMContentLoaded', function() {
                // Configurar búsqueda con debounce
                const searchInput = document.getElementById('search-input');
                const statusFilter = document.getElementById('status-filter');
                const itemsPerPage = document.getElementById('items-per-page');

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            filterOrders();
                        }, 300);
                    });
                }

                if (statusFilter) {
                    statusFilter.addEventListener('change', filterOrders);
                }

                if (itemsPerPage) {
                    itemsPerPage.addEventListener('change', filterOrders);
                }

                // Manejar paginación AJAX
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.pagination a')) {
                        e.preventDefault();
                        const url = e.target.closest('.pagination a').href;
                        loadOrdersPage(url);
                    }
                });
            });

            function filterOrders() {
                const search = document.getElementById('search-input').value;
                const status = document.getElementById('status-filter').value;
                const perPage = document.getElementById('items-per-page').value;

                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (status) params.append('status', status);
                if (perPage) params.append('per_page', perPage);

                const url = `{{ route('admin.orders.index') }}?${params.toString()}`;
                loadOrdersPage(url);

                // Actualizar URL del navegador
                window.history.pushState({}, '', url);
            }

            function loadOrdersPage(url) {
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('orders-content').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cargar los pedidos',
                            icon: 'error'
                        });
                    });
            }

            function updateOrderStatus(orderId, status) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Deseas cambiar el estado de este pedido?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#EF4444',
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/orders/${orderId}/status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    status: status
                                })
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
                                    filterOrders(); // Recargar tabla
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

            function downloadPDF(orderId) {
                window.open(`/admin/orders/${orderId}/download-pdf`, '_blank');
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

            .status-pendiente {
                @apply bg-yellow-100 text-yellow-800;
            }

            .status-verificado {
                @apply bg-blue-100 text-blue-800;
            }

            .status-preparando {
                @apply bg-purple-100 text-purple-800;
            }

            .status-asignado {
                @apply bg-indigo-100 text-indigo-800;
            }

            .status-en-camino {
                @apply bg-orange-100 text-orange-800;
            }

            .status-entregado {
                @apply bg-green-100 text-green-800;
            }

            .status-cancelado {
                @apply bg-red-100 text-red-800;
            }
        </style>
    @endpush

</x-admin-layout>
