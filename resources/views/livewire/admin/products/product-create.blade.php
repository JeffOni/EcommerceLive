<div class="min-h-screen bg-white">
    <form wire:submit.prevent="store" class="h-full p-3 sm:p-6 lg:p-8 relative">
        <!-- Header -->
        <div class="text-center mb-4 sm:mb-6 lg:mb-8">
            <h1
                class="text-lg sm:text-2xl lg:text-4xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
                Crear Nuevo Producto
            </h1>
            <p class="text-xs sm:text-base lg:text-lg text-gray-600">Complete la información para agregar un nuevo
                producto</p>
        </div>

        <x-validation-errors
            class="mb-4 sm:mb-6 lg:mb-8 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg sm:rounded-xl max-w-4xl mx-auto" />

        <!-- Main Content Grid: Responsive Layout -->
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 sm:gap-8 lg:gap-12">
                <!-- Left Column: Images Upload -->
                <div class="flex flex-col space-y-4 sm:space-y-6">
                    <!-- Imagen Principal -->
                    <figure class="relative">
                        <div class="absolute top-2 sm:top-4 right-2 sm:right-4 z-20">
                            <x-label
                                class="flex items-center px-2 py-1.5 sm:px-3 sm:py-2 bg-white rounded-lg shadow-md cursor-pointer hover:bg-secondary-50 hover:shadow-lg transition-all duration-300 border border-gray-200 group">
                                <i
                                    class="mr-1 fas fa-camera text-secondary-500 group-hover:text-secondary-600 transition-colors text-xs sm:text-sm"></i>
                                <span
                                    class="font-medium text-primary-700 group-hover:text-primary-800 transition-colors text-xs sm:text-sm">Imagen
                                    1</span>
                                <input type="file" wire:model="image" class="hidden" accept="image/*"
                                    onchange="previewImageWithAnimationLivewire(event, '#preview-image-1', 'imagen principal')" />
                            </x-label>
                        </div>

                        <div
                            class="aspect-square w-full rounded-lg sm:rounded-xl lg:rounded-2xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-lg relative group">
                            @if($temporaryImageUrl || $image)
                            <img id="preview-image-1"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $temporaryImageUrl ?? ($image ? $image->temporaryUrl() : asset('/img/no-image.png')) }}"
                                alt="Imagen Principal">
                            @else
                            <img id="preview-image-1"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ asset('/img/no-image.png') }}" alt="Imagen Principal">
                            @endif
                            <!-- Spinner igual que ProductEdit que ya funciona -->
                            <div id="cover-image-spinner" style="display:none;"
                                class="absolute inset-0 bg-black bg-opacity-50 rounded-2xl flex items-center justify-center z-50 pointer-events-auto">
                                <div class="bg-white rounded-xl shadow-2xl p-6 flex items-center space-x-4 max-w-xs">
                                    <div
                                        class="animate-spin rounded-full h-8 w-8 border-4 border-secondary-200 border-t-primary-600">
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-800 font-semibold text-sm">Subiendo imagen...</p>
                                        <p class="text-gray-500 text-xs mt-1">Por favor espera</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <figcaption class="font-medium text-center text-gray-700 mt-2">
                            Imagen Principal *
                            @if($imageUploaded)
                            <i class="fas fa-check-circle text-green-500 ml-1"></i>
                            @endif
                        </figcaption>
                    </figure>

                    <!-- Segunda Imagen -->
                    <figure class="relative">
                        <div class="absolute top-4 right-4 z-20">
                            <x-label
                                class="flex items-center px-3 py-2 bg-white rounded-lg shadow-md cursor-pointer hover:bg-green-50 hover:shadow-lg transition-all duration-300 border border-gray-200 group">
                                <i
                                    class="mr-1 fas fa-camera text-green-500 group-hover:text-green-600 transition-colors text-sm"></i>
                                <span
                                    class="font-medium text-green-700 group-hover:text-green-800 transition-colors text-sm">Imagen
                                    2</span>
                                <input type="file" wire:model="image2" class="hidden" accept="image/*"
                                    onchange="handleImage2Upload(event)" />
                            </x-label>
                        </div>

                        <div
                            class="aspect-square w-full rounded-2xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-lg relative group">
                            @if($temporaryImageUrl2 || $image2)
                            <img id="preview-image-2"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $temporaryImageUrl2 ?? ($image2 ? $image2->temporaryUrl() : asset('/img/no-image.png')) }}"
                                alt="Segunda Imagen">
                            @else
                            <img id="preview-image-2"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ asset('/img/no-image.png') }}" alt="Segunda Imagen">
                            @endif
                            <!-- Spinner igual que ProductEdit -->
                            <div id="product-image-spinner-2" style="display:none;"
                                class="absolute inset-0 bg-black bg-opacity-50 rounded-2xl flex items-center justify-center z-50 pointer-events-auto">
                                <div class="bg-white rounded-xl shadow-2xl p-6 flex items-center space-x-4 max-w-xs">
                                    <div
                                        class="animate-spin rounded-full h-8 w-8 border-4 border-secondary-200 border-t-primary-600">
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-800 font-semibold text-sm">Subiendo imagen...</p>
                                        <p class="text-gray-500 text-xs mt-1">Por favor espera</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <figcaption class="font-medium text-center text-gray-700 mt-2">
                            Segunda Imagen (opcional)
                            @if($image2Uploaded)
                            <i class="fas fa-check-circle text-green-500 ml-1"></i>
                            @endif
                        </figcaption>
                    </figure>

                    <!-- Tercera Imagen -->
                    <figure class="relative">
                        <div class="absolute top-4 right-4 z-20">
                            <x-label
                                class="flex items-center px-3 py-2 bg-white rounded-lg shadow-md cursor-pointer hover:bg-purple-50 hover:shadow-lg transition-all duration-300 border border-gray-200 group">
                                <i
                                    class="mr-1 fas fa-camera text-purple-500 group-hover:text-purple-600 transition-colors text-sm"></i>
                                <span
                                    class="font-medium text-purple-700 group-hover:text-purple-800 transition-colors text-sm">Imagen
                                    3</span>
                                <input type="file" wire:model="image3" class="hidden" accept="image/*"
                                    onchange="handleImage3Upload(event)" />
                            </x-label>
                        </div>

                        <div
                            class="aspect-square w-full rounded-2xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-lg relative group">
                            @if($temporaryImageUrl3 || $image3)
                            <img id="preview-image-3"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $temporaryImageUrl3 ?? ($image3 ? $image3->temporaryUrl() : asset('/img/no-image.png')) }}"
                                alt="Tercera Imagen">
                            @else
                            <img id="preview-image-3"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ asset('/img/no-image.png') }}" alt="Tercera Imagen">
                            @endif
                            <!-- Spinner igual que ProductEdit -->
                            <div id="product-image-spinner-3" style="display:none;"
                                class="absolute inset-0 bg-black bg-opacity-50 rounded-2xl flex items-center justify-center z-50 pointer-events-auto">
                                <div class="bg-white rounded-xl shadow-2xl p-6 flex items-center space-x-4 max-w-xs">
                                    <div
                                        class="animate-spin rounded-full h-8 w-8 border-4 border-secondary-200 border-t-primary-600">
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-800 font-semibold text-sm">Subiendo imagen...</p>
                                        <p class="text-gray-500 text-xs mt-1">Por favor espera</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <figcaption class="font-medium text-center text-gray-700 mt-2">
                            Tercera Imagen (opcional)
                            @if($image3Uploaded)
                            <i class="fas fa-check-circle text-green-500 ml-1"></i>
                            @endif
                        </figcaption>
                    </figure>
                </div>

                <!-- Right Column: Form Fields -->
                <div class="flex flex-col">
                    <div class="bg-gray-50 rounded-2xl shadow-lg p-8 border border-gray-200 flex-1">
                        <!-- Grid layout for better organization -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- SKU Field -->
                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Código de Producto') }}">
                                    <i class="fas fa-barcode mr-2 text-secondary-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-primary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    type="text" wire:model.defer="product.sku" placeholder="Código del Producto" />
                            </div>

                            <!-- Price Field -->
                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Precio') }}">
                                    <i class="fas fa-dollar-sign mr-2 text-green-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-primary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    type="number" step="0.01" wire:model.defer="product.price"
                                    placeholder="Precio del Producto" />
                            </div>
                        </div>

                        <!-- Full width fields -->
                        <div class="space-y-6 mb-8">
                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Nombre') }}">
                                    <i class="fas fa-tag mr-2 text-blue-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-primary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    type="text" wire:model.defer="product.name" placeholder="Nombre del Producto" />
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Descripción') }}">
                                    <i class="fas fa-align-left mr-2 text-purple-500"></i>
                                </x-label>
                                <x-textarea
                                    class="w-full border-gray-300 focus:border-primary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg resize-none py-3 text-lg"
                                    wire:model.defer="product.description" placeholder="Descripción del Producto"
                                    rows="4" />
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Características Generales') }}">
                                    <i class="fas fa-list-ul mr-2 text-teal-500"></i>
                                </x-label>
                                <x-textarea
                                    class="w-full border-gray-300 focus:border-primary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg resize-none py-3 text-lg"
                                    wire:model.defer="product.general_features"
                                    placeholder="Características generales del producto (opcional)" rows="3" />
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Preparación Recomendada') }}">
                                    <i class="fas fa-utensils mr-2 text-amber-500"></i>
                                </x-label>
                                <x-textarea
                                    class="w-full border-gray-300 focus:border-primary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg resize-none py-3 text-lg"
                                    wire:model.defer="product.recommended_preparation"
                                    placeholder="Preparación recomendada del producto (opcional)" rows="3" />
                            </div>
                        </div>

                        <!-- Category Selection Grid -->
                        <div class="grid grid-cols-1 gap-6 mb-8">
                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Familia') }}">
                                    <i class="fas fa-sitemap mr-2 text-orange-500"></i>
                                </x-label>
                                <x-select wire:model.live="family_id"
                                    class="w-full border-gray-300 focus:border-primary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    name="family_id" id="family_id">
                                    <option value="" disabled>Seleccione una familia</option>
                                    @foreach ($families as $family)
                                    <option value="{{ $family->id }}">{{ $family->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Categoría') }}">
                                    <i class="fas fa-folder mr-2 text-teal-500"></i>
                                </x-label>
                                <x-select wire:model.live="category_id"
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    name="category_id" id="category_id">
                                    <option value="" disabled>Seleccione una categoría</option>
                                    @foreach ($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Subcategoría') }}">
                                    <i class="fas fa-folder-open mr-2 text-pink-500"></i>
                                </x-label>
                                <x-select wire:model.live="product.subcategory_id"
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    name="subcategory_id" id="subcategory_id">
                                    <option value="" disabled>Seleccione una subcategoría</option>
                                    @foreach ($this->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col space-y-3 sm:flex-row sm:justify-end sm:space-y-0 sm:gap-6 pt-6 border-t border-gray-200">
                            <x-danger-button
                                class="w-full sm:w-auto px-4 py-2 sm:px-8 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 font-semibold transform hover:scale-105 text-sm sm:text-lg"
                                name="Cancelar" />
                            <x-button
                                class="w-full sm:w-auto px-4 py-2 sm:px-8 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-sm sm:text-lg"
                                name="Crear Producto" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('js')
<script>
    // Escuchar evento de imagen cargada desde Livewire
    document.addEventListener('livewire:init', () => {
        Livewire.on('imageUploaded', (event) => {
            const data = event[0] || event;
            if (typeof showImageUploadToast === 'function') {
                showImageUploadToast(data.type);
            }
        });
    });

    // Función específica para imagen 2
    function handleImage2Upload(event) {
        const input = event.target;
        const spinner = document.getElementById('product-image-spinner-2');
        const imgPreview = document.querySelector('#preview-image-2');

        if (!input.files.length) return;

        const file = input.files[0];

        // Validación básica
        if (!file.type.startsWith('image/')) {
            alert('El archivo seleccionado no es una imagen válida');
            input.value = '';
            return;
        }

        // Validación de tamaño (1MB)
        if (file.size > 1024 * 1024) {
            alert('La imagen no puede ser mayor a 1MB');
            input.value = '';
            return;
        }

        // Mostrar spinner
        if (spinner) {
            spinner.style.display = 'flex';
        }

        // Crear preview
        const objectURL = URL.createObjectURL(file);
        
        // Cambiar imagen con animación simple
        if (imgPreview) {
            imgPreview.style.opacity = '0.5';
            
            setTimeout(() => {
                imgPreview.src = objectURL;
                imgPreview.style.opacity = '1';
                
                // Ocultar spinner
                if (spinner) {
                    spinner.style.display = 'none';
                }
            }, 500);
        }
    }

    // Función específica para imagen 3
    function handleImage3Upload(event) {
        const input = event.target;
        const spinner = document.getElementById('product-image-spinner-3');
        const imgPreview = document.querySelector('#preview-image-3');

        if (!input.files.length) return;

        const file = input.files[0];

        // Validación básica
        if (!file.type.startsWith('image/')) {
            alert('El archivo seleccionado no es una imagen válida');
            input.value = '';
            return;
        }

        // Validación de tamaño (1MB)
        if (file.size > 1024 * 1024) {
            alert('La imagen no puede ser mayor a 1MB');
            input.value = '';
            return;
        }

        // Mostrar spinner
        if (spinner) {
            spinner.style.display = 'flex';
        }

        // Crear preview
        const objectURL = URL.createObjectURL(file);
        
        // Cambiar imagen con animación simple
        if (imgPreview) {
            imgPreview.style.opacity = '0.5';
            
            setTimeout(() => {
                imgPreview.src = objectURL;
                imgPreview.style.opacity = '1';
                
                // Ocultar spinner
                if (spinner) {
                    spinner.style.display = 'none';
                }
            }, 500);
        }
    }
</script>
@endpush