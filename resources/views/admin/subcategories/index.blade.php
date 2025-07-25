<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Subcategorias',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="min-h-screen bg-gradient-to-br from-pink-50 via-white to-rose-50 relative overflow-hidden">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-pink-200/30 to-rose-300/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-rose-200/30 to-pink-300/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-pink-100/40 to-rose-100/40 rounded-full blur-2xl">
            </div>
        </div>

        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.subcategories.create') }}" type="primary" name="Nueva Subcategoría" />
            </x-slot>

            <!-- Contenedor principal con backdrop blur -->
            <div class="glass-effect rounded-3xl shadow-2xl mx-4 my-8 overflow-hidden">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-pink-600 to-rose-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="fas fa-tag text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Gestión de Subcategorías</h2>
                                <p class="text-pink-100 text-sm">Administra las subcategorías organizadas por categorías
                                </p>
                            </div>
                        </div>
                        <div class="text-white/80 text-sm">
                            <i class="fas fa-list mr-1"></i>
                            {{ $subcategories->total() ?? $subcategories->count() }} subcategorías
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas con controles de vista -->
                <div class="bg-white border-b border-gray-200 px-8 py-4">
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                        <!-- Controles de vista -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">Vista:</span>
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <button onclick="toggleView('cards')" id="cards-btn"
                                    class="view-toggle px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 bg-pink-600 text-white shadow-sm">
                                    <i class="fas fa-th-large mr-2"></i>Tarjetas
                                </button>
                                <button onclick="toggleView('table')" id="table-btn"
                                    class="view-toggle px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-table mr-2"></i>Tabla
                                </button>
                            </div>
                        </div>

                        <!-- Filtros y búsqueda -->
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Buscar subcategorías..."
                                    value="{{ request('search') }}"
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm w-64">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <select id="items-per-page"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-500">
                                <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12 por
                                    página</option>
                                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 por página
                                </option>
                                <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 por página
                                </option>
                                <option value="96" {{ request('per_page') == 96 ? 'selected' : '' }}>96 por página
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-8" id="subcategories-container">
                    @if ($subcategories->count())
                        @include('admin.subcategories.partials.subcategories-content')
                    @else
                        <!-- Estado vacío mejorado -->
                        <div class="text-center py-16">
                            <div
                                class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-pink-100 to-rose-100 rounded-full mb-6">
                                <i class="fas fa-tag text-4xl text-pink-500"></i>
                            </div>
                            <h3 class="text-2xl font-semibold text-gray-800 mb-4">No hay subcategorías registradas</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">Todavía no has creado ninguna subcategoría.
                                Las
                                subcategorías te ayudan a organizar productos dentro de cada categoría.</p>
                            <a href="{{ route('admin.subcategories.create') }}"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-plus mr-3 text-white"></i>
                                <span class="text-white">Crear Primera Subcategoría</span>
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
                    btn.classList.remove('bg-pink-600', 'text-white', 'shadow-sm');
                    btn.classList.add('text-gray-600', 'hover:text-gray-900');
                });

                const selectedBtn = document.getElementById(viewType + '-btn');
                selectedBtn.classList.add('bg-pink-600', 'text-white', 'shadow-sm');
                selectedBtn.classList.remove('text-gray-600', 'hover:text-gray-900');

                // Guardar preferencia
                localStorage.setItem('admin_subcategories_view', viewType);
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
                const container = document.getElementById('subcategories-container');

                // Mostrar indicador de carga
                container.style.opacity = '0.6';

                const params = new URLSearchParams();
                if (searchInput.value.trim()) {
                    params.append('search', searchInput.value.trim());
                }
                params.append('per_page', itemsPerPage.value);

                const url = `{{ route('admin.subcategories.index') }}?${params.toString()}`;

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
                        const savedView = localStorage.getItem('admin_subcategories_view') || 'cards';
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
                const savedView = localStorage.getItem('admin_subcategories_view');
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
        </script>
    @endpush

</x-admin-layout>
