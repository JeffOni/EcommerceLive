<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Repartidores',
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
            <x-slot name="action">
                <x-link href="{{ route('admin.delivery-drivers.create') }}" type="primary" name="Nuevo Repartidor" />
            </x-slot>

            <!-- Header -->
            <div class="text-center mb-4 sm:mb-6 pt-4 sm:pt-6 px-3 sm:px-4">
                <h1
                    class="text-lg sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-900 to-secondary-500 bg-clip-text text-transparent mb-2">
                    Gestión de Repartidores
                </h1>
                <p class="text-xs sm:text-sm text-secondary-600">Administra el equipo de delivery</p>
            </div>

            <!-- Contenedor principal responsive con backdrop blur -->
            <div
                class="mx-2 sm:mx-4 my-4 sm:my-8 overflow-hidden shadow-lg sm:shadow-2xl glass-effect rounded-xl sm:rounded-3xl">
                <!-- Header responsive con gradiente -->
                <div
                    class="px-4 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6 bg-gradient-to-r from-primary-900 to-secondary-500">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                            <div class="p-2 sm:p-3 glass-effect rounded-lg sm:rounded-xl flex-shrink-0">
                                <i class="text-lg sm:text-xl text-white fas fa-truck"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">Repartidores
                                </h2>
                                <p class="text-xs sm:text-sm text-secondary-100 truncate">Equipo de delivery</p>
                            </div>
                        </div>
                        <div class="text-xs sm:text-sm text-white/80 flex items-center flex-shrink-0">
                            <i class="mr-1 fas fa-users"></i>
                            {{ $drivers->total() ?? $drivers->count() }} repartidores
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

                        <!-- Filtros responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <span class="text-sm font-medium text-gray-700 flex-shrink-0">Filtros:</span>
                            <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                                <select id="status-filter"
                                    class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Estados</option>
                                    <option value="1" {{ request('status')=='1' ? 'selected' : '' }}>Activos</option>
                                    <option value="0" {{ request('status')=='0' ? 'selected' : '' }}>Inactivos</option>
                                </select>
                                <select id="vehicle-filter"
                                    class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Vehículos</option>
                                    <option value="moto" {{ request('vehicle_type')=='moto' ? 'selected' : '' }}>Moto
                                    </option>
                                    <option value="auto" {{ request('vehicle_type')=='auto' ? 'selected' : '' }}>Auto
                                    </option>
                                    <option value="bicicleta" {{ request('vehicle_type')=='bicicleta' ? 'selected' : ''
                                        }}>Bicicleta</option>
                                    <option value="camion" {{ request('vehicle_type')=='camion' ? 'selected' : '' }}>
                                        Camión</option>
                                    <option value="furgoneta" {{ request('vehicle_type')=='furgoneta' ? 'selected' : ''
                                        }}>Furgoneta</option>
                                </select>
                            </div>
                        </div>

                        <!-- Búsqueda y configuración responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Buscar repartidores..."
                                    value="{{ request('search') }}"
                                    class="w-full sm:w-48 lg:w-64 py-2 pl-8 sm:pl-10 pr-4 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <i
                                    class="absolute text-gray-400 fas fa-search left-2 sm:left-3 top-2.5 sm:top-3 text-xs sm:text-sm"></i>
                            </div>
                            <select id="items-per-page"
                                class="w-full sm:w-auto px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
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
                <div class="overflow-hidden bg-white" id="drivers-content">
                    @include('admin.delivery-drivers.partials.drivers-content')
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
            localStorage.setItem('admin_drivers_view', viewType);
        }

        function filterDrivers() {
            if (isLoading) return;
            
            isLoading = true;
            const container = document.getElementById('drivers-content');
            if (container) {
                container.style.opacity = '0.6';
            }

            const search = document.getElementById('search-input')?.value || '';
            const status = document.getElementById('status-filter')?.value || '';
            const vehicle = document.getElementById('vehicle-filter')?.value || '';
            const perPage = document.getElementById('items-per-page')?.value || '15';

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status !== '') params.append('status', status);
            if (vehicle) params.append('vehicle_type', vehicle);
            if (perPage) params.append('per_page', perPage);

            const url = `{{ route('admin.delivery-drivers.index') }}?${params.toString()}`;
            loadDriversPage(url);

            // Actualizar URL del navegador
            window.history.pushState({}, '', url);
        }

        function loadDriversPage(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    const container = document.getElementById('drivers-content');
                    if (container) {
                        // Extraer solo el contenido del contenedor
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.querySelector('#drivers-content');
                        
                        if (newContent) {
                            container.innerHTML = newContent.innerHTML;
                        } else {
                            container.innerHTML = html;
                        }

                        // Restaurar vista seleccionada
                        const savedView = localStorage.getItem('admin_drivers_view') || 'cards';
                        setTimeout(() => toggleView(savedView), 100);
                        
                        container.style.opacity = '1';
                    }
                    isLoading = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    const container = document.getElementById('drivers-content');
                    if (container) {
                        container.style.opacity = '1';
                    }
                    isLoading = false;
                    
                    // Mostrar error con SweetAlert si está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al cargar los repartidores',
                            icon: 'error'
                        });
                    } else {
                        alert('Error al cargar los repartidores');
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Restaurar vista guardada
            const savedView = localStorage.getItem('admin_drivers_view') || 'cards';
            if (savedView !== 'cards') {
                toggleView(savedView);
            }

            // Configurar búsqueda con debounce
            const searchInput = document.getElementById('search-input');
            const statusFilter = document.getElementById('status-filter');
            const vehicleFilter = document.getElementById('vehicle-filter');
            const itemsPerPage = document.getElementById('items-per-page');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        filterDrivers();
                    }, 300);
                });
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', filterDrivers);
            }

            if (vehicleFilter) {
                vehicleFilter.addEventListener('change', filterDrivers);
            }

            if (itemsPerPage) {
                itemsPerPage.addEventListener('change', filterDrivers);
            }

            // Manejar paginación AJAX
            document.addEventListener('click', function(e) {
                if (e.target.closest('.pagination a')) {
                    e.preventDefault();
                    const url = e.target.closest('.pagination a').href;
                    loadDriversPage(url);
                }
            });
        });

        function toggleDriverStatus(driverId) {
            fetch(`/admin/delivery-drivers/${driverId}/toggle-status`, {
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
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'Estado actualizado correctamente',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                        filterDrivers(); // Recargar tabla
                    } else {
                        throw new Error(data.message || 'Error al actualizar');
                    }
                })
                .catch(error => {
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

        function deleteDriver(driverId) {
            const confirmDelete = () => {
                // Crear form y enviarlo
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/delivery-drivers/${driverId}`;

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
            };

            if (typeof Swal !== 'undefined') {
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
                        confirmDelete();
                    }
                });
            } else {
                if (confirm('¿Estás seguro de que quieres eliminar este repartidor? Esta acción no se puede deshacer.')) {
                    confirmDelete();
                }
            }
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
            @apply px-1.5 py-0.5 text-xs font-medium rounded-full;
        }

        .status-active {
            @apply bg-green-100 text-green-800;
        }

        .status-inactive {
            @apply bg-red-100 text-red-800;
        }

        .vehicle-badge {
            @apply px-2 py-1 text-xs font-medium rounded-full;
        }

        .vehicle-moto {
            @apply bg-blue-100 text-blue-800;
        }

        .vehicle-auto {
            @apply bg-purple-100 text-purple-800;
        }

        .vehicle-bicicleta {
            @apply bg-green-100 text-green-800;
        }

        .vehicle-camion {
            @apply bg-orange-100 text-orange-800;
        }

        .vehicle-furgoneta {
            @apply bg-indigo-100 text-indigo-800;
        }

        /* Botones compactos para tarjetas */
        .card-action-btn {
            @apply w-8 h-8 flex items-center justify-center text-xs border border-gray-300 rounded transition-colors;
        }

        /* Grid compacto para información */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        /* Ultra small screens (344px) */
        @media (max-width: 375px) {
            .glass-effect {
                border-radius: 0.5rem;
            }

            .status-badge {
                font-size: 0.65rem;
                padding: 0.125rem 0.25rem;
            }

            .card-action-btn {
                width: 1.75rem;
                height: 1.75rem;
                font-size: 0.7rem;
            }

            .info-grid {
                gap: 0.25rem;
            }
        }

        /* Hover effects for cards */
        .driver-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
    @endpush

</x-admin-layout>