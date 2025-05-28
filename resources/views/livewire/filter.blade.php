<div class="py-12 bg-white rounded-lg shadow-md">
    {{-- Componente de filtro de opciones y características --}}
    <x-container class="px-4 md:flex">
        {{-- Filtro de opciones --}}
        @if (count($options))

            <aside class="mb-8 md:mr-8 md:flex-shrink-0 md:w-52 md:mb-0">
                <ul class="space-y-4">
                    @foreach ($options as $option)
                        <li class="mb-2" x-data="{ open: false }"> {{-- x-data: Inicializa el estado local de Alpine.js para este elemento, aquí 'open' controla si el panel está expandido o no --}}
                            <button x-on:click="open = !open" {{-- x-on:click: Escucha el evento click y alterna el valor de 'open' (abre/cierra el panel) --}}
                                class="flex items-center justify-between w-full px-4 py-2 text-left text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring focus:ring-blue-500">
                                <span>{{ $option['name'] }}</span>
                                <i class="ml-2 fas fa-chevron-down"
                                    x-bind:class="{ 'rotate-180': open, 'rotate-0': !open }" {{-- x-bind:class: Cambia la clase del ícono según el estado de 'open' para rotar la flecha --}}
                                    x-transition:enter="transition ease-out duration-300" {{-- x-transition:enter: Define la animación al mostrar el panel --}}
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-200" {{-- x-transition:leave: Define la animación al ocultar el panel --}}
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2"></i>
                            </button>
                            <ul class="mt-2 space-y-2" x-show="open" {{-- x-show: Muestra u oculta este elemento según el valor de 'open' --}}
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2">
                                @foreach ($option['features'] as $feature)
                                    <li class="ml-4">
                                        <label class="inline-flex items-center text-sm text-gray-700">
                                            <x-checkbox value="{{ $feature['id'] }}" wire:model.live="selected_features"
                                                class="mr-2" />
                                            {{ $feature['description'] }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </aside>

        @endif


        {{-- Contenido principal --}}
        <div class="md:flex-1">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="mr-2 text-sm font-semibold text-gray-700">
                        Ordenar por:
                    </span>
                    <x-select wire:model.live="orderBy">
                        <option value="1">
                            Sellecionar
                        </option>
                        <option value="2">
                            Precio : Mayor a Menor
                        </option>
                        <option value="3">
                            Precio : Menor a Mayor
                        </option>
                    </x-select>
                </div>

                {{--
                    SECCIÓN: Indicador visual de búsqueda activa

                    Esta sección se muestra solo cuando hay una búsqueda activa ($search tiene valor)
                    Proporciona feedback visual al usuario sobre el término de búsqueda actual
                    Incluye un botón para limpiar la búsqueda que ejecuta el método clearSearch() del componente

                    Funcionalidades:
                    - Muestra el término de búsqueda actual destacado en negrita
                    - Botón de limpiar que resetea tanto el componente como los campos de navegación
                    - Estilos azules para consistencia con el diseño de la aplicación
                    - Iconos para mejor experiencia visual
                --}}
                @if ($search)
                    <div
                        class="flex items-center px-3 py-2 text-sm text-blue-800 bg-blue-100 border border-blue-200 rounded-lg">
                        <i class="mr-2 fas fa-search"></i>
                        <span class="mr-2">Buscando: "<strong>{{ $search }}</strong>"</span>
                        <button wire:click="clearSearch"
                            class="flex items-center justify-center w-5 h-5 text-blue-600 transition-colors duration-200 rounded-full hover:text-blue-800 hover:bg-blue-200"
                            title="Limpiar búsqueda">
                            <i class="text-xs fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>

            <hr class="my-4 border-gray-300">
            @if (count($products))

                <div
                    class="grid grid-cols-1 gap-6 transition-all duration-300 ease-out sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <article class="flex flex-col h-full overflow-hidden bg-white rounded-md shadow-md">
                            <div class="relative">
                                <img src="{{ asset($product->image) }}" alt="Product Image"
                                    class="object-center w-full h-48">
                                <div class="absolute top-0 left-0 px-2 py-1 text-white bg-blue-500 rounded-br-md">
                                    {{ $product->name }}
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 p-4">
                                <h2 class="text-lg font-semibold line-clamp-2 min-h-[56px] text-gray-800">
                                    {{ $product->name }}</h2>
                                {{-- <p class="mt-2 text-gray-600">{{ $product->description }}</p> --}}
                                <p class="mt-4 text-xl font-bold text-gray-800">${{ $product->price }}</p>
                                <x-link href="{{ route('products.show', $product) }}"
                                    class="block w-full mt-auto text-center" name="Ver Mas"></x-link>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Paginación --}}

                <div class="mt-8">
                    {{ $products->links() }} {{-- Renderiza los enlaces de paginación --}}
                </div>
            @else
                {{--
                    SECCIÓN: Estados de "sin resultados"

                    Maneja dos casos diferentes cuando no hay productos para mostrar:

                    1. CON BÚSQUEDA ACTIVA ($search tiene valor):
                       - Muestra mensaje específico de "no se encontraron productos"
                       - Incluye el término de búsqueda para contexto
                       - Botón para limpiar búsqueda y ver todos los productos
                       - Sugerencia para probar otros términos
                       - Icono de búsqueda para consistencia visual

                    2. SIN BÚSQUEDA (sin filtros aplicados):
                       - Mensaje genérico de "no hay productos disponibles"
                       - Icono de caja vacía para indicar inventario vacío
                       - Estilo más neutral sin opciones de acción
                --}}
                <div class="flex flex-col items-center justify-center h-64 text-center">
                    @if ($search)
                        {{-- Estado: Búsqueda sin resultados --}}
                        <i class="mb-4 text-4xl text-gray-400 fas fa-search"></i>
                        <p class="mb-2 text-lg font-semibold text-gray-600">No se encontraron productos</p>
                        <p class="mb-4 text-gray-500">No hay productos que coincidan con tu búsqueda
                            "<strong>{{ $search }}</strong>"</p>
                        <div class="space-y-2">
                            <button wire:click="clearSearch"
                                class="px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="mr-1 fas fa-times"></i>
                                Limpiar búsqueda
                            </button>
                            <p class="text-sm text-gray-500">O intenta con otros términos de búsqueda</p>
                        </div>
                    @else
                        {{-- Estado: No hay productos en general --}}
                        <i class="mb-4 text-4xl text-gray-400 fas fa-box-open"></i>
                        <p class="text-lg font-semibold text-gray-500">No hay productos disponibles.</p>
                    @endif
                </div>
            @endif

        </div>
    </x-container>
</div>
