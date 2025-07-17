<div class="min-h-screen bg-white">
    <form wire:submit.prevent="store" class="h-full p-8 relative overflow-hidden">
        <!-- Decorative background elements -->
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-200/20 to-purple-200/20 rounded-full -translate-y-16 translate-x-16">
        </div>
        <div
            class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-200/20 to-cyan-200/20 rounded-full translate-y-12 -translate-x-12">
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1
                class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                Editar Producto
            </h1>
            <p class="text-gray-600 text-lg">Modifique la información del producto</p>
        </div>

        <x-validation-errors class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl max-w-4xl mx-auto" />

        <!-- Main Content Grid: Images on Left, Form on Right -->
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
                <!-- Left Column: Images Upload -->
                <div class="flex flex-col space-y-8">
                    <!-- Imagen Principal -->
                    <figure class="relative">
                        <div class="absolute top-6 right-6 z-20">
                            <x-label
                                class="flex items-center px-4 py-3 bg-white rounded-xl shadow-lg cursor-pointer hover:bg-indigo-50 hover:shadow-xl transition-all duration-300 border border-gray-200 group">
                                <i
                                    class="mr-2 fas fa-camera text-indigo-500 group-hover:text-indigo-600 transition-colors"></i>
                                <span
                                    class="font-semibold text-indigo-700 group-hover:text-indigo-800 transition-colors">Actualizar
                                    Imagen Principal</span>
                                <input type="file" wire:model="image" class="hidden" accept="image/*" name="image"
                                    onchange="previewImage1(event)" />
                            </x-label>
                        </div>

                        <div
                            class="aspect-square w-full rounded-2xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-lg relative group">
                            @if($image)
                            <img id="preview1"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $image->temporaryUrl() }}" alt="Nueva imagen principal">
                            @elseif($productEdit['image_path'])
                            <img id="preview1"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ Storage::url($productEdit['image_path']) }}" alt="Imagen actual del producto">
                            @else
                            <div id="preview1" class="w-full h-full flex items-center justify-center bg-gray-100">
                                <div class="text-center">
                                    <i class="fas fa-image text-gray-400 text-6xl mb-4"></i>
                                    <p class="text-gray-500 font-medium">Sin imagen principal</p>
                                </div>
                            </div>
                            @endif
                            <!-- Spinner -->
                            <div id="spinner1" style="display:none;"
                                class="absolute inset-0 bg-black bg-opacity-50 rounded-2xl flex items-center justify-center z-50 pointer-events-auto">
                                <div class="bg-white rounded-xl shadow-2xl p-6 flex items-center space-x-4 max-w-xs">
                                    <div
                                        class="animate-spin rounded-full h-8 w-8 border-4 border-indigo-200 border-t-indigo-600">
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

                        <!-- Error específico para imagen -->
                        @error('image')
                        <div class="mt-3 p-3 bg-red-100 border border-red-300 rounded-lg">
                            <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
                        </div>
                        @enderror

                        <figcaption class="font-semibold text-center text-gray-700 mt-4 text-lg">Imagen Principal
                        </figcaption>
                    </figure>

                    <!-- Segunda Imagen -->
                    <figure class="relative">
                        <div class="absolute top-6 right-6 z-20">
                            <x-label
                                class="flex items-center px-4 py-3 bg-white rounded-xl shadow-lg cursor-pointer hover:bg-green-50 hover:shadow-xl transition-all duration-300 border border-gray-200 group">
                                <i
                                    class="mr-2 fas fa-camera text-green-500 group-hover:text-green-600 transition-colors"></i>
                                <span
                                    class="font-semibold text-green-700 group-hover:text-green-800 transition-colors">Segunda
                                    Imagen</span>
                                <input type="file" wire:model="image2" class="hidden" accept="image/*" name="image2"
                                    onchange="previewImage2(event)" />
                            </x-label>
                        </div>

                        <div
                            class="aspect-square w-full rounded-2xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-lg relative group">
                            @if($image2)
                            <img id="preview2"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $image2->temporaryUrl() }}" alt="Nueva segunda imagen">
                            @elseif(!empty($productEdit['image_2']))
                            <img id="preview2"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ Storage::url($productEdit['image_2']) }}" alt="Segunda imagen actual">
                            @else
                            <div id="preview2" class="w-full h-full flex items-center justify-center bg-gray-100">
                                <div class="text-center">
                                    <i class="fas fa-image text-gray-400 text-6xl mb-4"></i>
                                    <p class="text-gray-500 font-medium">Sin segunda imagen</p>
                                </div>
                            </div>
                            @endif
                            <!-- Spinner -->
                            <div id="spinner2" style="display:none;"
                                class="absolute inset-0 bg-black bg-opacity-50 rounded-2xl flex items-center justify-center z-50 pointer-events-auto">
                                <div class="bg-white rounded-xl shadow-2xl p-6 flex items-center space-x-4 max-w-xs">
                                    <div
                                        class="animate-spin rounded-full h-8 w-8 border-4 border-green-200 border-t-green-600">
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

                        <!-- Error específico para segunda imagen -->
                        @error('image2')
                        <div class="mt-3 p-3 bg-red-100 border border-red-300 rounded-lg">
                            <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
                        </div>
                        @enderror

                        <figcaption class="font-semibold text-center text-gray-700 mt-4 text-lg">Segunda Imagen
                        </figcaption>
                    </figure>

                    <!-- Tercera Imagen -->
                    <figure class="relative">
                        <div class="absolute top-6 right-6 z-20">
                            <x-label
                                class="flex items-center px-4 py-3 bg-white rounded-xl shadow-lg cursor-pointer hover:bg-purple-50 hover:shadow-xl transition-all duration-300 border border-gray-200 group">
                                <i
                                    class="mr-2 fas fa-camera text-purple-500 group-hover:text-purple-600 transition-colors"></i>
                                <span
                                    class="font-semibold text-purple-700 group-hover:text-purple-800 transition-colors">Tercera
                                    Imagen</span>
                                <input type="file" wire:model="image3" class="hidden" accept="image/*" name="image3"
                                    onchange="previewImage3(event)" />
                            </x-label>
                        </div>

                        <div
                            class="aspect-square w-full rounded-2xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-lg relative group">
                            @if($image3)
                            <img id="preview3"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $image3->temporaryUrl() }}" alt="Nueva tercera imagen">
                            @elseif(!empty($productEdit['image_3']))
                            <img id="preview3"
                                class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ Storage::url($productEdit['image_3']) }}" alt="Tercera imagen actual">
                            @else
                            <div id="preview3" class="w-full h-full flex items-center justify-center bg-gray-100">
                                <div class="text-center">
                                    <i class="fas fa-image text-gray-400 text-6xl mb-4"></i>
                                    <p class="text-gray-500 font-medium">Sin tercera imagen</p>
                                </div>
                            </div>
                            @endif
                            <!-- Spinner -->
                            <div id="spinner3" style="display:none;"
                                class="absolute inset-0 bg-black bg-opacity-50 rounded-2xl flex items-center justify-center z-50 pointer-events-auto">
                                <div class="bg-white rounded-xl shadow-2xl p-6 flex items-center space-x-4 max-w-xs">
                                    <div
                                        class="animate-spin rounded-full h-8 w-8 border-4 border-purple-200 border-t-purple-600">
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

                        <!-- Error específico para tercera imagen -->
                        @error('image3')
                        <div class="mt-3 p-3 bg-red-100 border border-red-300 rounded-lg">
                            <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
                        </div>
                        @enderror

                        <figcaption class="font-semibold text-center text-gray-700 mt-4 text-lg">Tercera Imagen
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
                                    <i class="fas fa-barcode mr-2 text-indigo-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    type="text" wire:model.defer="productEdit.sku" placeholder="Código del Producto" />
                            </div>

                            <!-- Price Field -->
                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Precio') }}">
                                    <i class="fas fa-dollar-sign mr-2 text-green-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    type="number" step="0.01" wire:model.defer="productEdit.price"
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
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    type="text" wire:model.defer="productEdit.name" placeholder="Nombre del Producto" />
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Descripción') }}">
                                    <i class="fas fa-align-left mr-2 text-purple-500"></i>
                                </x-label>
                                <x-textarea
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg resize-none py-3 text-lg"
                                    wire:model.defer="productEdit.description" placeholder="Descripción del Producto"
                                    rows="4" />
                            </div>

                            @empty($product->variants->count() > 0)
                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Stock') }}">
                                    <i class="fas fa-boxes mr-2 text-amber-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    type="number" wire:model.defer="productEdit.stock"
                                    placeholder="Stock del Producto" />
                            </div>
                            @endempty
                        </div>

                        <!-- Category Selection Grid -->
                        <div class="grid grid-cols-1 gap-6 mb-8">
                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Familia') }}">
                                    <i class="fas fa-sitemap mr-2 text-orange-500"></i>
                                </x-label>
                                <x-select wire:model.live="family_id"
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
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
                                <x-select wire:model.live="productEdit.subcategory_id"
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
                        <div class="flex justify-end gap-6 pt-6 border-t border-gray-200">
                            <x-danger-button
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 font-semibold transform hover:scale-105 text-lg"
                                onclick="confirmDelete()" name="Eliminar" />
                            <x-button
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-lg"
                                name="Actualizar Producto" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

    {{-- formulario para eliminar --}}
    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>

    @push('js')
    <script>
        let confirmDelete = () => {
                // Sweet Alert 2
                Swal.fire({
                    title: "Estás Seguro?",
                    text: "No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, Bórralo!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Swal.fire({
                        //     title: "Eliminado!",
                        //     text: "Su archivo ha sido eliminado.",
                        //     icon: "success"
                        // });
                        document.getElementById('delete-form').submit();
                    }
                });
            }

            // CÓDIGO EXACTAMENTE IGUAL QUE COVER - CERO CAMBIOS
            document.addEventListener('livewire:init', () => {
                Livewire.on('image-uploaded', () => {
                    if (typeof showImageUploadToast === 'function') {
                        showImageUploadToast('imagen principal');
                    }
                });

                Livewire.on('image2-uploaded', () => {
                    if (typeof showImageUploadToast === 'function') {
                        showImageUploadToast('segunda imagen');
                    }
                });

                Livewire.on('image3-uploaded', () => {
                    if (typeof showImageUploadToast === 'function') {
                        showImageUploadToast('tercera imagen');
                    }
                });
            });

            // Funciones de preview para cada imagen
            function previewImage1(event) {
                const input = event.target;
                const imgPreview = document.getElementById('preview1');
                const spinner = document.getElementById('spinner1');

                if (!input.files.length) return;

                const file = input.files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Archivo no válido');
                    input.value = '';
                    return;
                }

                if (spinner) spinner.style.display = 'flex';

                const objectURL = URL.createObjectURL(file);
                
                if (imgPreview.tagName === 'IMG') {
                    imgPreview.style.opacity = '0.5';
                    setTimeout(() => {
                        imgPreview.src = objectURL;
                        imgPreview.style.opacity = '1';
                        if (spinner) spinner.style.display = 'none';
                    }, 500);
                } else {
                    // Es un div, crear nueva imagen
                    const newImg = document.createElement('img');
                    newImg.id = 'preview1';
                    newImg.className = 'w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105';
                    newImg.src = objectURL;
                    newImg.alt = 'Nueva imagen principal';
                    
                    setTimeout(() => {
                        imgPreview.parentNode.replaceChild(newImg, imgPreview);
                        if (spinner) spinner.style.display = 'none';
                    }, 500);
                }
            }

            function previewImage2(event) {
                const input = event.target;
                const imgPreview = document.getElementById('preview2');
                const spinner = document.getElementById('spinner2');

                if (!input.files.length) return;

                const file = input.files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Archivo no válido');
                    input.value = '';
                    return;
                }

                if (spinner) spinner.style.display = 'flex';

                const objectURL = URL.createObjectURL(file);
                
                if (imgPreview.tagName === 'IMG') {
                    imgPreview.style.opacity = '0.5';
                    setTimeout(() => {
                        imgPreview.src = objectURL;
                        imgPreview.style.opacity = '1';
                        if (spinner) spinner.style.display = 'none';
                    }, 500);
                } else {
                    // Es un div, crear nueva imagen
                    const newImg = document.createElement('img');
                    newImg.id = 'preview2';
                    newImg.className = 'w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105';
                    newImg.src = objectURL;
                    newImg.alt = 'Nueva segunda imagen';
                    
                    setTimeout(() => {
                        imgPreview.parentNode.replaceChild(newImg, imgPreview);
                        if (spinner) spinner.style.display = 'none';
                    }, 500);
                }
            }

            function previewImage3(event) {
                const input = event.target;
                const imgPreview = document.getElementById('preview3');
                const spinner = document.getElementById('spinner3');

                if (!input.files.length) return;

                const file = input.files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Archivo no válido');
                    input.value = '';
                    return;
                }

                if (spinner) spinner.style.display = 'flex';

                const objectURL = URL.createObjectURL(file);
                
                if (imgPreview.tagName === 'IMG') {
                    imgPreview.style.opacity = '0.5';
                    setTimeout(() => {
                        imgPreview.src = objectURL;
                        imgPreview.style.opacity = '1';
                        if (spinner) spinner.style.display = 'none';
                    }, 500);
                } else {
                    // Es un div, crear nueva imagen
                    const newImg = document.createElement('img');
                    newImg.id = 'preview3';
                    newImg.className = 'w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105';
                    newImg.src = objectURL;
                    newImg.alt = 'Nueva tercera imagen';
                    
                    setTimeout(() => {
                        imgPreview.parentNode.replaceChild(newImg, imgPreview);
                        if (spinner) spinner.style.display = 'none';
                    }, 500);
                }
            }
    </script>
    @endpush

</div>