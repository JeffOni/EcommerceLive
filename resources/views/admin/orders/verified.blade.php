<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Órdenes Verificadas',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos responsive -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <!-- Elementos decorativos de fondo adaptativos -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-20 -right-20 sm:-top-40 sm:-right-40 w-48 h-48 sm:w-96 sm:h-96 bg-gradient-to-br from-primary-200/30 to-secondary-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-20 -left-20 sm:-bottom-40 sm:-left-40 w-48 h-48 sm:w-96 sm:h-96 bg-gradient-to-tr from-secondary-200/30 to-primary-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-32 h-32 sm:w-64 sm:h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-primary-100/40 to-secondary-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <!-- Contenedor principal responsive con backdrop blur -->
            <div
                class="mx-2 sm:mx-4 my-4 sm:my-8 overflow-hidden shadow-lg sm:shadow-2xl glass-effect rounded-xl sm:rounded-3xl">
                <!-- Header responsive con gradiente -->
                <div
                    class="px-4 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6 bg-gradient-to-r from-primary-900 to-secondary-500">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                            <div class="p-2 sm:p-3 glass-effect rounded-lg sm:rounded-xl flex-shrink-0">
                                <i class="text-lg sm:text-xl text-white fas fa-clipboard-check"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">Órdenes
                                    Verificadas</h2>
                                <p class="text-xs sm:text-sm text-secondary-100 truncate">Gestiona órdenes listas para
                                    envío</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            <div class="text-xs sm:text-sm text-white/80 flex items-center">
                                <i class="mr-1 fas fa-check-double text-xs sm:text-sm"></i>
                                <span class="hidden sm:inline">{{ $orders->total() ?? $orders->count() }} órdenes
                                    verificadas</span>
                                <span class="sm:hidden">{{ $orders->total() ?? $orders->count() }} verificadas</span>
                            </div>
                            <button onclick="refreshOrders()"
                                class="px-2 py-2 sm:px-3 text-white transition-colors rounded-lg bg-white/20 hover:bg-white/30 flex-shrink-0">
                                <i class="fas fa-sync-alt text-xs sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas responsive con filtros -->
                <div class="px-3 py-3 sm:px-6 sm:py-4 lg:px-8 bg-white border-b border-gray-200">
                    <div class="flex flex-col space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                        <!-- Filtros por estado responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <span class="text-sm font-medium text-gray-700 flex-shrink-0">Filtrar por:</span>
                            <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                                <select id="status-filter"
                                    class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Todos los estados</option>
                                    <option value="2" {{ request('status')=='2' ? 'selected' : '' }}>Pago Verificado
                                    </option>
                                    <option value="3" {{ request('status')=='3' ? 'selected' : '' }}>Preparando</option>
                                    <option value="4" {{ request('status')=='4' ? 'selected' : '' }}>Listo para Envío
                                    </option>
                                </select>

                                <select id="payment-method-filter"
                                    class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Todos los métodos</option>
                                    <option value="0" {{ request('payment_method')=='0' ? 'selected' : '' }}>
                                        Transferencia</option>
                                    <option value="1" {{ request('payment_method')=='1' ? 'selected' : '' }}>Tarjeta
                                    </option>
                                    <option value="2" {{ request('payment_method')=='2' ? 'selected' : '' }}>Efectivo
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Búsqueda y configuración responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Buscar órdenes..."
                                    value="{{ request('search') }}"
                                    class="w-full sm:w-48 lg:w-64 py-2 pl-8 sm:pl-10 pr-4 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <i
                                    class="absolute text-gray-400 fas fa-search left-2 sm:left-3 top-2.5 sm:top-3 text-xs sm:text-sm"></i>
                            </div>
                            <select id="items-per-page"
                                class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="15" {{ request('per_page')=='15' ? 'selected' : '' }}>15 por página
                                </option>
                                <option value="25" {{ request('per_page')=='25' ? 'selected' : '' }}>25 por página
                                </option>
                                <option value="50" {{ request('per_page')=='50' ? 'selected' : '' }}>50 por página
                                </option>
                                <option value="100" {{ request('per_page')=='100' ? 'selected' : '' }}>100 por página
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                </select>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="overflow-hidden bg-white" id="orders-content">
        @include('admin.orders.partials.verified-orders-content')
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
                const paymentMethodFilter = document.getElementById('payment-method-filter');
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

                if (paymentMethodFilter) {
                    paymentMethodFilter.addEventListener('change', filterOrders);
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
                const paymentMethod = document.getElementById('payment-method-filter').value;
                const perPage = document.getElementById('items-per-page').value;

                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (status) params.append('status', status);
                if (paymentMethod) params.append('payment_method', paymentMethod);
                if (perPage) params.append('per_page', perPage);

                const url = `{{ route('admin.orders.verified') }}?${params.toString()}`;
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
                            text: 'Hubo un problema al cargar las órdenes',
                            icon: 'error'
                        });
                    });
            }

            function updateOrderStatus(orderId, status) {
                let title = '';
                let text = '';

                switch (status) {
                    case 3:
                        title = '¿Marcar como Preparando?';
                        text = 'La orden pasará al estado "Preparando"';
                        break;
                    case 4:
                        title = '¿Marcar como Listo para Envío?';
                        text = 'La orden estará lista para asignar un repartidor';
                        break;
                    case 7:
                        title = '¿Cancelar Orden?';
                        text = 'Esta acción no se puede deshacer';
                        break;
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: status === 7 ? '#EF4444' : '#10B981',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, actualizar',
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

            function createShipment(orderId) {
                window.location.href = `{{ route('admin.shipments.create') }}?order_id=${orderId}`;
            }

            function downloadPDF(orderId) {
                window.open(`/admin/orders/${orderId}/download-pdf`, '_blank');
            }

            function refreshOrders() {
                filterOrders();
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

        .order-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background: white;
        }

        .order-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .status-badge {
            @apply px-3 py-1 text-xs font-medium rounded-full;
        }

        .status-verificado {
            @apply bg-blue-100 text-blue-800;
        }

        .status-preparando {
            @apply bg-purple-100 text-purple-800;
        }

        .status-listo {
            @apply bg-green-100 text-green-800;
        }

        .payment-method-badge {
            @apply px-2 py-1 text-xs rounded-full font-medium;
        }

        .method-transferencia {
            @apply bg-yellow-100 text-yellow-800;
        }

        .method-tarjeta {
            @apply bg-green-100 text-green-800;
        }

        .method-efectivo {
            @apply bg-orange-100 text-orange-800;
        }

        .action-btn {
            @apply px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200;
        }

        .btn-prepare {
            @apply bg-purple-100 text-purple-700 hover: bg-purple-200;
        }

        .btn-ready {
            @apply bg-green-100 text-green-700 hover: bg-green-200;
        }

        .btn-ship {
            @apply bg-blue-100 text-blue-700 hover: bg-blue-200;
        }

        .btn-cancel {
            @apply bg-red-100 text-red-700 hover: bg-red-200;
        }

        .btn-view {
            @apply bg-gray-100 text-gray-700 hover: bg-gray-200;
        }
    </style>
    @endpush

</x-admin-layout>