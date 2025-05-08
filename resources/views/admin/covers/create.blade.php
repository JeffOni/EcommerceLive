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

    <form action="{{ route('admin.covers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- figure --}}

        <figure class="relative">

            <div class="absolute top-8 right-8">
                <x-label class="flex items-center px-4 py-2 bg-white rounded-lg shadow-lg cursor-pointer">
                    <i class="mr-2 fas fa-camera"></i>
                    Actualizar Imagen
                    <input type="file" class="hidden" accept = "image/*" name="image"
                    onchange="previewImage(event, '#imgPreview')" />

                </x-label>
            </div>

            <img id="imgPreview" src="{{ asset('img/no-image-horizontal.png') }}" alt="Portada"
                class="w-full rounded-lg object-center aspect-[3/1]">
            <figcaption class="mt-2 text-center text-gray-600">Sube una imagen de portada</figcaption>
        </figure>

        {{-- div con clase card para todos --}}
        <div class="p-6 bg-white rounded-lg shadow-lg">

            {{-- Muestra el error de validación --}}

            <x-validation-errors class="mb-4" />

            {{-- Input de Nombre --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Nombre') }}" />
                <x-input class="w-full" placeholder="ingrese el nombre de la Portada" name="name"
                    value="{{ old('name') }}" />
            </div>

            {{-- Input de Imagen --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Imagen') }}" />
                <x-input type="file" name="image" accept=".jpg, .jpeg, .png, .gif" />
            </div>

            <div class="flex justify-end">
                <x-button name="Crear Portada" />
            </div>
        </div>
    </form>

    @push('js')
        <script>
            function previewImage(event, querySelector) {

                //Recuperamos el input que desencadeno la acción
                let input = event.target;

                //Recuperamos la etiqueta img donde cargaremos la imagen
                let imgPreview = document.querySelector(querySelector);

                // Verificamos si existe una imagen seleccionada
                if (!input.files.length) return

                //Recuperamos el archivo subido
                let file = input.files[0];

                //Creamos la url
                let objectURL = URL.createObjectURL(file);

                //Modificamos el atributo src de la etiqueta img
                imgPreview.src = objectURL;

            }
        </script>
    @endpush

</x-admin-layout>
