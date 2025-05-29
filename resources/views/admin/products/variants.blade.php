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

<x-slot name="action">
    <x-link href="{{ route('admin.products.edit', $product) }}" type="secondary" name="Regresar" />
</x-slot>

    <div class="min-h-screen bg-white">
        <form action="{{ route('admin.products.variantsUpdate', [$product, $variant]) }}" method="POST"
            enctype="multipart/form-data" class="h-full p-8 relative overflow-hidden">

            <!-- Decorative background elements -->
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-200/20 to-purple-200/20 rounded-full -translate-y-16 translate-x-16">
            </div>
            <div
                class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-200/20 to-cyan-200/20 rounded-full translate-y-12 -translate-x-12">
            </div>

            @csrf
            @method('PUT')

            <!-- Header -->
            <div class="text-center mb-8">
                <h1
                    class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    Editar Variante
                </h1>
                <p class="text-gray-600 text-lg">
                    {{ $variant->features->pluck('description')->implode(', ') }} - {{ $product->name }}
                </p>
            </div>

            <x-validation-errors class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl max-w-4xl mx-auto" />


            <!-- Main Content Grid: Image on Left, Form on Right -->
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
                    <!-- Left Column: Image Upload -->
                    <div class="flex flex-col">
                        <figure class="relative flex-1">
                            <div class="absolute top-6 right-6 z-20">
                                <x-label
                                    class="flex items-center px-4 py-3 bg-white rounded-xl shadow-lg cursor-pointer hover:bg-indigo-50 hover:shadow-xl transition-all duration-300 border border-gray-200 group">
                                    <i
                                        class="mr-2 fas fa-camera text-indigo-500 group-hover:text-indigo-600 transition-colors"></i>
                                    <span
                                        class="font-semibold text-indigo-700 group-hover:text-indigo-800 transition-colors">Actualizar
                                        Imagen</span>
                                    <input type="file" name="image" class="hidden" accept="image/*"
                                        onchange="previewImage(event, '#imgPreview')" />
                                </x-label>
                            </div>
                            <div
                                class="aspect-square w-full rounded-2xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-lg relative group">
                                <img id="imgPreview"
                                    class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                    src="{{ $variant->image }}" alt="Imagen de la variante">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                            </div>
                            <figcaption class="font-semibold text-center text-gray-700 mt-4 text-lg">Imagen de Variante
                            </figcaption>
                        </figure>
                    </div>

                    <!-- Right Column: Form Fields -->
                    <div class="flex flex-col">
                        <div class="bg-gray-50 rounded-2xl shadow-lg p-8 border border-gray-200 flex-1">
                            <!-- Form Fields -->
                            <div class="space-y-8 mb-8">
                                <!-- SKU Field -->
                                <div class="space-y-3">
                                    <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                        value="{{ __('Código (SKU)') }}">
                                        <i class="fas fa-barcode mr-2 text-indigo-500"></i>
                                    </x-label>
                                    <x-input
                                        class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                        placeholder="Ingrese el código SKU" name="sku"
                                        value="{{ old('sku', $variant->sku) }}" />
                                </div>

                                <!-- Stock Field -->
                                <div class="space-y-3">
                                    <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                        value="{{ __('Stock') }}">
                                        <i class="fas fa-boxes mr-2 text-green-500"></i>
                                    </x-label>
                                    <x-input
                                        class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                        placeholder="Ingrese el stock disponible" type="number" name="stock"
                                        value="{{ old('stock', $variant->stock) }}" />
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="flex justify-end pt-6 border-t border-gray-200">
                                <x-button type="submit" name="Actualizar" positive
                                    class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Actualizar Variante
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    @push('js')
        <script>
            function previewImage(event, querySelector) {
                // Recuperamos el input que desencadeno la acción
                let input = event.target;

                // Recuperamos la etiqueta img donde cargaremos la imagen
                let imgPreview = document.querySelector(querySelector);

                // Verificamos si existe una imagen seleccionada
                if (!input.files.length) return

                // Recuperamos el archivo subido
                let file = input.files[0];

                // Creamos la url
                let objectURL = URL.createObjectURL(file);

                // Modificamos el atributo src de la etiqueta img
                imgPreview.src = objectURL;

                // Agregamos una animación suave al cambiar la imagen
                imgPreview.style.opacity = '0.5';
                setTimeout(() => {
                    imgPreview.style.opacity = '1';
                }, 300);

                // Mostramos notificación de éxito
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¡Imagen cargada!',
                        text: 'La nueva imagen se ha cargado correctamente',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl',
                        }
                    });
                }
            }
        </script>
    @endpush

</x-admin-layout>
