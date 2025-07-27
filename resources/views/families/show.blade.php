<x-app-layout>
    {{--
    Vista families.show
    Este archivo utiliza el layout principal x-app-layout, lo que asegura que todo el contenido común (como el header,
    navegación y footer) se renderice automáticamente.
    Si no se muestra el footer, normalmente es porque no hay contenido visible en la página, lo que puede hacer que el
    footer quede fuera de la vista o no se note.
    Para pruebas, agregamos contenido de ejemplo y mostramos la variable $family recibida desde el controlador.
    --}}

    <x-container class="px-4 my-4">


        <!-- Breadcrumb -->
        <nav class="flex px-5 py-3 text-primary-700 border border-secondary-200 rounded-xl bg-secondary-50/50 backdrop-blur-sm"
            aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="/"
                        class="inline-flex items-center text-sm font-medium text-primary-700 hover:text-coral-600 transition-colors duration-300">
                        <svg class="w-3 h-3 me-2.5 text-coral-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Inicio
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-secondary-400 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="text-sm font-medium text-coral-600 ms-1 md:ms-2 flex items-center">
                            <i class="fas fa-fish mr-1.5"></i>
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
    Si ves el footer ahora, significa que el layout funciona correctamente y el problema era solo la falta de contenido
    visible.
    --}}
</x-app-layout>