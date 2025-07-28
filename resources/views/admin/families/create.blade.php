<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Familias',
        'route' => route('admin.families.index'),
    ],
    [
        'name' => 'Crear Familia',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.families.index') }}" type="secondary" name="Regresar"
            class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" />
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <!-- Header -->
        <div class="text-center mb-4 sm:mb-6 lg:mb-8 pt-4 sm:pt-6 lg:pt-8 px-3 sm:px-4">
            <h1
                class="text-lg sm:text-2xl lg:text-3xl xl:text-4xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
                Nueva Familia
            </h1>
            <p class="text-gray-600 text-xs sm:text-sm lg:text-base xl:text-lg">Crea una nueva familia para organizar
                tus productos</p>
        </div>

        <!-- Main Form Container -->
        <div class="max-w-2xl mx-3 sm:mx-4 lg:mx-auto">
            <form action="{{ route('admin.families.store') }}" method="POST" class="relative">
                @csrf

                <div
                    class="glass-effect rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-lg sm:shadow-xl lg:shadow-2xl p-3 sm:p-5 lg:p-8 relative overflow-hidden">
                    <!-- Decorative gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none">
                    </div>

                    <div class="relative">
                        <!-- Icon Header -->
                        <div class="text-center mb-4 sm:mb-6 lg:mb-8">
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 bg-gradient-to-r from-primary-500 to-secondary-600 rounded-lg sm:rounded-xl lg:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg">
                                <i class="fas fa-sitemap text-white text-lg sm:text-2xl lg:text-3xl"></i>
                            </div>
                            <h2 class="text-base sm:text-xl lg:text-2xl font-bold text-gray-800">Información de la
                                Familia</h2>
                            <p class="text-gray-600 text-xs sm:text-sm lg:text-base">Complete los datos para crear una
                                nueva familia</p>
                        </div>

                        <!-- Validation Errors -->
                        <x-validation-errors
                            class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg sm:rounded-xl" />

                        <!-- Form Fields -->
                        <div class="space-y-4 sm:space-y-6">
                            <div
                                class="bg-gray-50 rounded-lg sm:rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-6 border border-gray-200">
                                <x-label
                                    class="text-slate-700 font-semibold flex items-center text-sm sm:text-base lg:text-lg mb-3 sm:mb-4"
                                    value="{{ __('Nombre de la Familia') }}">
                                    <i class="fas fa-tag mr-2 sm:mr-3 text-secondary-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-secondary-400 focus:ring-secondary-200 rounded-lg sm:rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-2 sm:py-3 lg:py-4 text-sm sm:text-base lg:text-lg"
                                    placeholder="Ej: Electrónicos, Ropa, Hogar..." name="name"
                                    value="{{ old('name') }}" />
                                <p class="mt-2 text-xs sm:text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Ingrese un nombre descriptivo para la familia de productos
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-4 sm:pt-6 lg:pt-8 mt-4 sm:mt-6 lg:mt-8 border-t border-gray-200">
                            <a href="{{ route('admin.families.index') }}"
                                class="w-full sm:w-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 font-semibold transform hover:scale-105 text-white text-center text-sm sm:text-base">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-sm sm:text-base">
                                <i class="fas fa-plus mr-2 text-white"></i>
                                <span class="text-white">Crear Familia</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>