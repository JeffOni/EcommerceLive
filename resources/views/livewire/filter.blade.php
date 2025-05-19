<div class="py-12 bg-white rounded-lg shadow-md">
    {{-- Componente de filtro de opciones y características --}}
    <x-container class="flex px-4">
        <aside class="flex-shrink-0 mr-8 w-52">
            <ul class="space-y-4">
                @foreach ($options as $option)
                    <li class="mb-2" x-data="{ open: false }"> {{-- x-data: Inicializa el estado local de Alpine.js para este elemento, aquí 'open' controla si el panel está expandido o no --}}
                        <button
                            x-on:click="open = !open" {{-- x-on:click: Escucha el evento click y alterna el valor de 'open' (abre/cierra el panel) --}}
                            class="flex items-center justify-between w-full px-4 py-2 text-left text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring focus:ring-blue-500">
                            <span>{{ $option->name }}</span>
                            <i class="ml-2 fas fa-chevron-down"
                                x-bind:class="{'rotate-180': open, 'rotate-0': !open}" {{-- x-bind:class: Cambia la clase del ícono según el estado de 'open' para rotar la flecha --}}
                                x-transition:enter="transition ease-out duration-300" {{-- x-transition:enter: Define la animación al mostrar el panel --}}
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-200" {{-- x-transition:leave: Define la animación al ocultar el panel --}}
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                            ></i>
                        </button>
                        <ul
                            class="mt-2 space-y-2"
                            x-show="open" {{-- x-show: Muestra u oculta este elemento según el valor de 'open' --}}
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                        >
                            @foreach ($option->features as $feature)
                                <li class="ml-4">
                                    <label class="inline-flex items-center text-sm text-gray-700">
                                        <x-checkbox class="mr-2"/>
                                        {{ $feature->description }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="flex-1">

        </div>
    </x-container>
</div>
