<x-app-layout>
    {{--
        Vista families.show
        Este archivo utiliza el layout principal x-app-layout, lo que asegura que todo el contenido común (como el header, navegación y footer) se renderice automáticamente.
        Si no se muestra el footer, normalmente es porque no hay contenido visible en la página, lo que puede hacer que el footer quede fuera de la vista o no se note.
        Para pruebas, agregamos contenido de ejemplo y mostramos la variable $family recibida desde el controlador.
    --}}

    <x-container class="px-4 my-4">


        <!-- Breadcrumb -->
        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
            aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    {{-- CORRECCIÓN: Usar '/' para ir a la página principal --}}
                    <a href="/"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Home
                    </a>
                </li>
                {{-- <li>
                    <div class="flex items-center">
                        <svg class="block w-3 h-3 mx-1 text-gray-400 rtl:rotate-180 " aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#"
                            class="text-sm font-medium text-gray-700 ms-1 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Templates</a>
                    </div>
                </li> --}}
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500 ms-1 md:ms-2 dark:text-gray-400">
                            {{ $family->name }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

    </x-container>


    @livewire('filter', ['family_id' => $family->id])


    {{--
        El footer se incluye automáticamente al final de la página por el layout x-app-layout.
        Si ves el footer ahora, significa que el layout funciona correctamente y el problema era solo la falta de contenido visible.
    --}}
</x-app-layout>
