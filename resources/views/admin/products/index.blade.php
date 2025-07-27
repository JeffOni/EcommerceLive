<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
    ],
]">


    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-secondary-50 via-white to-primary-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-secondary-200/30 to-primary-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-primary-200/30 to-secondary-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-64 h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-secondary-100/40 to-primary-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.products.create') }}" type="primary" name="Nuevo Producto" />
            </x-slot>

            <!-- Contenedor principal con backdrop blur -->
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header con gradiente -->
                <div class="px-8 py-6 bg-gradient-to-r from-primary-900 to-secondary-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="text-xl text-white fas fa-box"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Gestión de Productos</h2>
                                <p class="text-sm text-blue-100">Administra el catálogo completo de productos</p>
                            </div>
                        </div>
                        <div class="text-sm text-white/80">
                            <i class="mr-1 fas fa-list"></i>
                            {{ $products->total() ?? $products->count() }} productos
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas con controles de vista -->
                <div class="px-8 py-4 bg-white border-b border-gray-200">
                    <div
                        class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
                        <!-- Controles de vista -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">Vista:</span>
                            <div class="flex p-1 bg-gray-100 rounded-lg">
                                <button onclick="toggleView('cards')" id="cards-btn"
                                    class="px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-blue-600 rounded-md shadow-sm view-toggle">
                                    <i class="mr-2 fas fa-th-large"></i>Tarjetas
                                </button>
                                <button onclick="toggleView('table')" id="table-btn"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 transition-all duration-200 rounded-md view-toggle hover:text-gray-900">
                                    <i class="mr-2 fas fa-table"></i>Tabla
                                </button>
                            </div>
                        </div>

                        <!-- Filtros y búsqueda -->
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Buscar productos..."
                                    value="{{ request('search') }}"
                                    class="w-64 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i class="absolute text-gray-400 fas fa-search left-3 top-3"></i>
                            </div>
                            <select id="items-per-page"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="12" {{ request('per_page', 12)==12 ? 'selected' : '' }}>12 por
                                    página</option>
                                <option value="24" {{ request('per_page')==24 ? 'selected' : '' }}>24 por página
                                </option>
                                <option value="48" {{ request('per_page')==48 ? 'selected' : '' }}>48 por página
                                </option>
                                <option value="96" {{ request('per_page')==96 ? 'selected' : '' }}>96 por página
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-8" id="products-container">
                    @if ($products->count())
                    @include('admin.products.partials.products-content')
                    @else
                    <!-- Estado vacío mejorado -->
                    <div class="py-16 text-center">
                        <div
                            class="inline-flex items-center justify-center w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-blue-100 to-purple-100">
                            <i class="text-4xl text-blue-500 fas fa-box"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-semibold text-gray-800">No hay productos registrados</h3>
                        <p class="max-w-md mx-auto mb-8 text-gray-600">Todavía no has creado ningún producto. Los
                            productos son el corazón de tu tienda online.</p>
                        <a href="{{ route('admin.products.create') }}"
                            class="inline-flex items-center px-8 py-3 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 rounded-xl hover:shadow-xl hover:scale-105">
                            <i class="mr-3 text-white fas fa-plus"></i>
                            <span class="text-white">Crear Primer Producto</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript para funcionalidad avanzada --}}
    @push('js')
    <script>
        // Estado de la vista actual
            let currentView = 'cards';

            // Función para cambiar entre vistas
            function toggleView(viewType) {
                // Ocultar todas las vistas
                document.querySelectorAll('.view-content').forEach(view => {
                    view.classList.add('hidden');
                });

                // Mostrar la vista seleccionada
                document.getElementById(viewType + '-view').classList.remove('hidden');

                // Actualizar botones
                document.querySelectorAll('.view-toggle').forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                    btn.classList.add('text-gray-600', 'hover:text-gray-900');
                });

                const selectedBtn = document.getElementById(viewType + '-btn');
                selectedBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                selectedBtn.classList.remove('text-gray-600', 'hover:text-gray-900');

                // Guardar preferencia
                localStorage.setItem('admin_products_view', viewType);
                currentView = viewType;
            }

            // Variable para controlar el estado de carga
            let isLoading = false;

            // Función para cargar datos
            function loadData() {
                if (isLoading) return;

                isLoading = true;
                const searchInput = document.getElementById('search-input');
                const itemsPerPage = document.getElementById('items-per-page');
                const container = document.getElementById('products-container');

                // Mostrar indicador de carga
                container.style.opacity = '0.6';

                const params = new URLSearchParams();
                if (searchInput.value.trim()) {
                    params.append('search', searchInput.value.trim());
                }
                params.append('per_page', itemsPerPage.value);

                const url = `{{ route('admin.products.index') }}?${params.toString()}`;

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
                        container.innerHTML = html;

                        // Restaurar vista seleccionada
                        const savedView = localStorage.getItem('admin_products_view') || 'cards';
                        if (savedView !== 'cards') {
                            setTimeout(() => toggleView(savedView), 100);
                        }

                        container.style.opacity = '1';
                        isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error al cargar datos:', error);
                        container.style.opacity = '1';
                        isLoading = false;
                    });
            }

            // Restaurar vista guardada
            document.addEventListener('DOMContentLoaded', function() {
                const savedView = localStorage.getItem('admin_products_view');
                if (savedView && savedView !== 'cards') {
                    toggleView(savedView);
                }

                // Establecer valor inicial de items por página desde URL
                const urlParams = new URLSearchParams(window.location.search);
                const perPage = urlParams.get('per_page') || '12';
                document.getElementById('items-per-page').value = perPage;

                // Establecer valor inicial de búsqueda desde URL
                const search = urlParams.get('search') || '';
                document.getElementById('search-input').value = search;
            });

            // Búsqueda en tiempo real
            let searchTimeout;
            document.getElementById('search-input').addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadData();
                }, 500); // Reducir a 500ms para mejor UX
            });

            // Cambio de items por página
            document.getElementById('items-per-page').addEventListener('change', function(e) {
                loadData();
            });

            // Función para toggle del estado is_active del producto
            window.toggleProductStatus = function(productId, newStatus) {
                // Mostrar confirmación
                const action = newStatus === 'true' ? 'activar' : 'desactivar';
                const actionColor = newStatus === 'true' ? 'success' : 'warning';
                
                Swal.fire({
                    title: `¿${action.charAt(0).toUpperCase() + action.slice(1)} producto?`,
                    text: `¿Estás seguro de que quieres ${action} este producto?`,
                    icon: actionColor,
                    showCancelButton: true,
                    confirmButtonColor: newStatus === 'true' ? '#10b981' : '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: `Sí, ${action}`,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realizar la petición AJAX
                        fetch(`/admin/products/${productId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                is_active: newStatus === 'true'
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Recargar los datos para actualizar la vista
                                loadData();
                                
                                // Mostrar mensaje de éxito
                                Swal.fire({
                                    title: '¡Éxito!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                throw new Error(data.message || 'Error desconocido');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un problema al actualizar el estado del producto',
                                icon: 'error'
                            });
                        });
                    }
                });
            };
    </script>
    @endpush

</x-admin-layout>