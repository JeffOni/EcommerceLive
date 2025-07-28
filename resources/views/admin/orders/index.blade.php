<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pedidos',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos responsive -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-secondary-50 via-white to-primary-50">
        <!-- Elementos decorativos de fondo adaptativos -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-20 -right-20 sm:-top-40 sm:-right-40 w-48 h-48 sm:w-96 sm:h-96 bg-gradient-to-br from-secondary-200/30 to-primary-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-20 -left-20 sm:-bottom-40 sm:-left-40 w-48 h-48 sm:w-96 sm:h-96 bg-gradient-to-tr from-primary-200/30 to-secondary-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-32 h-32 sm:w-64 sm:h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-secondary-100/40 to-primary-100/40 blur-2xl">
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
                                <i class="text-lg sm:text-xl text-white fas fa-shopping-cart"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">Gestión de
                                    Pedidos</h2>
                                <p class="text-xs sm:text-sm text-secondary-100 truncate">Administra y supervisa todos
                                    los pedidos</p>
                            </div>
                        </div>
                        <div class="text-xs sm:text-sm text-white/80 flex items-center flex-shrink-0">
                            <i class="mr-1 fas fa-list"></i>
                            {{ $orders->total() ?? $orders->count() }} pedidos
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas responsive con filtros -->
                <div class="px-3 py-3 sm:px-6 sm:py-4 lg:px-8 bg-white border-b border-gray-200">
                    <div class="flex flex-col space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                        <!-- Controles de vista responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <span class="text-sm font-medium text-gray-700 flex-shrink-0">Vista:</span>
                            <div class="flex p-1 bg-gray-100 rounded-lg w-full sm:w-auto">
                                <button onclick="toggleView('cards')" id="cards-btn"
                                    class="flex-1 sm:flex-none px-3 py-2 sm:px-4 text-xs sm:text-sm font-medium text-white transition-all duration-200 bg-primary-600 rounded-md shadow-sm view-toggle">
                                    <i class="mr-1 sm:mr-2 fas fa-th-large text-xs sm:text-sm"></i>
                                    <span class="hidden sm:inline">Tarjetas</span>
                                    <span class="sm:hidden">Cards</span>
                                </button>
                                <button onclick="toggleView('table')" id="table-btn"
                                    class="flex-1 sm:flex-none px-3 py-2 sm:px-4 text-xs sm:text-sm font-medium text-gray-600 transition-all duration-200 rounded-md view-toggle hover:text-gray-900">
                                    <i class="mr-1 sm:mr-2 fas fa-table text-xs sm:text-sm"></i>
                                    <span class="hidden sm:inline">Tabla</span>
                                    <span class="sm:hidden">Table</span>
                                </button>
                            </div>
                        </div>

                        <!-- Filtros por estado responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <span class="text-sm font-medium text-gray-700 flex-shrink-0">Filtrar por:</span>
                            <select id="status-filter"
                                class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Todos los estados</option>
                                <option value="1" {{ request('status')=='1' ? 'selected' : '' }}>Pendiente de Pago
                                </option>
                                <option value="2" {{ request('status')=='2' ? 'selected' : '' }}>Pago Confirmado
                                </option>
                                <option value="3" {{ request('status')=='3' ? 'selected' : '' }}>Preparando Pedido
                                </option>
                                <option value="4" {{ request('status')=='4' ? 'selected' : '' }}>Asignado a Repartidor
                                </option>
                                <option value="5" {{ request('status')=='5' ? 'selected' : '' }}>En Camino</option>
                                <option value="6" {{ request('status')=='6' ? 'selected' : '' }}>Entregado</option>
                                <option value="7" {{ request('status')=='7' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>

                        <!-- Búsqueda y configuración responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Buscar pedidos..."
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
        let currentView = 'cards';
        let isLoading = false;

        // Función para cambiar entre vistas
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

            const selectedBtn = document.getElementById(viewType + '-btn');
            if (selectedBtn) {
                selectedBtn.classList.add('bg-primary-600', 'text-white', 'shadow-sm');
                selectedBtn.classList.remove('text-gray-600', 'hover:text-gray-900');
            }

            // Guardar preferencia
            localStorage.setItem('admin_orders_view', viewType);
        }

        function filterOrders() {
            const search = document.getElementById('search-input')?.value || '';
            const status = document.getElementById('status-filter')?.value || '';
            const perPage = document.getElementById('items-per-page')?.value || '15';

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status) params.append('status', status);
            if (perPage) params.append('per_page', perPage);

            const url = `{{ route('admin.orders.index') }}?${params.toString()}`;
            loadOrdersPage(url);

            // Actualizar URL sin recargar
            window.history.pushState({}, '', url);
        }

        function loadOrdersPage(url) {
            if (isLoading) return;
            
            isLoading = true;
            const container = document.getElementById('orders-content');
            if (container) {
                container.style.opacity = '0.6';
            }

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    if (container) {
                        // Extraer solo el contenido del contenedor
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.querySelector('#orders-content');
                        
                        if (newContent) {
                            container.innerHTML = newContent.innerHTML;
                        } else {
                            container.innerHTML = html;
                        }

                        // Restaurar vista seleccionada
                        const savedView = localStorage.getItem('admin_orders_view') || 'cards';
                        setTimeout(() => toggleView(savedView), 100);
                        
                        container.style.opacity = '1';
                    }
                    isLoading = false;
                })
                .catch(error => {
                    console.error('Error al cargar pedidos:', error);
                    if (container) {
                        container.style.opacity = '1';
                    }
                    isLoading = false;
                    
                    // Mostrar error con SweetAlert si está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cargar los pedidos',
                            icon: 'error'
                        });
                    } else {
                        alert('Error al cargar los pedidos');
                    }
                });
        }

        function updateOrderStatus(orderId, status) {
            if (!status) return;
            
            // Mensajes específicos por estado
            let title, text, confirmText;
            
            switch(parseInt(status)) {
                case 2:
                    title = '¿Confirmar Pago?';
                    text = 'Confirma que el pago ha sido verificado';
                    confirmText = 'Sí, confirmar';
                    break;
                case 3:
                    title = '¿Marcar como Preparando?';
                    text = 'La orden pasará al estado "Preparando Pedido"';
                    confirmText = 'Sí, preparar';
                    break;
                case 5:
                    title = '¿Poner En Camino?';
                    text = 'El repartidor iniciará la entrega del pedido';
                    confirmText = 'Sí, poner en camino';
                    break;
                case 6:
                    title = '¿Marcar como Entregado?';
                    text = 'Confirma que el pedido fue entregado al cliente';
                    confirmText = 'Sí, entregar';
                    break;
                case 7:
                    title = '¿Cancelar Pedido?';
                    text = 'Esta acción no se puede deshacer';
                    confirmText = 'Sí, cancelar';
                    break;
                default:
                    title = '¿Cambiar Estado?';
                    text = '¿Deseas cambiar el estado de este pedido?';
                    confirmText = 'Sí, cambiar';
            }
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#EF4444',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performStatusUpdate(orderId, status);
                    }
                });
            } else {
                if (confirm(text)) {
                    performStatusUpdate(orderId, status);
                }
            }
        }

        let currentOrderId = null;
        let availableDrivers = [];

        function openAssignDriverModal(orderId) {
            currentOrderId = orderId;
            document.getElementById('modal-order-id').textContent = `#${orderId}`;
            document.getElementById('assign-driver-modal').classList.remove('hidden');
            
            // Cargar repartidores disponibles
            loadAvailableDrivers();
        }

        function closeAssignDriverModal() {
            document.getElementById('assign-driver-modal').classList.add('hidden');
            currentOrderId = null;
            availableDrivers = [];
        }

        function loadAvailableDrivers() {
            const select = document.getElementById('driver-select');
            select.innerHTML = '<option value="">Cargando repartidores...</option>';
            
            fetch('/admin/delivery-drivers/active', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                availableDrivers = data;
                select.innerHTML = '<option value="">Seleccionar repartidor...</option>';
                
                if (data.length === 0) {
                    select.innerHTML = '<option value="">No hay repartidores disponibles</option>';
                    return;
                }
                
                data.forEach(driver => {
                    const option = document.createElement('option');
                    option.value = driver.id;
                    // Mostrar información adicional del repartidor
                    const activeShipments = driver.active_shipments_count || 0;
                    const maxShipments = driver.max_shipments || 10;
                    option.textContent = `${driver.name} - ${driver.phone} (${activeShipments}/${maxShipments} envíos)`;
                    select.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error cargando repartidores:', error);
                select.innerHTML = '<option value="">Error cargando repartidores</option>';
            });
        }

        function confirmAssignDriver() {
            const driverId = document.getElementById('driver-select').value;

            if (!driverId) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error',
                        text: 'Debe seleccionar un repartidor',
                        icon: 'error'
                    });
                } else {
                    alert('Debe seleccionar un repartidor');
                }
                return;
            }

            // Encontrar el nombre del repartidor
            const selectedDriver = availableDrivers.find(d => d.id == driverId);
            const driverName = selectedDriver ? selectedDriver.name : 'Repartidor';

            fetch(`/admin/orders/${currentOrderId}/assign-driver`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    delivery_driver_id: driverId
                })
            })
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        throw new Error(data.message || `Error ${response.status}: ${response.statusText}`);
                    }
                    return data;
                });
            })
            .then(data => {
                if (data.success) {
                    closeAssignDriverModal();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¡Repartidor Asignado!',
                    text: `${driverName} ha sido asignado al pedido #${currentOrderId}. Ahora puede poner el pedido "En Camino" cuando esté listo.`,
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
            }                    // Recargar contenido para reflejar el estado actualizado
                    filterOrders();
                } else {
                    throw new Error(data.message || 'Error al asignar repartidor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'No se pudo asignar el repartidor';

                if (error.message.includes('envíos activos')) {
                    errorMessage = `El repartidor ${driverName} ya tiene el máximo de envíos permitidos. Por favor, selecciona otro repartidor.`;
                } else if (error.message.includes('no disponible')) {
                    errorMessage = `El repartidor ${driverName} no está disponible en este momento.`;
                } else if (error.message !== 'No se pudo asignar el repartidor') {
                    errorMessage = error.message;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error al Asignar Repartidor',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                        loadAvailableDrivers();
                    });
                } else {
                    alert(errorMessage);
                    loadAvailableDrivers();
                }
            });
        }

        function performStatusUpdate(orderId, status) {
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
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'Estado actualizado correctamente',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                        filterOrders(); // Recargar contenido
                    } else {
                        throw new Error(data.message || 'Error al actualizar');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo actualizar el estado',
                            icon: 'error'
                        });
                    } else {
                        alert('Error al actualizar el estado');
                    }
                });
        }

        function downloadPDF(orderId) {
            window.open(`/admin/orders/${orderId}/download-pdf`, '_blank');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Restaurar vista guardada
            const savedView = localStorage.getItem('admin_orders_view') || 'cards';
            if (savedView !== 'cards') {
                toggleView(savedView);
            }

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

        .status-pagado {
            @apply bg-blue-100 text-blue-800;
        }

        .status-preparando {
            @apply bg-purple-100 text-purple-800;
        }

        .status-asignado {
            @apply bg-indigo-100 text-indigo-800;
        }

        .status-enviado {
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

    @push('css')
    <style>
        .status-badge {
            @apply px-2 py-1 rounded-full text-xs font-medium;
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

        /* Modal styles */
        #assign-driver-modal {
            z-index: 1000;
        }

        /* Mobile responsiveness for action buttons */
        @media (max-width: 640px) {
            .action-buttons {
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .action-btn {
                flex: 1;
                min-width: 0;
                font-size: 0.75rem;
                padding: 0.375rem 0.5rem;
            }
        }
    </style>
    @endpush

</x-admin-layout>