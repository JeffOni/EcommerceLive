<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorias',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-cyan-50 relative overflow-hidden">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-teal-200/30 to-cyan-300/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-cyan-200/30 to-teal-300/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-teal-100/40 to-cyan-100/40 rounded-full blur-2xl">
            </div>
        </div>

        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.categories.create') }}" type="primary" name="Nueva Categoría" />
            </x-slot>

            <!-- Contenedor principal con backdrop blur -->
            <div
                class="backdrop-blur-sm bg-white/70 rounded-3xl shadow-2xl border border-white/20 mx-4 my-8 overflow-hidden">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                <i class="fas fa-tags text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Gestión de Categorías</h2>
                                <p class="text-teal-100 text-sm">Administra las categorías organizadas por familias</p>
                            </div>
                        </div>
                        <div class="text-white/80 text-sm">
                            <i class="fas fa-list mr-1"></i>
                            {{ $categories->total() ?? $categories->count() }} categorías
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
                                    class="view-toggle px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 bg-teal-600 text-white shadow-sm">
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
                                <input type="text" id="search-input" placeholder="Buscar categorías..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm w-64">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <select id="items-per-page"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
                                <option value="12">12 por página</option>
                                <option value="24">24 por página</option>
                                <option value="48">48 por página</option>
                                <option value="96">96 por página</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    @if ($categories->count())
                        <!-- Vista de tarjetas -->
                        <div id="cards-view" class="view-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                                @foreach ($categories as $category)
                                    <div
                                        class="group relative bg-gradient-to-br from-white to-teal-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-teal-100 overflow-hidden">
                                        <!-- Badge ID -->
                                        <div class="absolute top-3 left-3">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                #{{ $category->id }}
                                            </span>
                                        </div>

                                        <!-- Badge Familia -->
                                        <div class="absolute top-3 right-3">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                                <i class="fas fa-layer-group mr-1"></i>
                                                {{ $category->family->name }}
                                            </span>
                                        </div>

                                        <!-- Contenido principal -->
                                        <div class="p-6 pt-16">
                                            <!-- Icono central -->
                                            <div class="flex justify-center mb-4">
                                                <div
                                                    class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                    <i class="fas fa-tags text-white text-2xl"></i>
                                                </div>
                                            </div>

                                            <!-- Nombre de la categoría -->
                                            <h3
                                                class="text-lg font-semibold text-gray-800 text-center mb-2 group-hover:text-teal-600 transition-colors duration-300">
                                                {{ $category->name }}
                                            </h3>

                                            <!-- Información de la familia -->
                                            <p class="text-sm text-gray-500 text-center mb-4">
                                                Familia: {{ $category->family->name }}
                                            </p>

                                            <!-- Botones de acción -->
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                    class="flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                                                    <i class="fas fa-edit mr-2 text-white"></i>
                                                    <span class="text-white text-sm font-medium">Editar</span>
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Efecto decorativo -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-teal-500/10 to-cyan-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Vista de tabla moderna -->
                        <div id="table-view" class="view-content hidden">
                            <div class="overflow-hidden bg-white rounded-xl shadow-lg border border-gray-200">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                            <tr>
                                                <th
                                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    <div class="flex items-center space-x-1">
                                                        <span>ID</span>
                                                        <i
                                                            class="fas fa-sort text-gray-400 cursor-pointer hover:text-gray-600"></i>
                                                    </div>
                                                </th>
                                                <th
                                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    <div class="flex items-center space-x-1">
                                                        <span>Categoría</span>
                                                        <i
                                                            class="fas fa-sort text-gray-400 cursor-pointer hover:text-gray-600"></i>
                                                    </div>
                                                </th>
                                                <th
                                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Familia</th>
                                                <th
                                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($categories as $category)
                                                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                            #{{ $category->id }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-12 w-12">
                                                                <div
                                                                    class="h-12 w-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                                                                    <i class="fas fa-tags text-white"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div
                                                                    class="text-sm font-semibold text-gray-900 group-hover:text-teal-600 transition-colors duration-200">
                                                                    {{ $category->name }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                                            <i class="fas fa-layer-group mr-1"></i>
                                                            {{ $category->family->name }}
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                                                            <i class="fas fa-edit mr-2"></i>
                                                            Editar
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Paginación mejorada -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                            {{ $categories->links() }}
                        </div>
                    @else
                        <!-- Estado vacío mejorado -->
                        <div class="text-center py-16">
                            <div
                                class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-full mb-6">
                                <i class="fas fa-tags text-4xl text-teal-500"></i>
                            </div>
                            <h3 class="text-2xl font-semibold text-gray-800 mb-4">No hay categorías registradas</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">Todavía no has creado ninguna categoría. Las
                                categorías te ayudan a organizar productos dentro de cada familia.</p>
                            <a href="{{ route('admin.categories.create') }}"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-plus mr-3 text-white"></i>
                                <span class="text-white">Crear Primera Categoría</span>
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
                    btn.classList.remove('bg-teal-600', 'text-white', 'shadow-sm');
                    btn.classList.add('text-gray-600', 'hover:text-gray-900');
                });

                const selectedBtn = document.getElementById(viewType + '-btn');
                selectedBtn.classList.add('bg-teal-600', 'text-white', 'shadow-sm');
                selectedBtn.classList.remove('text-gray-600', 'hover:text-gray-900');

                // Guardar preferencia
                localStorage.setItem('admin_categories_view', viewType);
                currentView = viewType;
            }

            // Restaurar vista guardada
            document.addEventListener('DOMContentLoaded', function() {
                const savedView = localStorage.getItem('admin_categories_view');
                if (savedView && savedView !== 'cards') {
                    toggleView(savedView);
                }
            });

            // Búsqueda en tiempo real
            let searchTimeout;
            document.getElementById('search-input').addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = e.target.value.toLowerCase();
                    console.log('Búsqueda categorías:', searchTerm);
                }, 300);
            });

            // Cambio de items por página
            document.getElementById('items-per-page').addEventListener('change', function(e) {
                const itemsPerPage = e.target.value;
                console.log('Items por página:', itemsPerPage);
            });
        </script>
    @endpush

</x-admin-layout>
