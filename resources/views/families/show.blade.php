<x-app-layout>
    {{--
        Vista families.show
        Este archivo utiliza el layout principal x-app-layout, lo que asegura que todo el contenido común (como el header, navegación y footer) se renderice automáticamente.
        Si no se muestra el footer, normalmente es porque no hay contenido visible en la página, lo que puede hacer que el footer quede fuera de la vista o no se note.
        Para pruebas, agregamos contenido de ejemplo y mostramos la variable $family recibida desde el controlador.
    --}}
    <div class="mt-8">

        @livewire('filter', ['family_id' => $family->id])

    </div>
    {{--
        El footer se incluye automáticamente al final de la página por el layout x-app-layout.
        Si ves el footer ahora, significa que el layout funciona correctamente y el problema era solo la falta de contenido visible.
    --}}
</x-app-layout>
