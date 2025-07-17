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
                Crear Nuevo Producto
            </h1>
            <p class="text-gray-600 text-lg">Complete la información para agregar un nuevo producto</p>
        </div>

        <x-validation-errors class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl max-w-4xl mx-auto" />

        <!-- Main Content Grid: Image on Left, Form on Right -->
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
                <!-- Left Column: Images Upload -->
                <div class="flex flex-col space-y-6">
                    <!-- Imagen Principal -->
                    <figure class="relative">
                        <div class="absolute top-4 right-4 z-20">
                            <x-label
                                class="flex items-center px-3 py-2 bg-white rounded-lg shadow-md cursor-pointer hover:bg-indigo-50 hover:shadow-lg transition-all duration-300 border border-gray-200 group">
                                <i
                                    class="mr-1 fas fa-camera text-indigo-500 group-hover:text-indigo-600 transition-colors text-sm"></i>
                                <span
                                    class="font-medium text-indigo-700 group-hover:text-indigo-800 transition-colors text-sm">Imagen
                                    1</span>
                                <input type="file" wire:model="image" class="hidden" accept="image/*" />
                            </x-label>
                        </div>
                        <div
                            class="aspect-square w-full rounded-xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-md relative group">
                            <img class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $image ? $image->temporaryUrl() : asset('/img/no-image.png') }}"
                                alt="Imagen Principal">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <figcaption class="font-medium text-center text-gray-700 mt-2">Imagen Principal *</figcaption>
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
                                <input type="file" wire:model="image2" class="hidden" accept="image/*" />
                            </x-label>
                        </div>
                        <div
                            class="aspect-square w-full rounded-xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-md relative group">
                            <img class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $image2 ? $image2->temporaryUrl() : asset('/img/no-image.png') }}"
                                alt="Segunda Imagen">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <figcaption class="font-medium text-center text-gray-700 mt-2">Segunda Imagen (opcional)
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
                                <input type="file" wire:model="image3" class="hidden" accept="image/*" />
                            </x-label>
                        </div>
                        <div
                            class="aspect-square w-full rounded-xl overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-md relative group">
                            <img class="w-full h-full object-cover object-center transition-all duration-500 group-hover:scale-105"
                                src="{{ $image3 ? $image3->temporaryUrl() : asset('/img/no-image.png') }}"
                                alt="Tercera Imagen">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <figcaption class="font-medium text-center text-gray-700 mt-2">Tercera Imagen (opcional)
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
                                    type="text" wire:model.defer="product.sku" placeholder="Código del Producto" />
                            </div>

                            <!-- Price Field -->
                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Precio') }}">
                                    <i class="fas fa-dollar-sign mr-2 text-green-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
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
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    type="text" wire:model.defer="product.name" placeholder="Nombre del Producto" />
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Descripción') }}">
                                    <i class="fas fa-align-left mr-2 text-purple-500"></i>
                                </x-label>
                                <x-textarea
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg resize-none py-3 text-lg"
                                    wire:model.defer="product.description" placeholder="Descripción del Producto"
                                    rows="4" />
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Características Generales') }}">
                                    <i class="fas fa-list-ul mr-2 text-teal-500"></i>
                                </x-label>
                                <x-textarea
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg resize-none py-3 text-lg"
                                    wire:model.defer="product.general_features"
                                    placeholder="Características generales del producto (opcional)" rows="3" />
                            </div>

                            <div class="space-y-3">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Preparación Recomendada') }}">
                                    <i class="fas fa-utensils mr-2 text-amber-500"></i>
                                </x-label>
                                <x-textarea
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg resize-none py-3 text-lg"
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
                        <div class="flex justify-end gap-6 pt-6 border-t border-gray-200">
                            <x-danger-button
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 font-semibold transform hover:scale-105 text-lg"
                                name="Cancelar" />
                            <x-button
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-lg"
                                name="Crear Producto" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>