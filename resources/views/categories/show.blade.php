<x-app-layout>
    {{--
    Vista categories.show
    Este archivo utiliza el layout principal x-app-layout, lo que asegura que todo el contenido común (como el header,
    navegación y footer) se renderice automáticamente.
    Optimizado para responsive en dispositivos de 344px.
    --}}

    <x-container class="px-2 sm:px-4 my-2 sm:my-4 breadcrumb-container">

        <!-- Breadcrumb -->
        <nav class="flex px-3 sm:px-5 py-2 sm:py-3 text-primary-700 border border-secondary-200 rounded-xl bg-secondary-50/50 backdrop-blur-sm breadcrumb-container"
            aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse breadcrumb-scroll">
                <li class="inline-flex items-center breadcrumb-item">
                    <a href="/"
                        class="inline-flex items-center text-xs sm:text-sm font-medium text-primary-700 hover:text-coral-600 transition-colors duration-300">
                        <svg class="w-3 h-3 me-1.5 sm:me-2.5 text-coral-500" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        <span class="hidden xs:inline">Inicio</span>
                        <span class="xs:hidden">🏠</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <div class="flex items-center">
                        <svg class="block w-2 h-2 sm:w-3 sm:h-3 mx-1 text-secondary-400 rtl:rotate-180"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="{{ route('families.show', $category->family->id) }}"
                            class="text-xs sm:text-sm font-medium text-primary-600 ms-1 hover:text-coral-600 md:ms-2 transition-colors duration-300 flex items-center truncate">
                            <i class="fas fa-fish mr-1 text-xs flex-shrink-0"></i>
                            <span class="truncate">{{ Str::limit($category->family->name, 15) }}</span>
                        </a>
                    </div>
                </li>
                <li aria-current="page" class="breadcrumb-item">
                    <div class="flex items-center">
                        <svg class="w-2 h-2 sm:w-3 sm:h-3 mx-1 text-secondary-400 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span
                            class="text-xs sm:text-sm font-medium text-coral-600 ms-1 md:ms-2 flex items-center truncate">
                            <i class="fas fa-layer-group mr-1 text-xs flex-shrink-0"></i>
                            <span class="truncate">{{ Str::limit($category->name, 20) }}</span>
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

    </x-container>

    @livewire('filter', ['category_id' => $category->id])

</x-app-layout>