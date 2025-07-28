<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
        'route' => route('admin.covers.index'),
    ],
    [
        'name' => 'Crear Portada',
    ],
]">

    <!-- Fondo con gradiente responsive -->
    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.covers.index') }}" type="secondary" name="Regresar" />
            </x-slot>

            <!-- Header -->
            <div class="text-center mb-4 sm:mb-6 pt-4 sm:pt-6 px-3 sm:px-4">
                <h1
                    class="text-lg sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-2">
                    Nueva Portada
                </h1>
                <p class="text-xs sm:text-sm text-secondary-600">Crear una nueva portada para la página de inicio</p>
            </div>

            <!-- Contenedor principal responsivo -->
            <div
                class="max-w-4xl mx-2 sm:mx-4 lg:mx-auto glass-effect rounded-lg sm:rounded-xl lg:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden relative">
                <!-- Header con gradiente responsive -->
                <div
                    class="px-3 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6 bg-gradient-to-r from-primary-600 to-secondary-600">
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 glass-effect rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-image text-white text-sm sm:text-base lg:text-xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-sm sm:text-lg lg:text-2xl font-bold text-white truncate">Nueva Portada</h2>
                            <p class="text-xs sm:text-sm text-secondary-100 truncate">Crear portada para inicio</p>
                        </div>
                    </div>
                </div>

                {{-- Formulario responsive --}}
                <form action="{{ route('admin.covers.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-3 sm:p-6 lg:p-8">
                    @csrf

                    {{-- Sección de vista previa de imagen responsive --}}
                    <div class="mb-6 sm:mb-8">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                            <i class="fas fa-camera text-secondary-500 mr-2 text-sm sm:text-base"></i>
                            <span class="truncate">Vista Previa de la Imagen</span>
                        </h3>

                        <figure class="relative group">
                            {{-- Botón para seleccionar imagen responsive --}}
                            <div class="absolute top-2 right-2 sm:top-4 sm:right-4 z-10">
                                <label
                                    class="flex items-center px-3 py-2 sm:px-6 sm:py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white rounded-lg sm:rounded-xl shadow-lg cursor-pointer transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                                    <i class="fas fa-camera mr-2 sm:mr-3 text-white text-xs sm:text-sm"></i>
                                    <span class="font-medium text-white text-xs sm:text-sm">
                                        <span class="hidden sm:inline">Seleccionar </span>Imagen
                                    </span>
                                    <input type="file" class="hidden" accept="image/*" name="image"
                                        onchange="previewImageWithAnimation(event, '#imgPreview', 'portada')" />
                                </label>
                            </div>

                            {{-- Imagen de vista previa responsive --}}
                            <div
                                class="relative overflow-hidden rounded-lg sm:rounded-2xl shadow-lg sm:shadow-xl border-2 sm:border-4 border-white">
                                <!-- Spinner responsive -->
                                <div id="cover-image-spinner" style="display:none;"
                                    class="absolute inset-0 flex flex-col items-center justify-center z-30 pointer-events-auto">
                                    <div class="absolute inset-0 bg-black bg-opacity-60 rounded-lg sm:rounded-2xl">
                                    </div>
                                    <div class="relative z-10 flex flex-col items-center justify-center">
                                        <div
                                            class="animate-spin rounded-full h-8 w-8 sm:h-12 sm:w-12 lg:h-14 lg:w-14 border-2 sm:border-4 border-secondary-200 border-t-primary-600 mb-2 sm:mb-4">
                                        </div>
                                        <p
                                            class="text-white font-semibold text-sm sm:text-base text-center drop-shadow">
                                            Subiendo portada...</p>
                                        <p class="text-white text-xs sm:text-sm mt-1 text-center drop-shadow">Por favor
                                            espera</p>
                                    </div>
                                </div>
                                <img id="imgPreview" src="{{ asset('img/no-image-horizontal.png') }}" alt="Portada"
                                    class="w-full aspect-[3/1] object-cover object-center transition-transform duration-500 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                            </div>

                            <figcaption class="mt-3 sm:mt-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-secondary-100 text-secondary-700 rounded-lg text-xs sm:text-sm font-medium">
                                    <i class="fas fa-info-circle mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                                    <span class="hidden sm:inline">Formato recomendado: </span>1200x400px<span
                                        class="hidden sm:inline"> (3:1)</span>
                                </span>
                            </figcaption>
                        </figure>
                    </div>

                    {{-- Contenedor principal del formulario responsive --}}
                    <div
                        class="bg-white rounded-lg sm:rounded-2xl shadow-lg sm:shadow-xl border border-gray-100 p-4 sm:p-6 lg:p-8">

                        {{-- Errores de validación con diseño mejorado --}}
                        <x-validation-errors class="mb-4 sm:mb-6" />

                        {{-- Grid de campos del formulario responsive --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                            {{-- Input de Título de la portada --}}
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3 flex items-center">
                                    <i class="fas fa-heading text-secondary-500 mr-2 text-sm sm:text-base"></i>
                                    <span class="truncate">Título de la Portada</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 bg-white hover:shadow-md focus:shadow-lg text-sm sm:text-base"
                                    placeholder="Ingrese un título atractivo para la portada">
                            </div>

                            {{-- Input de Fecha de Inicio --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3 flex items-center">
                                    <i class="fas fa-calendar-plus text-green-500 mr-2 text-sm sm:text-base"></i>
                                    <span class="truncate">Fecha de Inicio</span>
                                </label>
                                <input type="date" name="start_at" value="{{ old('start_at', now()->format('Y-m-d')) }}"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 bg-white hover:shadow-md focus:shadow-lg text-sm sm:text-base">
                            </div>

                            {{-- Input de Fecha de Fin --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3 flex items-center">
                                    <i class="fas fa-calendar-minus text-red-500 mr-2 text-sm sm:text-base"></i>
                                    <span class="truncate">Fecha de Fin</span>
                                    <span class="text-gray-400 text-xs ml-1">(Opcional)</span>
                                </label>
                                <input type="date" name="end_at" value="{{ old('end_at') }}"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 bg-white hover:shadow-md focus:shadow-lg text-sm sm:text-base">
                            </div>
                        </div>

                        {{-- Estado de la portada responsive --}}
                        <div class="mt-6 sm:mt-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 sm:mb-4 flex items-center">
                                <i class="fas fa-toggle-on text-blue-500 mr-2 text-sm sm:text-base"></i>
                                <span class="truncate">Estado de la Portada</span>
                            </label>
                            <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-6">
                                <label class="flex items-center group cursor-pointer">
                                    <input type="radio" name="is_active" value="1" checked
                                        class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500 flex-shrink-0">
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700 group-hover:text-primary-600 transition-colors duration-200 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-sm sm:text-base"></i>
                                        Activar Portada
                                    </span>
                                </label>
                                <label class="flex items-center group cursor-pointer">
                                    <input type="radio" name="is_active" value="0"
                                        class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500 flex-shrink-0">
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700 group-hover:text-orange-600 transition-colors duration-200 flex items-center">
                                        <i class="fas fa-times-circle text-gray-400 mr-2 text-sm sm:text-base"></i>
                                        Desactivar Portada
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Botón de envío responsive --}}
                        <div
                            class="flex flex-col sm:flex-row sm:justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 sm:px-8 bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2 text-sm sm:text-base">
                                <i class="fas fa-plus mr-2 text-white text-sm sm:text-base"></i>
                                <span class="text-white">Crear Portada</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts JavaScript --}}
    {{-- Sección push añade el script al stack 'js' definido en el layout principal --}}
    @push('js')
    <script>
        // Elimina la función local y usa el sistema global
    </script>
    @endpush

</x-admin-layout>