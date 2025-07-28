<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
        'route' => route('admin.categories.index'),
    ],
    [
        'name' => 'Crear Categoría',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.categories.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 relative overflow-hidden">
        <!-- Header -->
        <div class="text-center mb-4 sm:mb-6 lg:mb-8 pt-4 sm:pt-6 lg:pt-8 px-3 sm:px-4">
            <h1
                class="text-lg sm:text-2xl lg:text-3xl xl:text-4xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
                Crear Nueva Categoría
            </h1>
            <p class="text-gray-600 text-xs sm:text-sm lg:text-base xl:text-lg">Agrega una nueva categoría al sistema
            </p>
        </div>

        <div class="max-w-4xl mx-3 sm:mx-4 lg:mx-auto px-3 sm:px-4 lg:px-6 pb-6 sm:pb-8 lg:pb-12">
            <div
                class="glass-effect rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-lg sm:shadow-xl lg:shadow-2xl overflow-hidden relative">
                <!-- Decorative gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

                <!-- Content -->
                <div class="relative p-3 sm:p-5 lg:p-8">
                    <form action="{{ route('admin.categories.store') }}" method="POST"
                        class="space-y-4 sm:space-y-6 lg:space-y-8">
                        @csrf

                        <x-validation-errors
                            class="mb-4 sm:mb-6 lg:mb-8 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg sm:rounded-xl" />

                        <!-- Form Header -->
                        <div class="flex items-center space-x-3 sm:space-x-4 mb-4 sm:mb-6 lg:mb-8">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-primary-500 to-secondary-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                                <i class="fas fa-folder text-white text-sm sm:text-lg lg:text-xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-base sm:text-xl lg:text-2xl font-bold text-gray-800">Información de la
                                    Categoría</h2>
                                <p class="text-gray-600 text-xs sm:text-sm lg:text-base">Complete los datos para crear
                                    la categoría</p>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div
                            class="bg-gray-50/50 rounded-lg sm:rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-6 border border-gray-200/60 space-y-4 sm:space-y-6">
                            <!-- Family Selection -->
                            <div class="space-y-2 sm:space-y-4">
                                <x-label
                                    class="text-slate-700 font-semibold flex items-center text-sm sm:text-base lg:text-lg"
                                    value="{{ __('Familia') }}">
                                    <i class="fas fa-sitemap mr-2 text-secondary-500"></i>
                                </x-label>
                                <x-select name="family_id" id="family_id"
                                    class="w-full border-gray-300 focus:border-secondary-400 focus:ring-secondary-200 rounded-lg sm:rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-2 sm:py-3 text-sm sm:text-base lg:text-lg">
                                    <option value="" disabled selected>Seleccione una familia</option>
                                    @foreach ($families as $family)
                                    <option value="{{ $family->id }}" @selected(old('family_id')==$family->id)>
                                        {{ $family->name }}
                                    </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <!-- Category Name -->
                            <div class="space-y-2 sm:space-y-4">
                                <x-label
                                    class="text-slate-700 font-semibold flex items-center text-sm sm:text-base lg:text-lg"
                                    value="{{ __('Nombre de la Categoría') }}">
                                    <i class="fas fa-folder mr-2 text-secondary-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-secondary-400 focus:ring-secondary-200 rounded-lg sm:rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-2 sm:py-3 text-sm sm:text-base lg:text-lg"
                                    placeholder="Ingrese el nombre de la categoría" name="name"
                                    value="{{ old('name') }}" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-4 sm:pt-6 mt-4 sm:mt-6 border-t border-gray-200">
                            <a href="{{ route('admin.categories.index') }}"
                                class="w-full sm:w-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 font-semibold transform hover:scale-105 text-white text-center text-sm sm:text-base lg:text-lg">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-sm sm:text-base lg:text-lg flex items-center justify-center space-x-2">
                                <i class="fas fa-plus mr-2 text-white"></i>
                                <span class="text-white">Crear Categoría</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>