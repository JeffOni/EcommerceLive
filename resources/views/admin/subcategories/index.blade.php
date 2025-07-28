<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Subcategorias',
    ],
]">

    <!-- Fondo con gradiente responsive -->
    <div class="min-h-screen bg-gradient-to-br from-secondary-50 via-white to-primary-50">
        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.subcategories.create') }}" type="primary" name="Nueva Subcategoría" />
            </x-slot>

            <!-- Header -->
            <div class="text-center mb-4 sm:mb-6 pt-4 sm:pt-6 px-3 sm:px-4">
                <h1
                    class="text-lg sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-900 to-secondary-500 bg-clip-text text-transparent mb-2">
                    Gestión de Subcategorías
                </h1>
                <p class="text-xs sm:text-sm text-secondary-600">Administra las subcategorías organizadas por categorías
                </p>
            </div>

            <!-- Contenedor principal responsivo -->
            <div
                class="max-w-5xl mx-3 sm:mx-4 lg:mx-auto glass-effect rounded-xl sm:rounded-2xl shadow-lg overflow-hidden relative">
                <!-- Header con gradiente responsive -->
                <div
                    class="px-3 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6 bg-gradient-to-r from-primary-900 to-secondary-500">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 glass-effect rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="text-sm sm:text-base lg:text-xl text-white fas fa-tag"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-sm sm:text-lg lg:text-2xl font-bold text-white truncate">Subcategorías
                                </h2>
                                <p class="text-xs sm:text-sm text-pink-100 truncate">Organizadas por categorías</p>
                            </div>
                        </div>
                        <div class="text-xs sm:text-sm text-white/80 flex-shrink-0">
                            <i class="mr-1 fas fa-list"></i>
                            {{ $subcategories->total() ?? $subcategories->count() }} subcategorías
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas responsive -->
                <div class="px-3 py-3 sm:px-6 sm:py-4 lg:px-8 bg-white border-b border-gray-200">
                    <div
                        class="flex flex-col space-y-3 sm:space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                        <!-- Controles de vista responsive -->
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            <span class="text-xs sm:text-sm font-medium text-gray-700 flex-shrink-0">Vista:</span>
                            <div class="flex p-1 bg-gray-100 rounded-lg">
                                <button onclick="toggleView('cards')" id="cards-btn"
                                    class="px-2 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-white transition-all duration-200 rounded-md shadow-sm bg-primary-600 view-toggle">
                                    <i class="sm:mr-2 fas fa-th-large"></i><span
                                        class="hidden sm:inline">Tarjetas</span>
                                </button>
                                <button onclick="toggleView('table')" id="table-btn"
                                    class="px-2 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-gray-600 transition-all duration-200 rounded-md view-toggle hover:text-gray-900">
                                    <i class="sm:mr-2 fas fa-table"></i><span class="hidden sm:inline">Tabla</span>
                                </button>
                            </div>
                        </div>

                        <!-- Filtros y búsqueda responsive -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                            <div class="relative flex-1 sm:flex-none">
                                <input type="text" id="search-input" placeholder="Buscar..."
                                    value="{{ request('search') }}"
                                    class="w-full sm:w-48 lg:w-64 py-1.5 sm:py-2 pl-8 sm:pl-10 pr-3 sm:pr-4 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                                <i
                                    class="absolute text-gray-400 fas fa-search left-2.5 sm:left-3 top-2 sm:top-3 text-xs sm:text-sm"></i>
                            </div>
                            <select id="items-per-page"
                                class="px-2 py-1.5 sm:px-3 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                                <option value="12" {{ request('per_page', 12)==12 ? 'selected' : '' }}>12</option>
                                <option value="24" {{ request('per_page')==24 ? 'selected' : '' }}>24</option>
                                <option value="48" {{ request('per_page')==48 ? 'selected' : '' }}>48</option>
                                <option value="96" {{ request('per_page')==96 ? 'selected' : '' }}>96</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-5 lg:p-8" id="subcategories-container">
                    @if ($subcategories->count())
                    @include('admin.subcategories.partials.subcategories-content')
                    @else
                    <!-- Estado vacío responsive -->
                    <div class="py-8 sm:py-12 lg:py-16 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 mb-4 sm:mb-6 rounded-full bg-gradient-to-br from-pink-100 to-rose-100">
                            <i class="text-2xl sm:text-3xl lg:text-4xl text-pink-500 fas fa-tag"></i>
                        </div>
                        <h3 class="mb-3 sm:mb-4 text-lg sm:text-xl lg:text-2xl font-semibold text-gray-800">No hay
                            subcategorías registradas</h3>
                        <p class="max-w-sm sm:max-w-md mx-auto mb-6 sm:mb-8 text-sm sm:text-base text-gray-600 px-4">
                            Todavía no has creado ninguna subcategoría.
                            Las subcategorías te ayudan a organizar productos dentro de cada categoría.</p>
                        <a href="{{ route('admin.subcategories.create') }}"
                            class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 lg:px-8 lg:py-3 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 rounded-lg sm:rounded-xl hover:shadow-xl hover:scale-105">
                            <i class="mr-2 sm:mr-3 text-white fas fa-plus text-sm sm:text-base"></i>
                            <span class="text-white text-sm sm:text-base">Crear Primera Subcategoría</span>
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
                    btn.classList.remove('bg-primary-600', 'text-white', 'shadow-sm');
                    btn.classList.add('text-gray-600', 'hover:text-gray-900');
                });

                const selectedBtn = document.getElementById(viewType + '-btn');
                selectedBtn.classList.add('bg-primary-600', 'text-white', 'shadow-sm');
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