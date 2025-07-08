<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Órdenes Verificadas',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-green-50 via-white to-blue-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-green-200/30 to-blue-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-blue-200/30 to-green-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-64 h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-green-100/40 to-blue-100/40 blur-2xl">
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
                                <i class="text-xl text-white fas fa-clipboard-check"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Órdenes Verificadas</h2>
                                <p class="text-sm text-green-100">Gestiona órdenes listas para envío</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-white/80">
                                <i class="mr-1 fas fa-check-double"></i>
                                {{ $orders->total() ?? $orders->count() }} órdenes verificadas
                            </div>
                            <button onclick="refreshOrders()"
                                class="px-3 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors">
                                <i class="fas fa-sync-alt"></i>
                            </button>
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
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Pago Verificado
                                </option>
                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Preparando
                                </option>
                                <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Listo para Envío
                                </option>
                            </select>

                            <select id="payment-method-filter"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Todos los métodos</option>
                                <option value="0" {{ request('payment_method') == '0' ? 'selected' : '' }}>
                                    Transferencia</option>
                                <option value="1" {{ request('payment_method') == '1' ? 'selected' : '' }}>Tarjeta
                                </option>
                                <option value="2" {{ request('payment_method') == '2' ? 'selected' : '' }}>Efectivo
                                </option>
                            </select>
                        </div>

                        <!-- Búsqueda y configuración -->
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Buscar órdenes..."
                                    value="{{ request('search') }}"
                                    class="w-64 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <i class="absolute text-gray-400 fas fa-search left-3 top-3"></i>
                            </div>
                            <select id="items-per-page"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15 por página
                                </option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 por página
                                </option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 por
                                    página</option>
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
                @apply bg-purple-100 text-purple-700 hover:bg-purple-200;
            }

            .btn-ready {
                @apply bg-green-100 text-green-700 hover:bg-green-200;
            }

            .btn-ship {
                @apply bg-blue-100 text-blue-700 hover:bg-blue-200;
            }

            .btn-cancel {
                @apply bg-red-100 text-red-700 hover:bg-red-200;
            }

            .btn-view {
                @apply bg-gray-100 text-gray-700 hover:bg-gray-200;
            }
        </style>
    @endpush

</x-admin-layout>
