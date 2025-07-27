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
        <!-- Decorative background elements -->
        <div
            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-200/20 to-secondary-200/20 rounded-full -translate-y-16 translate-x-16">
        </div>
        <div
            class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-secondary-200/20 to-primary-200/20 rounded-full translate-y-12 -translate-x-12">
        </div>

        <!-- Header -->
        <div class="text-center mb-8 pt-8">
            <h1
                class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
                Crear Nueva Categoría
            </h1>
            <p class="text-gray-600 text-lg">Agrega una nueva categoría al sistema</p>
        </div>

        <div class="max-w-4xl mx-auto px-6 pb-12">
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden relative">
                <!-- Decorative gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

                <!-- Content -->
                <div class="relative p-8">
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <x-validation-errors class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl" />

                        <!-- Form Header -->
                        <div class="flex items-center space-x-4 mb-8">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-folder text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Información de la Categoría</h2>
                                <p class="text-gray-600">Complete los datos para crear la categoría</p>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-200/60 space-y-6">
                            <!-- Family Selection -->
                            <div class="space-y-4">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Familia') }}">
                                    <i class="fas fa-sitemap mr-2 text-secondary-500"></i>
                                </x-label>
                                <x-select name="family_id" id="family_id"
                                    class="w-full border-gray-300 focus:border-secondary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg">
                                    <option value="" disabled selected>Seleccione una familia</option>
                                    @foreach ($families as $family)
                                    <option value="{{ $family->id }}" @selected(old('family_id')==$family->id)>
                                        {{ $family->name }}
                                    </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <!-- Category Name -->
                            <div class="space-y-4">
                                <x-label class="text-slate-700 font-semibold flex items-center text-lg"
                                    value="{{ __('Nombre de la Categoría') }}">
                                    <i class="fas fa-folder mr-2 text-secondary-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full border-gray-300 focus:border-secondary-400 focus:ring-secondary-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 text-lg"
                                    placeholder="Ingrese el nombre de la categoría" name="name"
                                    value="{{ old('name') }}" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 text-lg flex items-center space-x-2">
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