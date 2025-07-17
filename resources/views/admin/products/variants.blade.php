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
                                        onchange="handleVariantImageUpload(event, '#imgPreview')" />
                                </x-label>
                            </div>

                            <div
                                class="aspect-square w-full rounded-2xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-lg relative group">
                                <!-- Spinner DENTRO del contenedor relativo -->
                                <div id="variant-image-spinner" style="display:none;"
                                    class="absolute inset-0 bg-black bg-opacity-50 rounded-2xl flex items-center justify-center z-30">
                                    <div
                                        class="bg-white rounded-xl shadow-2xl p-6 flex items-center space-x-4 max-w-xs">
                                        <div
                                            class="animate-spin rounded-full h-8 w-8 border-4 border-indigo-200 border-t-indigo-600">
                                        </div>
                                        <div class="text-center">
                                            <p class="text-gray-800 font-semibold text-sm">Subiendo imagen...</p>
                                            <p class="text-gray-500 text-xs mt-1">Por favor espera</p>
                                        </div>
                                    </div>
                                </div>
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

                                <!-- Custom Price Field -->
                                <div class="space-y-3">
                                    <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                        value="{{ __('Precio Personalizado') }}">
                                        <i class="fas fa-dollar-sign mr-2 text-yellow-500"></i>
                                    </x-label>
                                    <x-input
                                        class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                        placeholder="Precio personalizado (opcional, deje vacío para usar precio base: ${{ number_format($product->price, 2) }})"
                                        type="number" step="0.01" name="custom_price"
                                        value="{{ old('custom_price', $variant->custom_price) }}" />
                                    <p class="text-sm text-gray-600 italic">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Si no especifica un precio, se usará el precio base del producto: ${{
                                        number_format($product->price, 2) }}
                                    </p>
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
        // Estado global para manejo de imagen temporal
        let tempImageUploaded = false;
        let currentImageUrl = null;

        /**
         * Maneja la subida de imagen de variante con persistencia durante errores
         * @param {Event} event - Evento del input file
         * @param {string} querySelector - Selector del elemento img
         */
        function handleVariantImageUpload(event, querySelector) {
            const input = event.target;
            const imgPreview = document.querySelector(querySelector);
            const spinner = document.getElementById('variant-image-spinner');

            if (!input.files.length) return;

            const file = input.files[0];

            // Validación básica de tipo
            if (!file.type.startsWith('image/')) {
                showImageErrorToast('El archivo seleccionado no es una imagen válida');
                input.value = '';
                return;
            }

            // Validación del nombre del archivo (máximo 100 caracteres)
            if (file.name.length > 100) {
                showImageErrorToast('El nombre del archivo es demasiado largo. Máximo 100 caracteres.');
                input.value = '';
                return;
            }

            // Validación de tamaño (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showImageErrorToast('La imagen no puede ser mayor a 2MB');
                input.value = '';
                return;
            }

            // Mostrar overlay spinner
            if (spinner) spinner.style.display = 'flex';
            // Mostrar indicador de carga visual en la imagen
            showLoadingAnimation(imgPreview);

            // Subir imagen temporalmente al servidor
            uploadImageToServer(file, imgPreview, input, spinner);
        }

        /**
         * Sube la imagen al servidor temporalmente
         * @param {File} file - Archivo de imagen
         * @param {HTMLElement} imgPreview - Elemento img para preview
         * @param {HTMLElement} input - Input file
         */
        function uploadImageToServer(file, imgPreview, input) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            const spinner = arguments[3];

            fetch('{{ route("admin.products.variants.upload-temp-image") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (spinner) spinner.style.display = 'none';
                if (data.success) {
                    // Imagen subida exitosamente, mostrar preview con animación elegante
                    showImagePreviewWithAnimation(imgPreview, data.preview_url);
                    tempImageUploaded = true;
                    currentImageUrl = data.preview_url;
                    // Marcar que hay una imagen temporal para el formulario
                    let hiddenInput = document.querySelector('input[name="temp_image_uploaded"]');
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'temp_image_uploaded';
                        document.querySelector('form').appendChild(hiddenInput);
                    }
                    hiddenInput.value = '1';
                    showImageUploadToast('imagen de variante');
                } else {
                    showImageErrorToast(data.message || 'Error al subir la imagen');
                    input.value = '';
                }
            })
            .catch(error => {
                if (spinner) spinner.style.display = 'none';
                console.error('Error:', error);
                showImageErrorToast('Error de conexión al subir la imagen');
                input.value = '';
            });
        }

        /**
         * Muestra animación de carga mientras se sube la imagen
         * @param {HTMLElement} imgPreview - Elemento img
         */
        function showLoadingAnimation(imgPreview) {
            // Guardar la imagen actual para evitar parpadeo
            const currentSrc = imgPreview.src;
            
            // Animación de carga elegante sin cambiar la imagen
            imgPreview.style.transition = 'all 0.3s ease-out';
            imgPreview.style.opacity = '0.6';
            imgPreview.style.transform = 'scale(0.98)';
            imgPreview.style.filter = 'blur(2px) grayscale(0.3)';
            
            // Agregar efecto de pulso sutil
            imgPreview.style.animation = 'pulse 2s ease-in-out infinite';
        }

        /**
         * Muestra el preview de imagen con animación mejorada
         * @param {HTMLElement} imgPreview - Elemento img
         * @param {string} imageUrl - URL de la imagen
         */
        function showImagePreviewWithAnimation(imgPreview, imageUrl) {
            // Cambiar la imagen INMEDIATAMENTE para evitar mostrar la anterior
            imgPreview.src = imageUrl;
            
            // Aplicar animación suave de entrada
            imgPreview.style.transition = 'all 0.4s ease-out';
            imgPreview.style.opacity = '0.8';
            imgPreview.style.transform = 'scale(0.98)';
            imgPreview.style.filter = 'blur(1px)';
            
            // Remover estilos de carga
            imgPreview.style.animation = 'none';

            // Animación final después de cambiar la src
            requestAnimationFrame(() => {
                setTimeout(() => {
                    imgPreview.style.opacity = '1';
                    imgPreview.style.transform = 'scale(1)';
                    imgPreview.style.filter = 'blur(0px)';

                    // Efecto de brillo temporal más sutil
                    imgPreview.style.boxShadow = '0 0 20px rgba(99, 102, 241, 0.4)';
                    
                    setTimeout(() => {
                        imgPreview.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
                        imgPreview.style.transition = 'all 0.3s ease';
                    }, 600);
                }, 100);
            });
        }

        /**
         * Restaura imagen temporal si existe en sesión (para cuando hay errores de validación)
         */
        function restoreTempImageIfExists() {
            @if(session('temp_variant_image'))
                const tempImageData = @json(session('temp_variant_image'));
                const imgPreview = document.querySelector('#imgPreview');
                if (imgPreview && tempImageData.url) {
                    // Cambiar directamente la imagen sin animación para evitar parpadeo
                    imgPreview.src = tempImageData.url;
                    tempImageUploaded = true;
                    currentImageUrl = tempImageData.url;
                    
                    // Crear el campo hidden para indicar que hay imagen temporal
                    let hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'temp_image_uploaded';
                    hiddenInput.value = '1';
                    document.querySelector('form').appendChild(hiddenInput);
                    
                    // Mostrar notificación solo si el flag de sesión está activo
                    @if(session('show_variant_image_restored_toast'))
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: '¡Imagen recuperada!',
                                text: 'Se ha mantenido tu imagen anterior',
                                icon: 'info',
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                                customClass: {
                                    popup: 'rounded-2xl shadow-2xl border-l-4 border-blue-500 bg-white',
                                    title: 'text-blue-800 font-bold',
                                    content: 'text-blue-700'
                                }
                            });
                        }
                        // Limpiar el flag después de mostrar el toast
                        fetch('{{ route("admin.products.variants.clear-toast-flag") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                    @endif
                }
            @endif
        }

        /**
         * Funciones para mostrar notificaciones de imagen
         */
        function showImageUploadToast(imageType = 'imagen') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: `¡${imageType.charAt(0).toUpperCase() + imageType.slice(1)} cargada!`,
                    text: `La ${imageType.toLowerCase()} se ha cargado correctamente`,
                    icon: 'success',
                    timer: 2500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    customClass: {
                        popup: 'rounded-lg shadow-lg border-l-4 border-green-500 bg-white'
                    }
                });
            }
        }

        function showImageErrorToast(message = 'Error al cargar la imagen') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¡Error!',
                    text: message,
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    customClass: {
                        popup: 'rounded-lg shadow-lg border-l-4 border-red-500 bg-white'
                    }
                });
            }
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            restoreTempImageIfExists();
        });

        // Agregar estilos CSS para la animación de pulso
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0%, 100% {
                    opacity: 0.4;
                    transform: scale(0.95);
                }
                50% {
                    opacity: 0.6;
                    transform: scale(0.98);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @endpush

</x-admin-layout>