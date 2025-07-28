<div x-data="{ sidebarOpen: false }" x-on:click.away="sidebarOpen = false"
    x-on:keydown.escape.window="sidebarOpen = false" x-bind:class="{ 'overflow-hidden': sidebarOpen }">

    {{-- Este componente representa un contenedor que puede ser utilizado para agrupar otros elementos --}}
    {{-- Se utiliza para mantener la consistencia de diseño y facilitar el uso de clases comunes --}}
    {{-- Este componente representa un campo de entrada de texto --}}
    {{-- Se utiliza para buscar productos en la tienda --}}
    {{-- El modelo está enlazado a la propiedad search del componente Livewire --}}
    {{-- Se utilizan eventos para manejar el comportamiento del campo de búsqueda --}}
    {{-- Because she competes with no one, no one can compete with her. --}}
    {{-- header --}}

    <header class="shadow-lg bg-primary-900">

        <x-container class="px-4 py-4">
            <div class="flex items-center justify-between space-x-8">

                <button
                    class="transition duration-300 ease-in-out transform text-2xl hover:scale-110 focus:outline-none"
                    x-on:click="sidebarOpen = !sidebarOpen">
                    {{-- Icono de menu --}}
                    <i class="text-cream-100 fas fa-bars hover:text-coral-300 transition-colors"></i>
                </button>

                {{-- Logo de la tienda --}}

                <h1 class="text-white">

                    <a href="/"
                        class="inline-flex flex-col items-end transition duration-300 ease-in-out hover:text-coral-200">

                        {{-- Logo de la tienda --}}

                        <span
                            class="text-2xl font-bold leading-5 md:text-4xl bg-gradient-to-r from-cream-100 to-coral-200 bg-clip-text text-transparent">
                            LagoFish
                        </span>

                        <span class="text-xs font-bold text-secondary-300">
                            Pescadería Online
                        </span>

                    </a>
                </h1>

                {{-- searcher --}}

                <div class="flex-1 hidden md:block">
                    <div class="relative">
                        {{--
                        CAMBIO: Campo de búsqueda para escritorio con sincronización
                        - Agregado ID único "search-desktop" para identificación en JavaScript
                        - Agregado pr-10 para espacio del botón de limpiar a la derecha
                        - Cambiado oninput a searchSync() para sincronizar con campo móvil
                        - Agregado parámetro 'desktop' para identificar origen de la búsqueda
                        --}}
                        <x-input id="search-desktop"
                            class="w-full pl-10 pr-10 border-2 rounded-full border-secondary-400 bg-white/95 focus:border-coral-400 focus:ring focus:ring-coral-400/30 focus:ring-opacity-50 text-slate-700 placeholder-slate-500"
                            type="text" placeholder="Buscar productos" oninput="searchSync(this.value, 'desktop')"
                            onkeydown="if(event.key==='Escape'){this.value='';searchSync('', 'desktop');}" />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="text-slate-500 fas fa-search"></i>
                        </div>
                        {{--
                        CAMBIO: Botón de limpiar búsqueda para escritorio
                        - Posicionado absolutamente a la derecha del input
                        - Oculto por defecto (clase 'hidden'), se muestra cuando hay texto
                        - Ejecuta clearSearch() que limpia ambos campos sincronizadamente
                        - Hover effect para mejor UX
                        - CORRECCIÓN: Separadas clases 'flex' y 'hidden' para evitar conflicto CSS
                        --}}
                        <button type="button" id="clear-search-desktop" onclick="clearSearch()"
                            class="absolute inset-y-0 right-0 items-center pr-3 text-slate-400 hover:text-coral-500 focus:outline-none hidden transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- Buttons --}}

                <div class="flex items-center space-x-4">

                    <x-dropdown>
                        <x-slot name="trigger">

                            @auth
                            <button
                                class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-coral-300 hover:border-secondary-300">
                                <img class="object-cover rounded-full size-8"
                                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                            @else
                            <button
                                class="relative p-2 text-lg transition duration-300 ease-in-out transform md:text-3xl hover:scale-110 hover:text-coral-300 focus:outline-none">
                                <i class="text-cream-100 fas fa-user"></i>
                            </button>
                            @endauth

                        </x-slot>
                        <x-slot name="content">

                            @guest
                            <div class="px-4 py-2">

                                <div class="flex justify-center">

                                    <x-link href="{{ route('login') }}" name="Iniciar Sesión" />

                                </div>

                                <p class="mt-4 text-sm text-center text-slate-600">
                                    ¿No tienes cuenta? <a href="{{ route('register') }}"
                                        class="font-semibold text-coral-600 hover:text-coral-500 hover:underline">Regístrate</a>

                                </p>

                            </div>
                            @else
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-slate-500">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('orders.tracking.index') }}">
                                <i class="fas fa-shopping-bag mr-2"></i>
                                Mis Pedidos
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell mr-2"></i>
                                Notificaciones
                                @if (auth()->user() && auth()->user()->unreadNotifications()->count() > 0)
                                <span
                                    class="ml-2 inline-flex items-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ auth()->user()->unreadNotifications()->count() }}
                                </span>
                                @endif
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('shipping.index') }}">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Mis Direcciones
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


                    <a href="{{ route('cart.index') }}"
                        class="relative p-2 text-lg transition duration-300 ease-in-out transform md:text-3xl hover:scale-110 hover:text-coral-300 focus:outline-none">
                        <i class="text-cream-100 fas fa-shopping-cart"></i>
                        <span id="cart-count"
                            class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-coral-500 rounded-full border-2 border-primary-900">{{
                            Cart::instance('shopping')->count() }}</span>
                    </a>

                </div>

            </div>

            {{--
            CAMBIO: Buscador en móvil (debajo de todo) con sincronización
            - Agregado ID único "search-mobile" para identificación en JavaScript
            - Agregado pr-10 para espacio del botón de limpiar a la derecha
            - Cambiado oninput a searchSync() para sincronizar con campo desktop
            - Agregado parámetro 'mobile' para identificar origen de la búsqueda
            - Agregado manejo de tecla Escape para limpiar búsqueda
            --}}
            <div class="mt-4 md:hidden">
                <div class="relative">
                    <x-input id="search-mobile"
                        class="w-full pl-10 pr-10 border-2 rounded-full border-secondary-400 bg-white/95 focus:border-coral-400 focus:ring focus:ring-coral-400/30 focus:ring-opacity-50 text-slate-700 placeholder-slate-500"
                        type="text" placeholder="Buscar productos" oninput="searchSync(this.value, 'mobile')"
                        onkeydown="if(event.key==='Escape'){this.value='';searchSync('', 'mobile');}" />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="text-slate-500 fas fa-search"></i>
                    </div>
                    {{--
                    CAMBIO: Botón de limpiar búsqueda para móvil
                    - Posicionado absolutamente a la derecha del input
                    - Oculto por defecto (clase 'hidden'), se muestra cuando hay texto
                    - Ejecuta clearSearch() que limpia ambos campos sincronizadamente
                    - Mismo comportamiento que el botón desktop para consistencia
                    - CORRECCIÓN: Separadas clases 'flex' y 'hidden' para evitar conflicto CSS
                    --}}
                    <button type="button" id="clear-search-mobile" onclick="clearSearch()"
                        class="absolute inset-y-0 right-0 items-center pr-3 text-slate-400 hover:text-coral-500 focus:outline-none hidden transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
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
    <!-- Sidebar/Menú Hamburguesa Optimizado para 344px -->
    <template x-if="sidebarOpen">
        <div class="fixed inset-0 z-40 flex">
            <!-- Fondo oscuro: cubre toda la pantalla y cierra el sidebar al hacer clic -->
            <div class="absolute inset-0 bg-gray-900/50" x-on:click="sidebarOpen = false"></div>

            <!-- Contenedor principal del menú hamburguesa -->
            <div class="relative z-50 w-full h-full pointer-events-none">
                <!-- Sidebar principal optimizado para dispositivos pequeños -->
                <div x-data="{ selectedFamily: null, selectedCategory: null }"
                    class="w-full max-w-xs xs:max-w-sm sm:max-w-md h-screen transition-all duration-300 bg-white shadow-lg pointer-events-auto overflow-hidden">

                    <!-- Header del sidebar -->
                    <div class="px-3 xs:px-4 py-3 font-semibold text-white bg-primary-900">
                        <div class="flex items-center justify-between">
                            <h1 class="text-lg xs:text-xl sm:text-2xl font-bold text-cream-100 truncate">LagoFish</h1>
                            <button x-on:click="sidebarOpen = false" class="flex-shrink-0 ml-2">
                                <i
                                    class="p-1 xs:p-2 text-base xs:text-lg transition duration-300 ease-in-out transform hover:scale-110 focus:outline-none fas fa-times text-cream-100 hover:text-coral-300"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido scrolleable del menú -->
                    <div class="h-[calc(100vh-3.5rem)] overflow-y-auto overflow-x-hidden">

                        <!-- Vista principal: Lista de Familias -->
                        <div x-show="!selectedFamily" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-x-4"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-x-0"
                            x-transition:leave-end="opacity-0 transform -translate-x-4">

                            <!-- Encabezado de familias -->
                            <div class="px-3 xs:px-4 py-3 bg-secondary-50 border-b border-secondary-200">
                                <h2 class="text-sm xs:text-base font-semibold text-slate-700 uppercase tracking-wide">
                                    <i class="fas fa-list mr-2 text-coral-500"></i>
                                    Familias de Productos
                                </h2>
                            </div>

                            <!-- Lista de familias -->
                            <ul class="pb-4">
                                @foreach ($families as $family)
                                <li class="border-b border-secondary-100/50 last:border-b-0">
                                    <div
                                        class="flex items-center justify-between px-3 xs:px-4 py-3 hover:bg-secondary-50 transition-colors duration-200">
                                        <!-- Enlace directo a la familia -->
                                        <a href="{{ route('families.show', $family) }}"
                                            class="flex-1 text-xs xs:text-sm text-slate-700 hover:text-coral-600 transition-colors duration-200 truncate pr-2">
                                            {{ $family->name }}
                                        </a>

                                        <!-- Botón para ver categorías -->
                                        <button
                                            x-on:click="selectedFamily = {{ $family->id }}; $wire.set('familyId', {{ $family->id }})"
                                            class="flex-shrink-0 p-1 xs:p-2 text-coral-500 hover:text-coral-700 hover:bg-coral-50 rounded-full transition-all duration-200">
                                            <i class="fas fa-chevron-right text-xs xs:text-sm"></i>
                                        </button>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Vista de Categorías (cuando se selecciona una familia) -->
                        <div x-show="selectedFamily && !selectedCategory"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-x-4"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-x-0"
                            x-transition:leave-end="opacity-0 transform -translate-x-4">

                            <!-- Header con botón de regreso -->
                            <div class="px-3 xs:px-4 py-3 bg-secondary-50 border-b border-secondary-200">
                                <div class="flex items-center">
                                    <button x-on:click="selectedFamily = null"
                                        class="mr-2 xs:mr-3 p-1 text-coral-600 hover:text-coral-800 hover:bg-coral-50 rounded-full transition-all duration-200">
                                        <i class="fas fa-chevron-left text-xs xs:text-sm"></i>
                                    </button>
                                    <h2
                                        class="text-xs xs:text-sm font-semibold text-slate-700 uppercase tracking-wide truncate">
                                        <i class="fas fa-tags mr-2 text-coral-500"></i>
                                        <span x-text="'{{ $this->familyName }}'"></span>
                                    </h2>
                                </div>

                                <!-- Enlace "Ver Todo" -->
                                @if ($this->familyId)
                                <div class="mt-2">
                                    <a href="{{ route('families.show', $this->familyId) }}"
                                        class="inline-flex items-center text-xs text-coral-600 hover:text-coral-700 font-medium transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver Todos los Productos
                                    </a>
                                </div>
                                @endif
                            </div>

                            <!-- Lista de categorías -->
                            <ul class="pb-4">
                                @foreach ($this->categories as $category)
                                <li class="border-b border-secondary-100/50 last:border-b-0">
                                    <div
                                        class="flex items-center justify-between px-3 xs:px-4 py-3 hover:bg-secondary-50 transition-colors duration-200">
                                        <!-- Enlace directo a la categoría -->
                                        <a href="{{ route('categories.show', $category) }}"
                                            class="flex-1 text-xs xs:text-sm text-slate-700 hover:text-coral-600 transition-colors duration-200 truncate pr-2 font-medium">
                                            {{ $category->name }}
                                        </a>

                                        <!-- Botón para ver subcategorías (solo si tiene subcategorías) -->
                                        @if($category->subcategories->count() > 0)
                                        <button x-on:click="selectedCategory = {{ $category->id }}"
                                            class="flex-shrink-0 p-1 xs:p-2 text-coral-500 hover:text-coral-700 hover:bg-coral-50 rounded-full transition-all duration-200">
                                            <i class="fas fa-chevron-right text-xs xs:text-sm"></i>
                                        </button>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Vista de Subcategorías (cuando se selecciona una categoría) -->
                        <div x-show="selectedFamily && selectedCategory"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-x-4"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-x-0"
                            x-transition:leave-end="opacity-0 transform -translate-x-4">

                            <!-- Header con botón de regreso -->
                            <div class="px-3 xs:px-4 py-3 bg-secondary-50 border-b border-secondary-200">
                                <div class="flex items-center">
                                    <button x-on:click="selectedCategory = null"
                                        class="mr-2 xs:mr-3 p-1 text-coral-600 hover:text-coral-800 hover:bg-coral-50 rounded-full transition-all duration-200">
                                        <i class="fas fa-chevron-left text-xs xs:text-sm"></i>
                                    </button>
                                    <h2
                                        class="text-xs xs:text-sm font-semibold text-slate-700 uppercase tracking-wide truncate">
                                        <i class="fas fa-sitemap mr-2 text-coral-500"></i>
                                        Subcategorías
                                    </h2>
                                </div>

                                <!-- Enlace a la categoría padre -->
                                <div class="mt-2">
                                    <template x-for="category in {{ json_encode($this->categories) }}"
                                        :key="category.id">
                                        <div x-show="category.id === selectedCategory">
                                            <a :href="'/categories/' + category.id"
                                                class="inline-flex items-center text-xs text-coral-600 hover:text-coral-700 font-medium transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                <span x-text="'Ver ' + category.name"></span>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Lista de subcategorías -->
                            <ul class="pb-4">
                                @foreach ($this->categories as $category)
                                <template x-if="selectedCategory === {{ $category->id }}">
                                    <div>
                                        @foreach ($category->subcategories as $subcategory)
                                        <li class="border-b border-secondary-100/50 last:border-b-0">
                                            <a href="{{ route('subcategories.show', $subcategory) }}"
                                                class="block px-3 xs:px-4 py-3 text-xs xs:text-sm text-slate-700 hover:text-coral-600 hover:bg-secondary-50 transition-all duration-200 truncate">
                                                <i class="fas fa-caret-right mr-2 text-coral-400"></i>
                                                {{ $subcategory->name }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </div>
                                </template>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </template>

    <div>

    </div>
    {{--
    SECCIÓN: Scripts JavaScript para funcionalidad de búsqueda sincronizada
    Esta sección contiene todas las funciones JavaScript necesarias para:
    - Sincronizar campos de búsqueda entre desktop y móvil
    - Manejar botones de limpiar búsqueda
    - Comunicación con componentes Livewire
    --}}
    @push('js')
    <script>
        Livewire.on('cartUpdated', (count) => {
                document.getElementById('cart-count').innerText = count;
            });
            {{--
                FUNCIÓN: search(value)
                Función original que envía el evento de búsqueda a Livewire
                @param {string} value - Término de búsqueda
            --}}

            function search(value) {
                Livewire.dispatch('search', {
                    search: value
                });
            }

            {{--
                FUNCIÓN: searchSync(value, source)
                Nueva función principal para sincronización de búsqueda
                Mantiene ambos campos (desktop y móvil) sincronizados y ejecuta la búsqueda
                @param {string} value - Término de búsqueda
                @param {string} source - Origen del evento ('desktop' o 'mobile')
            --}}

            function searchSync(value, source) {
                // Obtener referencias a ambos campos de búsqueda
                const desktopInput = document.getElementById('search-desktop');
                const mobileInput = document.getElementById('search-mobile');

                // Sincronizar el campo que no fue el origen del evento
                if (source === 'desktop' && mobileInput) {
                    mobileInput.value = value;
                } else if (source === 'mobile' && desktopInput) {
                    desktopInput.value = value;
                }

                // Mostrar/ocultar botones de limpiar según si hay contenido
                toggleClearButtons(value);

                // Ejecutar la búsqueda usando la función original
                search(value);
            }

            {{--
                FUNCIÓN: clearSearch()
                Limpia ambos campos de búsqueda y oculta los botones de limpiar
                Incluye múltiples métodos de selección para mayor compatibilidad
            --}}

            function clearSearch() {
                // Método principal: obtener inputs por ID
                let desktopInput = document.getElementById('search-desktop');
                let mobileInput = document.getElementById('search-mobile');

                // Método alternativo: usar querySelector si getElementById falla
                if (!desktopInput) {
                    desktopInput = document.querySelector('input[id="search-desktop"]');
                }
                if (!mobileInput) {
                    mobileInput = document.querySelector('input[id="search-mobile"]');
                }

                // Método de respaldo: buscar por placeholder si los anteriores fallan
                if (!desktopInput || !mobileInput) {
                    const allInputs = document.querySelectorAll('input[placeholder="Buscar productos"]');
                    allInputs.forEach(input => {
                        input.value = '';
                        input.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    });
                }

                // Logging para debugging (útil durante desarrollo)
                console.log('Desktop input:', desktopInput);
                console.log('Mobile input:', mobileInput);

                // Limpiar campo desktop si existe
                if (desktopInput) {
                    desktopInput.value = '';
                    // Disparar evento de input para asegurar que Livewire detecte el cambio
                    desktopInput.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    console.log('Desktop input cleared');
                }

                // Limpiar campo móvil si existe
                if (mobileInput) {
                    mobileInput.value = '';
                    // Disparar evento de input para asegurar que Livewire detecte el cambio
                    mobileInput.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    console.log('Mobile input cleared');
                }

                // Ocultar ambos botones de limpiar
                toggleClearButtons('');

                // Ejecutar búsqueda vacía para resetear resultados
                search('');

                // Enviar evento adicional a Livewire por si otros componentes lo necesitan
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('search', {
                        search: ''
                    });
                }
            }

            {{--
                FUNCIÓN: toggleClearButtons(value)
                Muestra u oculta los botones de limpiar según si hay contenido en los campos
                CORRECCIÓN: Maneja correctamente display:flex cuando se muestran los botones
                @param {string} value - Valor actual de los campos de búsqueda
            --}}

            function toggleClearButtons(value) {
                const clearDesktop = document.getElementById('clear-search-desktop');
                const clearMobile = document.getElementById('clear-search-mobile');

                if (value && value.trim() !== '') {
                    // Mostrar botones: remover 'hidden' y agregar 'flex' para layout correcto
                    if (clearDesktop) {
                        clearDesktop.classList.remove('hidden');
                        clearDesktop.classList.add('flex');
                    }
                    if (clearMobile) {
                        clearMobile.classList.remove('hidden');
                        clearMobile.classList.add('flex');
                    }
                } else {
                    // Ocultar botones: agregar 'hidden' y remover 'flex'
                    if (clearDesktop) {
                        clearDesktop.classList.add('hidden');
                        clearDesktop.classList.remove('flex');
                    }
                    if (clearMobile) {
                        clearMobile.classList.add('hidden');
                        clearMobile.classList.remove('flex');
                    }
                }
            }

            {{--
                EVENTO: DOMContentLoaded
                Inicializa el estado de los botones de limpiar al cargar la página
                Verifica si hay contenido inicial en los campos y ajusta la visibilidad de los botones
            --}}
            document.addEventListener('DOMContentLoaded', function() {
                const desktopInput = document.getElementById('search-desktop');
                const mobileInput = document.getElementById('search-mobile');

                // Verificar si hay contenido inicial en cualquiera de los campos
                const initialValue = (desktopInput && desktopInput.value) || (mobileInput && mobileInput.value) || '';
                toggleClearButtons(initialValue);
            });

            {{--
                EVENTO: Livewire 'clear-search-inputs'
                Escucha eventos desde el componente Filter de Livewire para limpiar búsquedas
                Permite que otros componentes (como Filter) limpien los campos de búsqueda
            --}}
            if (window.Livewire) {
                window.Livewire.on('clear-search-inputs', function() {
                    clearSearch();
                });
            }
    </script>
    @endpush
</div>