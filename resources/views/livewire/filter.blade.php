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
                                <x-link class="block w-full mt-auto text-center" name="Ver Mas"></x-link>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Paginación --}}

                <div class="mt-8">
                    {{ $products->links() }} {{-- Renderiza los enlaces de paginación --}}
                </div>
            @else
                <div class="flex items-center justify-center h-64">
                    <p class="text-lg font-semibold text-gray-500">No hay productos disponibles.</p>
                </div>
            @endif

        </div>
    </x-container>
</div>
