<x-admin-layout>
    <x-slot name="title">Gestión de Ofertas</x-slot>

    <div class="space-y-4 sm:space-y-6">
        {{-- Header responsive --}}
        <div class="p-3 rounded-lg shadow-sm bg-gradient-to-r from-primary-900 to-secondary-500 sm:p-4 lg:p-6">
            <div
                class="flex flex-col mb-4 space-y-4 sm:flex-row sm:justify-between sm:items-center sm:space-y-0 sm:mb-6">
                <div class="flex-1 min-w-0">
                    <h1 class="text-lg font-bold text-white truncate sm:text-xl lg:text-2xl">🎯 Gestión de Ofertas</h1>
                    <p class="text-xs truncate sm:text-sm text-secondary-100">Administra las ofertas y descuentos de tus
                        productos</p>
                </div>
                <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                    <form action="{{ route('admin.offers.clean-expired') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="w-full px-3 py-2 text-sm text-white transition-colors rounded-lg sm:w-auto bg-coral-600 sm:px-4 hover:bg-coral-700"
                            onclick="return confirm('¿Estás seguro de limpiar todas las ofertas vencidas?')">
                            🧹 <span class="hidden sm:inline">Limpiar Vencidas</span><span
                                class="sm:hidden">Limpiar</span>
                        </button>
                    </form>
                    <a href="{{ route('admin.offers.create') }}"
                        class="w-full px-3 py-2 text-sm text-center text-white transition-colors rounded-lg sm:w-auto bg-primary-600 sm:px-4 hover:bg-primary-700">
                        ➕ <span class="hidden sm:inline">Nueva Oferta</span><span class="sm:hidden">Nueva</span>
                    </a>
                </div>
            </div>

            {{-- Estadísticas responsive --}}
            <div class="grid grid-cols-2 gap-2 lg:grid-cols-4 sm:gap-4">
                <div class="p-3 rounded-lg bg-blue-50 sm:p-4">
                    <div class="flex items-center">
                        <div class="bg-blue-500 rounded-full p-1.5 sm:p-2 mr-2 sm:mr-3 flex-shrink-0">
                            <i class="text-xs text-white fas fa-tags sm:text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-blue-600 truncate sm:text-sm">Total Ofertas</p>
                            <p class="text-lg font-bold text-blue-900 sm:text-2xl">{{ $stats['total_offers'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-lg bg-green-50 sm:p-4">
                    <div class="flex items-center">
                        <div class="bg-green-500 rounded-full p-1.5 sm:p-2 mr-2 sm:mr-3 flex-shrink-0">
                            <i class="text-xs text-white fas fa-check-circle sm:text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-green-600 truncate sm:text-sm">Ofertas Activas</p>
                            <p class="text-lg font-bold text-green-900 sm:text-2xl">{{ $stats['active_offers'] }}</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-lg bg-red-50 sm:p-4">
                        <div class="flex items-center">
                            <div class="bg-red-500 rounded-full p-1.5 sm:p-2 mr-2 sm:mr-3 flex-shrink-0">
                                <i class="text-xs text-white fas fa-clock sm:text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-red-600 truncate sm:text-sm">Ofertas Vencidas</p>
                                <p class="text-lg font-bold text-red-900 sm:text-2xl">{{ $stats['expired_offers'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-lg bg-purple-50 sm:p-4">
                        <div class="flex items-center">
                            <div class="bg-purple-500 rounded-full p-1.5 sm:p-2 mr-2 sm:mr-3 flex-shrink-0">
                                <i class="text-xs text-white fas fa-dollar-sign sm:text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-purple-600 truncate sm:text-sm">Ahorros Totales</p>
                                <p class="text-base font-bold text-purple-900 sm:text-lg lg:text-2xl">${{
                                    number_format($stats['total_savings'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de herramientas con controles de vista -->
            <div class="px-3 py-3 mt-4 bg-white border-b border-gray-200 rounded-lg sm:px-6 sm:py-4">
                <div
                    class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
                    <!-- Controles de vista -->
                    <div class="flex items-center w-full space-x-3 sm:space-x-4 sm:w-auto">
                        <span class="flex-shrink-0 text-xs font-medium text-gray-700 sm:text-sm">Vista:</span>
                        <div class="flex flex-1 p-1 bg-gray-100 rounded-lg sm:flex-initial">
                            <button onclick="toggleView('cards')" id="cards-btn"
                                class="flex-1 px-3 py-2 text-xs font-medium text-white transition-all duration-200 rounded-md shadow-sm sm:px-4 sm:text-sm bg-primary-600 view-toggle sm:flex-initial">
                                <i class="mr-1 sm:mr-2 fas fa-th-large"></i><span
                                    class="hidden sm:inline">Tarjetas</span><span class="sm:hidden">Cards</span>
                            </button>
                            <button onclick="toggleView('table')" id="table-btn"
                                class="flex-1 px-3 py-2 text-xs font-medium text-gray-600 transition-all duration-200 rounded-md sm:px-4 sm:text-sm view-toggle hover:text-gray-900 sm:flex-initial">
                                <i class="mr-1 sm:mr-2 fas fa-table"></i><span
                                    class="hidden sm:inline">Tabla</span><span class="sm:hidden">Table</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filtros y búsqueda -->
                    <div
                        class="flex flex-col items-start w-full space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4 sm:w-auto">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="search-input" placeholder="Buscar ofertas..."
                                class="w-full py-2 pl-8 pr-4 text-xs border border-gray-300 rounded-lg sm:w-48 lg:w-64 sm:pl-10 sm:text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <i
                                class="absolute text-gray-400 fas fa-search left-2 sm:left-3 top-2.5 sm:top-3 text-xs sm:text-sm"></i>
                        </div>
                        <select id="items-per-page"
                            class="w-full px-2 py-2 text-xs border border-gray-300 rounded-lg sm:px-3 sm:text-sm focus:ring-2 focus:ring-primary-500 sm:w-auto">
                            <option value="12">12 por página</option>
                            <option value="24">24 por página</option>
                            <option value="48">48 por página</option>
                            <option value="96">96 por página</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Contenido responsive: tarjetas en móvil, tabla en desktop --}}
            <div class="overflow-hidden bg-white rounded-lg shadow-sm" id="offers-container">
                <div class="px-3 py-3 border-b border-gray-200 sm:px-6 sm:py-4">
                    <h2 class="text-base font-semibold text-gray-900 sm:text-lg">Productos con Ofertas</h2>
                </div>

                @if($products->isEmpty())
                <div class="py-8 text-center sm:py-12">
                    <div
                        class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full sm:w-16 sm:h-16 sm:mb-4">
                        <i class="text-lg text-gray-400 fas fa-tags sm:text-2xl"></i>
                    </div>
                    <h3 class="mb-2 text-base font-medium text-gray-900 sm:text-lg">No hay ofertas activas</h3>
                    <p class="px-4 mb-4 text-sm text-gray-600 sm:text-base sm:mb-6">Comienza creando tu primera oferta
                        para productos</p>
                    <a href="{{ route('admin.offers.create') }}"
                        class="px-3 py-2 text-sm text-white transition-colors bg-blue-600 rounded-lg sm:px-4 hover:bg-blue-700 sm:text-base">
                        ➕ Crear Primera Oferta
                    </a>
                </div>
                @else

                {{-- Vista de tarjetas para móviles --}}
                <div class="mb-6 view-content" id="cards-view">
                    <div class="divide-y divide-gray-200">
                        @foreach($products as $product)
                        <div class="p-4 transition-colors hover:bg-gray-50">
                            {{-- Header de la tarjeta --}}
                            <div class="flex items-start mb-3 space-x-3">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                    class="flex-shrink-0 object-cover w-16 h-16 rounded-lg"
                                    onerror="this.src='https://via.placeholder.com/64x64/e5e7eb/9ca3af?text=IMG'">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                                    <p class="text-xs text-gray-500 truncate">{{
                                        $product->subcategory->category->family->name ?? 'Sin categoría' }}</p>
                                    {{-- Estado --}}
                                    <div class="mt-1">
                                        @if($product->is_on_valid_offer)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Activa
                                        </span>
                                        @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ❌ Vencida
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Información de la oferta --}}
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div class="p-2 rounded-lg bg-blue-50">
                                    <p class="text-xs font-medium text-blue-600">Oferta</p>
                                    <p class="text-sm font-semibold text-blue-900 truncate">{{ $product->offer_name ??
                                        'Sin nombre' }}</p>
                                    <p class="text-xs font-bold text-red-600">{{ $product->offer_percentage }}% OFF</p>
                                </div>
                                <div class="p-2 rounded-lg bg-green-50">
                                    <p class="text-xs font-medium text-green-600">Precios</p>
                                    <p class="text-xs text-gray-500 line-through">${{ number_format($product->price, 2)
                                        }}</p>
                                    <p class="text-sm font-bold text-red-600">${{ number_format($product->offer_price,
                                        2) }}</p>
                                    <p class="text-xs text-green-600">Ahorra: ${{ number_format($product->price -
                                        $product->offer_price, 2) }}</p>
                                </div>
                            </div>

                            {{-- Vigencia --}}
                            <div class="p-2 mb-3 rounded-lg bg-purple-50">
                                <p class="mb-1 text-xs font-medium text-purple-600">Vigencia</p>
                                <div class="flex justify-between text-xs text-gray-700">
                                    <span>📅 {{ \Carbon\Carbon::parse($product->offer_starts_at)->format('d/m/Y')
                                        }}</span>
                                    <span>🔚 {{ \Carbon\Carbon::parse($product->offer_ends_at)->format('d/m/Y')
                                        }}</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    @if(\Carbon\Carbon::parse($product->offer_ends_at)->isPast())
                                    Vencida hace {{ \Carbon\Carbon::parse($product->offer_ends_at)->diffForHumans() }}
                                    @else
                                    Vence {{ \Carbon\Carbon::parse($product->offer_ends_at)->diffForHumans() }}
                                    @endif
                                </p>
                            </div>

                            {{-- Acciones --}}
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.offers.edit', $product) }}"
                                    class="p-2 text-blue-700 transition-colors bg-blue-100 rounded-lg hover:bg-blue-200"
                                    title="Editar">
                                    <i class="text-sm fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.offers.destroy', $product) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-700 transition-colors bg-red-100 rounded-lg hover:bg-red-200"
                                        title="Eliminar"
                                        onclick="return confirm('¿Estás seguro de eliminar esta oferta?')">
                                        <i class="text-sm fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Vista de tabla para desktop --}}
                <div class="hidden mt-6 view-content" id="table-view">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Producto
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Oferta
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Precios
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Vigencia
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Estado
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                                class="object-cover w-12 h-12 mr-4 rounded-lg"
                                                onerror="this.src='https://via.placeholder.com/48x48/e5e7eb/9ca3af?text=IMG'">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{
                                                    Str::limit($product->name,
                                                    30)
                                                    }}</div>
                                                <div class="text-sm text-gray-500">{{
                                                    $product->subcategory->category->family->name ?? 'Sin categoría' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->offer_name ?? 'Sin
                                            nombre'
                                            }}</div>
                                        <div class="text-sm font-semibold text-red-600">{{ $product->offer_percentage
                                            }}%
                                            OFF
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 line-through">${{
                                            number_format($product->price,
                                            2) }}
                                        </div>
                                        <div class="text-sm font-bold text-red-600">${{
                                            number_format($product->offer_price,
                                            2)
                                            }}</div>
                                        <div class="text-xs text-green-600">Ahorra: ${{ number_format($product->price -
                                            $product->offer_price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div>📅 {{ \Carbon\Carbon::parse($product->offer_starts_at)->format('d/m/Y') }}
                                        </div>
                                        <div>🔚 {{ \Carbon\Carbon::parse($product->offer_ends_at)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if(\Carbon\Carbon::parse($product->offer_ends_at)->isPast())
                                            Vencida hace {{
                                            \Carbon\Carbon::parse($product->offer_ends_at)->diffForHumans()
                                            }}
                                            @else
                                            Vence {{ \Carbon\Carbon::parse($product->offer_ends_at)->diffForHumans() }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->is_on_valid_offer)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Activa
                                        </span>
                                        @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ❌ Vencida
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.offers.edit', $product) }}"
                                                class="text-blue-600 hover:text-blue-900" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.offers.destroy', $product) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    title="Eliminar"
                                                    onclick="return confirm('¿Estás seguro de eliminar esta oferta?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($products->hasPages())
                    <div class="px-4 py-4 border-t border-gray-200 sm:px-6">
                        {{ $products->links() }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            {{-- JavaScript para funcionalidad de vista dual --}}
            @push('js')
            <script>
                // Estado de la vista actual
        let currentView = 'cards';
        let isLoading = false;

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
            localStorage.setItem('admin_offers_view', viewType);
            currentView = viewType;
        }

        // Función para cargar datos
        function loadData() {
            if (isLoading) return;

            isLoading = true;
            const searchInput = document.getElementById('search-input');
            const itemsPerPage = document.getElementById('items-per-page');
            const container = document.getElementById('offers-container');

            // Mostrar indicador de carga
            container.style.opacity = '0.6';

            const params = new URLSearchParams();
            if (searchInput.value.trim()) {
                params.append('search', searchInput.value.trim());
            }
            params.append('per_page', itemsPerPage.value);

            const url = `{{ route('admin.offers.index') }}?${params.toString()}`;

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Extraer solo el contenido del contenedor
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.querySelector('#offers-container');
                    
                    if (newContent) {
                        container.innerHTML = newContent.innerHTML;

                        // Restaurar vista seleccionada
                        const savedView = localStorage.getItem('admin_offers_view') || 'cards';
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
            const savedView = localStorage.getItem('admin_offers_view') || 'cards';
            if (savedView !== 'cards') {
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
            }, 500);
        });

        // Cambio de items por página
        document.getElementById('items-per-page').addEventListener('change', function(e) {
            loadData();
        });
            </script>
            @endpush
</x-admin-layout>