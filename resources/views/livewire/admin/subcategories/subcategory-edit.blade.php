<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-secondary-50">
    <!-- Header -->
    <div class="text-center mb-4 sm:mb-6 lg:mb-8 pt-4 sm:pt-6 lg:pt-8 px-3 sm:px-4">
        <h1
            class="text-lg sm:text-2xl lg:text-4xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
            Editar Subcategoría
        </h1>
        <p class="text-xs sm:text-base lg:text-lg text-gray-600">Modifica la información de la subcategoría "{{
            $subcategory->name }}"</p>
    </div>

    <div class="max-w-4xl mx-3 sm:mx-4 lg:mx-auto px-3 sm:px-6 pb-6 sm:pb-12">
        <div
            class="glass-effect rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-lg sm:shadow-2xl overflow-hidden relative">
            <!-- Content -->
            <div class="relative p-4 sm:p-6 lg:p-8">
                <form wire:submit.prevent="save" class="space-y-4 sm:space-y-6 lg:space-y-8">

                    <x-validation-errors
                        class="mb-4 sm:mb-6 lg:mb-8 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg sm:rounded-xl" />

                    <!-- Form Header responsive -->
                    <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 mb-4 sm:mb-6 lg:mb-8">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-primary-500 to-secondary-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                            <i class="fas fa-edit text-white text-sm sm:text-base lg:text-xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-sm sm:text-lg lg:text-2xl font-bold text-gray-800 truncate">Información de
                                la Subcategoría</h2>
                            <p class="text-xs sm:text-sm lg:text-base text-gray-600 truncate">Actualiza los datos de la
                                subcategoría</p>
                        </div>
                    </div>

                    <!-- Form Fields responsive -->
                    <div
                        class="bg-gray-50/50 rounded-lg sm:rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-6 border border-gray-200/60 space-y-4 sm:space-y-5 lg:space-y-6">
                        <!-- Family Selection -->
                        <div class="space-y-2 sm:space-y-3 lg:space-y-4">
                            <x-label
                                class="text-slate-700 font-semibold flex items-center text-sm sm:text-base lg:text-lg"
                                value="{{ __('Familia') }}">
                                <i
                                    class="fas fa-sitemap mr-1 sm:mr-2 text-secondary-500 text-xs sm:text-sm lg:text-base"></i>
                            </x-label>
                            <x-select name="family_id" id="family_id"
                                class="w-full border-gray-300 focus:border-secondary-400 focus:ring-secondary-200 rounded-lg sm:rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-2 sm:py-2.5 lg:py-3 text-sm sm:text-base lg:text-lg"
                                wire:model.live="subcategoryEdit.family_id">
                                <option value="" disabled>Seleccione una familia</option>
                                @foreach ($families as $family)
                                <option value="{{ $family->id }}">
                                    {{ $family->name }}
                                </option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Category Selection -->
                        <div class="space-y-2 sm:space-y-3 lg:space-y-4">
                            <x-label
                                class="text-slate-700 font-semibold flex items-center text-sm sm:text-base lg:text-lg"
                                value="{{ __('Categoría') }}">
                                <i
                                    class="fas fa-folder mr-1 sm:mr-2 text-secondary-500 text-xs sm:text-sm lg:text-base"></i>
                            </x-label>
                            <x-select name="category_id" id="category_id"
                                class="w-full border-gray-300 focus:border-secondary-400 focus:ring-secondary-200 rounded-lg sm:rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-2 sm:py-2.5 lg:py-3 text-sm sm:text-base lg:text-lg"
                                wire:model.live="subcategoryEdit.category_id">
                                <option value="" disabled>Seleccione una categoría</option>
                                @foreach ($this->categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Subcategory Name -->
                        <div class="space-y-2 sm:space-y-3 lg:space-y-4">
                            <x-label
                                class="text-slate-700 font-semibold flex items-center text-sm sm:text-base lg:text-lg"
                                value="{{ __('Nombre de la Subcategoría') }}">
                                <i
                                    class="fas fa-folder-open mr-1 sm:mr-2 text-coral-500 text-xs sm:text-sm lg:text-base"></i>
                            </x-label>
                            <x-input
                                class="w-full border-gray-300 focus:border-secondary-400 focus:ring-secondary-200 rounded-lg sm:rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-2 sm:py-2.5 lg:py-3 text-sm sm:text-base lg:text-lg"
                                placeholder="Ingrese el nombre de la subcategoría" name="name"
                                wire:model="subcategoryEdit.name" />
                        </div>
                    </div>

                    <!-- Action Buttons responsive -->
                    <div
                        class="flex flex-col space-y-3 sm:flex-row sm:justify-end sm:space-y-0 sm:space-x-4 lg:space-x-6 pt-4 sm:pt-6 border-t border-gray-200">
                        <button type="button" onclick="confirmDelete()"
                            class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 lg:px-8 lg:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold transform hover:scale-105 text-sm sm:text-base lg:text-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-trash text-white text-sm sm:text-base"></i>
                            <span class="text-white">Eliminar {{ $subcategory->name }}</span>
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 lg:px-8 lg:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-sm sm:text-base lg:text-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-save text-white text-sm sm:text-base"></i>
                            <span class="text-white">Actualizar Subcategoría</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- formulario para eliminar --}}
    <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>

    @push('js')
    <script>
        let confirmDelete = () => {
                // Sweet Alert 2
                Swal.fire({
                    title: "¿Estás Seguro?",
                    text: "No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, Bórralo!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form').submit();
                    }
                });
            }
    </script>
    @endpush
</div>