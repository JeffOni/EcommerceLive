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
        <!-- Decorative background elements -->
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-200/20 to-purple-200/20 rounded-full -translate-y-16 translate-x-16">
        </div>
        <div
            class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-200/20 to-cyan-200/20 rounded-full translate-y-12 -translate-x-12">
        </div>

        <!-- Header -->
        <div class="text-center mb-8 pt-8">
            <h1
                class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                Nueva Familia
            </h1>
            <p class="text-gray-600 text-lg">Crea una nueva familia para organizar tus productos</p>
        </div>

        <!-- Main Form Container -->
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('admin.families.store') }}" method="POST" class="relative">
                @csrf

                <div class="glass-effect rounded-3xl shadow-2xl p-8 relative overflow-hidden">
                    <!-- Decorative gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none">
                    </div>

                    <div class="relative">
                        <!-- Icon Header -->
                        <div class="text-center mb-8">
                            <div
                                class="w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fas fa-sitemap text-white text-3xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Información de la Familia</h2>
                            <p class="text-gray-600">Complete los datos para crear una nueva familia</p>
                        </div>

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl" />

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg mb-4"
                                    value="{{ __('Nombre de la Familia') }}">
                                    <i class="fas fa-tag mr-3 text-indigo-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-4 text-lg"
                                    placeholder="Ej: Electrónicos, Ropa, Hogar..." name="name"
                                    value="{{ old('name') }}" />
                                <p class="mt-2 text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Ingrese un nombre descriptivo para la familia de productos
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-200">
                            <a href="{{ route('admin.families.index') }}"
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 font-semibold transform hover:scale-105 text-white">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                            <button type="submit"
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-300 transform hover:scale-105">
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
