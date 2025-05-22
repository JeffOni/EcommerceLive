<div x-data="{ sidebarOpen: false }" x-on:click.away="sidebarOpen = false" x-on:keydown.escape.window="sidebarOpen = false"
    x-bind:class="{ 'overflow-hidden': sidebarOpen }">

    {{-- Este componente representa un contenedor que puede ser utilizado para agrupar otros elementos --}}
    {{-- Se utiliza para mantener la consistencia de diseño y facilitar el uso de clases comunes --}}
    {{-- Este componente representa un campo de entrada de texto --}}
    {{-- Se utiliza para buscar productos en la tienda --}}
    {{-- El modelo está enlazado a la propiedad search del componente Livewire --}}
    {{-- Se utilizan eventos para manejar el comportamiento del campo de búsqueda --}}
    {{-- Because she competes with no one, no one can compete with her. --}}
    {{-- header --}}

    <header class="bg-blue-900 shadow-lg">

        <x-container class="px-4 py-4">
            <div class="flex items-center justify-between space-x-8">

                <button class="text-2xl transition duration-300 ease-in-out transform hover:scale-110 focus:outline-none"
                    x-on:click="sidebarOpen = !sidebarOpen">
                    {{-- Icono de menu --}}
                    <i class="text-white fas fa-bars"></i>
                </button>

                {{-- Logo de la tienda --}}

                <h1 class="text-white">

                    <a href="/"
                        class="inline-flex flex-col items-end transition duration-300 ease-in-out hover:text-blue-200">

                        {{-- Logo de la tienda --}}

                        <span class="text-2xl font-bold leading-5 md:text-4xl">
                            Pescaderia
                        </span>

                        <span class="text-xs font-bold">
                            Tienda Online
                        </span>

                    </a>
                </h1>

                {{-- searcher --}}

                <div class="flex-1 hidden md:block">
                    <div class="relative">
                        <input
                            class="w-full pl-10 border-2 border-blue-500 rounded-full focus:border-blue-600 focus:ring focus:ring-blue-300 focus:ring-opacity-50"
                            type="text" placeholder="Buscar productos"
                            oninput="search(this.value)"
                            onkeydown="if(event.key==='Escape'){this.value='';search('');}"
                        />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="text-gray-500 fas fa-search"></i>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}

                <div class="flex items-center space-x-4">

                    <x-dropdown>
                        <x-slot name="trigger">

                            @auth
                                <button
                                    class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300">
                                    <img class="object-cover rounded-full size-8"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <button
                                    class="relative p-2 text-lg transition duration-300 ease-in-out transform md:text-3xl hover:scale-110 hover:text-blue-200 focus:outline-none">
                                    <i class="text-white fas fa-user"></i>
                                </button>
                            @endauth

                        </x-slot>
                        <x-slot name="content">

                            @guest
                                <div class="px-4 py-2">

                                    <div class="flex justify-center">

                                        <x-link href="{{ route('login') }}" name="Iniciar Sesión" />

                                    </div>

                                    <p class="mt-4 text-sm text-center text-gray-500">
                                        ¿No tienes cuenta? <a href="{{ route('register') }}"
                                            class="font-semibold text-blue-600 hover:text-blue-500 hover:underline">Regístrate</a>

                                    </p>

                                </div>
                            @else
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>

                            @endguest

                        </x-slot>

                    </x-dropdown>
                    {{-- Este componente representa un botón --}}
                    {{-- Se utiliza para mostrar el icono de usuario --}}
                    {{-- El icono es un icono de Font Awesome --}}


                    <button
                        class="relative p-2 text-lg transition duration-300 ease-in-out transform md:text-3xl hover:scale-110 hover:text-blue-200 focus:outline-none">
                        <i class="text-white fas fa-shopping-cart"></i>
                        <span
                            class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full">0</span>
                    </button>

                </div>

            </div>

            {{-- Buscador en móvil (debajo de todo) --}}
            <div class="mt-4 md:hidden">
                <div class="relative">
                    <x-input
                        class="w-full pl-10 border-2 border-blue-500 rounded-full focus:border-blue-600 focus:ring focus:ring-blue-300 focus:ring-opacity-50"
                        type="text" placeholder="Buscar productos"
                        wire:model.debounce.500ms="search"
                        wire:keydown.escape="clearSearch" />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="text-gray-500 fas fa-search"></i>
                    </div>
                </div>
            </div>

        </x-container>

    </header>

    {{-- Sidebar y fondo oscuro juntos para capturar el click correctamente --}}
    {{--
        Cambios realizados para solucionar el cierre del sidebar al hacer clic en el fondo oscuro:
        - Se reestructuró el contenedor del sidebar y el fondo oscuro para que ambos estén dentro de un mismo div fixed.
        - El fondo oscuro (div con bg-gray-900/50) tiene z-index:40 para permitir que reciba clics en toda su área.
        - Se usa pointer-events-none en el contenedor flex y pointer-events-auto solo en los elementos interactivos.
        - El evento x-on:click="sidebarOpen = false" en el fondo oscuro permite cerrar el sidebar al hacer clic fuera de él.
    --}}
    <template x-if="sidebarOpen">
        <div class="fixed inset-0 z-40 flex">
            <!-- Fondo oscuro: cubre toda la pantalla y cierra el sidebar al hacer clic -->
            <div class="absolute inset-0 bg-gray-900/50" x-on:click="sidebarOpen = false"></div>
            <!-- Contenedor flex para sidebar y panel derecho -->
            <div class="relative z-50 flex w-full h-full pointer-events-none">
                <!-- Sidebar principal: pointer-events-auto para permitir interacción -->
                <div
                    class="w-screen h-screen transition-all duration-300 bg-white shadow-lg pointer-events-auto md:w-80">
                    <div class="px-4 py-3 font-semibold text-white bg-blue-400">
                        <div class="flex items-center justify-between px-2">
                            <h1 class="text-2xl font-bold">Hola Peresona</h1>
                            <button x-on:click="sidebarOpen = !sidebarOpen">
                                <i
                                    class="p-2 text-lg transition duration-300 ease-in-out transform hover:scale-110 focus:outline-none fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="h-[calc(100vh-3.25rem)] overflow-y-auto">
                        <ul>
                            @foreach ($families as $family)
                                <li wire:mouseover="$set('familyId', {{ $family->id }})"
                                    class="flex items-center justify-between px-4 py-3 text-gray-700 transition duration-300 ease-in-out transform hover:bg-blue-100">
                                    <a href="{{ route('families.show', $family) }}"
                                        class="flex items-center justify-between w-full text-gray-700 transition duration-300 ease-in-out transform hover:text-blue-600">
                                        <span>{{ $family->name }}</span>
                                        <i class="fa-solid fa-angle-right"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Panel derecho de categorías y subcategorías: pointer-events-auto para permitir interacción -->
                <div class="w-80 xl:w-[57rem] pt-[3.25rem] hidden md:block z-30 pointer-events-auto">
                    <div class="h-[calc(100vh-3.25rem)] overflow-y-auto bg-white shadow-lg px-6 py-8">
                        <div class="flex items-center justify-between mb-8">
                            <p class="border-b-[3px] border-lime-600 text-xl pb-1 uppercase font-bold text-gray-700">
                                {{ $this->familyName }}
                            </p>
                            <x-link href="{{ route('families.show', $this->familyId) }}" name="Ver Todo" />
                        </div>
                        <ul class="grid grid-cols-1 gap-8 xl:grid-cols-3">
                            @foreach ($this->categories as $category)
                                <li wire:mouseover="">
                                    <a href="{{ route('categories.show', $category) }}" class="flex items-center justify-between text-blue-600 ">
                                        {{ $category->name }}
                                    </a>
                                    <ul class="mt-4 space-y-2">
                                        @foreach ($category->subcategories as $subcategory)
                                            <li>
                                                <a href="{{ route('subcategories.show', $subcategory) }}"
                                                    class="text-sm text-gray-700 transition duration-300 ease-in-out transform hover:text-blue-600">
                                                    {{ $subcategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <div>

    </div>
    {{-- evento js por si se quiere usar un buscador con javascript y no con livewire --}}
    @push('js')
        <script>
            function search(value) {
                Livewire.dispatch('search', {
                    search: value
                });
            }
        </script>
    @endpush
</div>
