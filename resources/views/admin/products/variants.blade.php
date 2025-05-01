<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'route' => route('admin.products.index'),
    ],
    [
        'name' => $product->name,
        'route' => route('admin.products.edit', $product),
    ],
    [
        'name' => $variant->features->pluck('description')->implode(', '),
    ],
]">

    <form action="" method="POST">

        @csrf

        <div class="relative mb-6">

            {{-- Muestra el error de validación --}}
            <x-validation-errors class="mb-4" />

            {{-- Input de Nombre --}}

            <figure>

                <img id="imgPreview" src="{{ $variant->image }}" alt="Imagen de la variante"
                    class="w-full aspect-[16/9] mb-4 rounded-lg shadow-lg object-cover object-center">

            </figure>

            <div class="absolute top-8 right-8">

                <x-label class="flex items-center px-4 py-2 bg-white rounded-lg shadow-lg cursor-pointer">
                    <i class="mr-2 fas fa-camera"></i>
                    Actualizar Imagen
                    <input type="file" name="image" class="hidden" accept = "image/*"
                        onchange="previewImage(event, '#imgPreview')" />
                </x-label>

            </div>

        </div>

        {{-- div con clase card para todos --}}
        <div class="p-6 bg-white rounded-lg shadow-lg">

            <div class="mb-4">

                {{-- Input de Nombre --}}
                <x-label class="mb-2" value="{{ __('Codigo (SKU)') }}" />
                <x-input class="w-full" placeholder="ingrese el código SKU" name="sku"
                    value="{{ old('sku', $variant->sku) }}" />

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
