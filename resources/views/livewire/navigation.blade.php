<div>
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

                    {{-- Este componente representa un contenedor que puede ser utilizado para agrupar otros elementos --}}
                    {{-- Se utiliza para mantener la consistencia de diseño y facilitar el uso de clases comunes --}}
                    {{-- Este componente representa un campo de entrada de texto --}}
                    {{-- Se utiliza para buscar productos en la tienda --}}
                    {{-- El modelo está enlazado a la propiedad search del componente Livewire --}}
                    {{-- Se utilizan eventos para manejar el comportamiento del campo de búsqueda --}}
                    <div class="relative">
                        <x-input
                            class="w-full pl-10 border-2 border-blue-500 rounded-full focus:border-blue-600 focus:ring focus:ring-blue-300 focus:ring-opacity-50"
                            type="text" placeholder="Buscar productos" wire:model.debounce.500ms="search"
                            wire:keydown.enter="search" wire:keydown.escape="clearSearch"
                            wire:keydown.backspace="clearSearch" wire:keydown.delete="clearSearch"
                            wire:keydown.tab="clearSearch" wire:keydown.shift.tab="clearSearch"
                            wire:keydown.arrowup="clearSearch" wire:keydown.arrowdown="clearSearch"
                            wire:keydown.home="clearSearch" wire:keydown.end="clearSearch"
                            wire:keydown.pageup="clearSearch" wire:keydown.pagedown="clearSearch" />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="text-gray-500 fas fa-search"></i>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}

                <div class="flex items-center space-x-4">

                    {{-- Este componente representa un botón --}}
                    {{-- Se utiliza para mostrar el icono de usuario --}}
                    {{-- El icono es un icono de Font Awesome --}}
                    <button
                        class="relative p-2 text-lg transition duration-300 ease-in-out transform md:text-3xl hover:scale-110 hover:text-blue-200 focus:outline-none">
                        <i class="text-white fas fa-user"></i>
                    </button>

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
                        type="text" placeholder="Buscar productos" wire:model.debounce.500ms="search"
                        wire:keydown.enter="search" wire:keydown.escape="clearSearch"
                        wire:keydown.backspace="clearSearch" wire:keydown.delete="clearSearch"
                        wire:keydown.tab="clearSearch" wire:keydown.shift.tab="clearSearch"
                        wire:keydown.arrowup="clearSearch" wire:keydown.arrowdown="clearSearch"
                        wire:keydown.home="clearSearch" wire:keydown.end="clearSearch" wire:keydown.pageup="clearSearch"
                        wire:keydown.pagedown="clearSearch" />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="text-gray-500 fas fa-search"></i>
                    </div>
                </div>
            </div>

        </x-container>

    </header>

    {{-- Sidebar  --}}
    <div class="fixed inset-0 left-0 z-10 flex items-start justify-end w-full h-full bg-gray-900/50"></div>

    {{-- sidebar content --}}

    <div class="fixed inset-0 top-0 left-0 z-20">

        <div class="flex">

            <div class="w-screen h-screen transition-all duration-300 bg-white shadow-lg md:w-80">

                <div class="px-4 py-3 font-semibold text-white bg-blue-400">

                    <div class="flex items-center justify-between px-2">

                        {{-- Logo de la tienda --}}

                        <h1 class="text-2xl font-bold">Hola Peresona</h1>

                        <button>
                            <i class="p-2 text-lg transition duration-300 ease-in-out transform hover:scale-110 focus:outline-none fas fa-times"
                                x-on:click="sidebarOpen = !sidebarOpen"></i>
                        </button>

                    </div>

                </div>

                <div class="h-[calc(100vh-3.25rem)] overflow-y-auto">

                    <ul>

                        @foreach ($families as $family)
                            <li wire:mouseover="$set('familyId', {{ $family->id }})"
                                class="flex items-center justify-between px-4 py-3 text-gray-700 transition duration-300 ease-in-out transform hover:bg-blue-100">
                                <a href="/"
                                    class="flex items-center justify-between w-full text-gray-700 transition duration-300 ease-in-out transform hover:text-blue-600">

                                    {{-- Icono de la familia --}}
                                    <span>{{ $family->name }}</span>
                                    <i class="fa-solid fa-angle-right"></i>

                                </a>
                            </li>
                        @endforeach

                    </ul>

                </div>


            </div>
            {{-- second part of sidebar --}}

            <div class="w-80 xl:w-[57rem] pt-[3.25rem] hidden md:block">

                <div class="h-[calc(100vh-3.25rem)] overflow-y-auto bg-white shadow-lg px-6 py-8">
                    {{-- eheader de families --}}
                    <div class="flex items-center justify-between mb-8">
                        <p class="border-b-[3px] border-lime-600 text-xl pb-1 uppercase font-bold text-gray-700">
                            {{-- Icono de la familia --}}
                            {{ $this->familyName }}
                        </p>

                        <x-link name="Ver Todo"/>
                    </div>

                    <ul class="grid grid-cols-1 gap-8 xl:grid-cols-3">

                        {{-- categories --}}

                        @foreach ($this->categories as $category)
                            <li wire:mouseover="">
                                <a href="/"
                                    class="flex items-center justify-between text-blue-600 ">

                                    {{-- Icono de la categoria --}}
                                    {{ $category->name }}
                                </a>

                                {{-- subcategories --}}

                                <ul class="mt-4 space-y-2">

                                    @foreach ($category->subcategories as $subcategory)
                                        <li>
                                            <a href="/"
                                                class="text-sm text-gray-700 transition duration-300 ease-in-out transform hover:text-blue-600">

                                                {{-- Icono de la subcategoria --}}
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



</div>
