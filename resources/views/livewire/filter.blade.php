<div class="py-12 bg-white rounded-lg shadow-md border border-secondary-100">
    {{-- Componente de filtro de opciones y características --}}
    <x-container class="px-4 md:flex">
        {{-- Filtro de opciones --}}
        @if (count($options))

        <aside class="mb-8 md:mr-8 md:flex-shrink-0 md:w-52 md:mb-0">
            <ul class="space-y-4">
                @foreach ($options as $option)
                <li class="mb-2" x-data="{ open: false }"> {{-- x-data: Inicializa el estado local de Alpine.js para
                    este elemento, aquí 'open' controla si el panel está expandido o no --}}
                    <button x-on:click="open = !open" {{-- x-on:click: Escucha el evento click y alterna el valor
                        de 'open' (abre/cierra el panel) --}}
                        class="flex items-center justify-between w-full px-4 py-2 text-left text-slate-700 bg-secondary-50 rounded-md hover:bg-secondary-100 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:ring-opacity-50 transition-colors">
                        <span class="font-medium">{{ $option['name'] }}</span>
                        <i class="ml-2 fas fa-chevron-down text-coral-500"
                            x-bind:class="{ 'rotate-180': open, 'rotate-0': !open }" {{-- x-bind:class: Cambia la clase
                            del ícono según el estado de 'open' para rotar la flecha --}}
                            x-transition:enter="transition ease-out duration-300" {{-- x-transition:enter: Define la
                            animación al mostrar el panel --}}
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-200" {{-- x-transition:leave: Define la
                            animación al ocultar el panel --}}
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"></i>
                    </button>
                    <ul class="mt-2 space-y-2" x-show="open" {{-- x-show: Muestra u oculta este elemento según el valor
                        de 'open' --}} x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2">
                        @foreach ($option['features'] as $feature)
                        <li class="ml-4">
                            <label
                                class="inline-flex items-center text-sm text-slate-600 hover:text-slate-800 cursor-pointer transition-colors">
                                <x-checkbox value="{{ $feature['id'] }}" wire:model.live="selected_features"
                                    class="mr-2 text-coral-500 bg-white border-secondary-300 rounded focus:ring-coral-400 focus:ring-opacity-50" />
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

            <div
                class="flex items-center justify-between mb-6 p-4 bg-secondary-50/50 rounded-xl border border-secondary-200/50">
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-semibold text-primary-700 flex items-center">
                        <i class="fas fa-sort mr-2 text-coral-500"></i>
                        Ordenar por:
                    </span>
                    <x-select wire:model.live="orderBy"
                        class="border-secondary-300 focus:border-coral-400 focus:ring-coral-400">
                        <option value="1">Seleccionar</option>
                        <option value="2">Precio: Mayor a Menor</option>
                        <option value="3">Precio: Menor a Mayor</option>
                    </x-select>
                </div>

                @if ($search)
                <div
                    class="flex items-center px-4 py-2 text-sm text-primary-800 bg-secondary-100/80 border border-secondary-300/50 rounded-xl backdrop-blur-sm">
                    <i class="mr-2 fas fa-search text-coral-500"></i>
                    <span class="mr-3">Buscando: "<strong class="text-coral-600">{{ $search }}</strong>"</span>
                    <button wire:click="clearSearch"
                        class="flex items-center justify-center w-6 h-6 text-coral-600 transition-all duration-200 rounded-full hover:text-coral-800 hover:bg-coral-100"
                        title="Limpiar búsqueda">
                        <i class="text-xs fas fa-times"></i>
                    </button>
                </div>
                @endif
            </div>

            <hr class="my-6 border-secondary-200">
            @if (count($products))

            <div
                class="grid grid-cols-1 gap-8 transition-all duration-300 ease-out sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                <article
                    class="flex flex-col h-full overflow-hidden transition-all duration-300 bg-white/90 backdrop-blur-sm border border-secondary-200 shadow-lg product-card group rounded-2xl hover:shadow-2xl hover:border-coral-200">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                            class="object-cover w-full h-64 transition-transform duration-500 group-hover:scale-110">

                        {{-- Badge de ofertas o nuevo --}}
                        @if(isset($product->is_on_valid_offer) && $product->is_on_valid_offer)
                        <div
                            class="absolute px-3 py-1 text-sm font-semibold text-white bg-coral-500 rounded-full top-4 left-4 animate-pulse shadow-lg">
                            <i class="mr-1 fas fa-fire"></i>{{ $product->discount_percentage ?? 15 }}% OFF
                        </div>
                        @else
                        <div
                            class="absolute px-3 py-1 text-sm font-bold text-white bg-gradient-to-r from-coral-500 to-coral-600 rounded-full shadow-lg top-4 left-4">
                            <i class="mr-1 fas fa-fish"></i>Fresco
                        </div>
                        @endif

                        {{-- Stock badge --}}
                        @if($product->stock > 0)
                        <div
                            class="absolute top-4 right-4 px-2 py-1 bg-secondary-500/90 text-white text-xs font-medium rounded-full backdrop-blur-sm">
                            <i class="fas fa-check mr-1"></i>{{ $product->stock }} disponibles
                        </div>
                        @else
                        <div
                            class="absolute top-4 right-4 px-2 py-1 bg-slate-500/90 text-white text-xs font-medium rounded-full backdrop-blur-sm">
                            <i class="fas fa-times mr-1"></i>Agotado
                        </div>
                        @endif

                        <div
                            class="absolute transition-all duration-300 transform translate-y-2 opacity-0 top-12 right-4 group-hover:opacity-100 group-hover:translate-y-0">
                            <button
                                class="p-3 transition-all duration-200 rounded-full shadow-lg bg-white/90 backdrop-blur-sm hover:bg-white hover:scale-110">
                                <i
                                    class="text-secondary-600 transition-colors duration-200 fas fa-heart hover:text-coral-500"></i>
                            </button>
                        </div>

                        {{-- Quick view overlay --}}
                        <div
                            class="absolute inset-0 flex items-center justify-center transition-all duration-300 opacity-0 bg-primary-900/60 group-hover:opacity-100">
                            <a href="{{ route('products.show', $product) }}"
                                class="px-6 py-3 font-semibold text-primary-800 transition-all duration-200 transform scale-90 bg-white rounded-full hover:bg-cream-50 group-hover:scale-100 shadow-xl">
                                <i class="mr-2 fas fa-eye"></i>Vista Rápida
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col flex-grow p-6">
                        <div class="mb-2">
                            <span
                                class="px-2 py-1 text-sm font-medium text-secondary-700 rounded-full bg-secondary-100">
                                {{ $product->subcategory->category->family->name ?? 'Producto' }}
                            </span>
                        </div>

                        <h3
                            class="text-lg font-bold text-primary-800 mb-3 line-clamp-2 min-h-[56px] group-hover:text-coral-600 transition-colors duration-200">
                            {{ $product->name }}
                        </h3>

                        @if($product->description)
                        <p class="flex-grow mb-4 text-sm leading-relaxed text-secondary-600 line-clamp-2">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                        @endif

                        <div class="flex items-center justify-between mb-6">
                            {{-- Precios con ofertas --}}
                            @if(isset($product->is_on_valid_offer) && $product->is_on_valid_offer)
                            <div class="flex flex-col">
                                <div class="text-2xl font-bold text-coral-600">
                                    ${{ number_format($product->current_price ?? $product->price, 2) }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-lg text-secondary-500 line-through">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold text-white bg-coral-500 rounded-full">
                                        -${{ number_format(($product->price - ($product->current_price ??
                                        $product->price)), 2) }}
                                    </span>
                                </div>
                            </div>
                            @else
                            <div class="text-2xl font-bold text-primary-600">
                                ${{ number_format($product->price, 2) }}
                            </div>
                            @endif

                            <div class="flex items-center text-yellow-400">
                                @for($i = 1; $i <= 5; $i++) <i class="text-sm fas fa-star"></i>
                                    @endfor
                                    <span class="ml-2 text-sm text-secondary-500">(4.8)</span>
                            </div>
                        </div>

                        <!-- Botones fijos en la parte inferior -->
                        <div class="flex gap-3 mt-auto">
                            <x-link href="{{ route('products.show', $product) }}"
                                class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center py-3 px-4 rounded-xl font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                name="Ver Detalles">
                            </x-link>
                            <livewire:quick-add-to-cart :product="$product" :key="'filter-cart-'.$product->id" />
                        </div>
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
            <div
                class="flex flex-col items-center justify-center h-64 text-center bg-cream-50/50 rounded-xl border border-secondary-200/30">
                @if ($search)
                {{-- Estado: Búsqueda sin resultados --}}
                <div class="w-20 h-20 bg-coral-100 rounded-full flex items-center justify-center mb-4">
                    <i class="text-3xl text-coral-500 fas fa-search"></i>
                </div>
                <p class="mb-2 text-xl font-bold text-primary-800">No se encontraron productos</p>
                <p class="mb-6 text-secondary-600 max-w-md">No hay productos que coincidan con tu búsqueda
                    "<strong class="text-coral-600">{{ $search }}</strong>"</p>
                <div class="space-y-3">
                    <button wire:click="clearSearch"
                        class="px-6 py-3 text-white bg-gradient-to-r from-coral-500 to-coral-600 rounded-xl hover:from-coral-600 hover:to-coral-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="mr-2 fas fa-times"></i>
                        Limpiar búsqueda
                    </button>
                    <p class="text-sm text-secondary-500">O intenta con otros términos de búsqueda</p>
                </div>
                @else
                {{-- Estado: No hay productos en general --}}
                <div class="w-20 h-20 bg-secondary-100 rounded-full flex items-center justify-center mb-4">
                    <i class="text-3xl text-secondary-500 fas fa-box-open"></i>
                </div>
                <p class="text-xl font-bold text-primary-800">No hay productos disponibles</p>
                <p class="text-secondary-600 mt-2">Próximamente tendremos nuevos productos frescos para ti.</p>
                @endif
            </div>
            @endif

        </div>
    </x-container>
</div>