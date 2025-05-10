<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
        'route' => route('admin.covers.index'),
    ],
    [
        'name' => 'Crear Portada',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.covers.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    {{-- Formulario para crear una nueva Portada --}}
    {{-- Este formulario permite al administrador crear portadas para la página de inicio --}}
    {{-- La ruta de acción es "admin.covers.store" y el método es POST --}}
    {{-- enctype="multipart/form-data" es necesario para permitir la subida de imágenes --}}
    <form action="{{ route('admin.covers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Sección de vista previa de imagen --}}
        {{-- Muestra la imagen predeterminada o la imagen seleccionada por el usuario --}}
        <figure class="relative mb-4">

            {{-- Botón para seleccionar una nueva imagen --}}
            {{-- Posicionado en la esquina superior derecha de la imagen --}}
            <div class="absolute top-8 right-8">
                <x-label class="flex items-center px-4 py-2 bg-white rounded-lg shadow-lg cursor-pointer">
                    <i class="mr-2 fas fa-camera"></i>
                    Actualizar Imagen
                    {{-- Input oculto tipo file que se activa al hacer clic en el botón --}}
                    {{-- Acepta solo archivos de imagen --}}
                    {{-- Al cambiar, llama a la función previewImage para mostrar la vista previa --}}
                    <input type="file" class="hidden" accept = "image/*" name="image"
                        onchange="previewImage(event, '#imgPreview')" />

                </x-label>
            </div>

            {{-- Imagen de vista previa --}}
            {{-- Inicialmente muestra una imagen predeterminada --}}
            {{-- ID "imgPreview" usado por la función JavaScript para actualizar la vista previa --}}
            <img id="imgPreview" src="{{ asset('img/no-image-horizontal.png') }}" alt="Portada"
                class="w-full rounded-lg object-center aspect-[3/1]">
            <figcaption class="mt-2 text-center text-gray-600">Sube una imagen de portada</figcaption>
        </figure>

        {{-- Contenedor principal del formulario --}}
        {{-- Diseñado con estilos Tailwind para una apariencia moderna y consistente --}}
        <div class="p-6 bg-white rounded-lg shadow-lg">

            {{-- Muestra el error de validación --}}
            {{-- Este componente muestra mensajes de error de validación si existen --}}
            <x-validation-errors class="mb-4" />

            {{-- Input de Título de la portada --}}
            {{-- Este campo permite al usuario introducir un título descriptivo para la portada --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Título') }}" />
                <x-input class="w-full" placeholder="ingrese el título de la Portada" name="title"
                    value="{{ old('title') }}" />
            </div>

            {{-- Input de Fecha de Inicio --}}
            {{-- Este campo define cuándo la portada comenzará a mostrarse en el sitio --}}
            {{-- Valor predeterminado: fecha actual --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Fecha de Inicio ') }}" />
                <x-input class="w-full" type="date" name="start_at"
                    value="{{ old('start_at', now()->format('Y-m-d')) }}" />
            </div>

            {{-- Input de Fecha de Fin --}}
            {{-- Este campo define cuándo la portada dejará de mostrarse en el sitio --}}
            {{-- Valor predeterminado: un mes después de la fecha actual --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Fecha de Fin (Opcional)') }}" />
                <x-input class="w-full" type="date" name="end_at" value="{{ old('end_at') }}" />
            </div>

            <div class="flex mb-4 space-x-2">
                {{-- Input de URL de la Portada --}}
                {{-- Este campo permite al usuario introducir una URL a la que se redirigirá al hacer clic en la portada --}}
                <label>
                    <x-input type="radio" name="is_active" value="1" checked />
                    <span class="ml-2">Activar Portada</span>
                </label>
                <label>
                    <x-input type="radio" name="is_active" value="0" />
                    <span class="ml-2">Desactivar Portada</span>
                </label>
            </div>

            {{-- Botón de envío del formulario --}}
            {{-- Alineado a la derecha mediante Flexbox --}}
            <div class="flex justify-end">
                <x-button type="submit" name="Crear Portada" />
            </div>
        </div>
    </form>

    {{-- Scripts JavaScript --}}
    {{-- Sección push añade el script al stack 'js' definido en el layout principal --}}
    @push('js')
        <script>
            /**
             * Función para mostrar la vista previa de una imagen antes de subirla
             * @param {Event} event - El evento de cambio del input file
             * @param {String} querySelector - Selector CSS del elemento img donde se mostrará la vista previa
             */
            function previewImage(event, querySelector) {

                //Recuperamos el input que desencadeno la acción
                let input = event.target;

                //Recuperamos la etiqueta img donde cargaremos la imagen
                let imgPreview = document.querySelector(querySelector);

                // Verificamos si existe una imagen seleccionada
                if (!input.files.length) return

                //Recuperamos el archivo subido
                let file = input.files[0];

                //Creamos la url temporal para la vista previa
                let objectURL = URL.createObjectURL(file);

                //Modificamos el atributo src de la etiqueta img
                imgPreview.src = objectURL;
            }
        </script>
    @endpush

</x-admin-layout>
