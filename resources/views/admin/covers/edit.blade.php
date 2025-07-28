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
        'name' => 'Editar ' . $cover->title,
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos responsive -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <!-- Elementos decorativos de fondo adaptados -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-20 -right-20 sm:-top-40 sm:-right-40 w-48 h-48 sm:w-96 sm:h-96 bg-gradient-to-br from-primary-200/30 to-secondary-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-20 -left-20 sm:-bottom-40 sm:-left-40 w-48 h-48 sm:w-96 sm:h-96 bg-gradient-to-tr from-secondary-200/30 to-primary-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-32 h-32 sm:w-64 sm:h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-primary-100/40 to-secondary-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.covers.index') }}" type="secondary" name="Regresar" />
            </x-slot>

            <!-- Contenedor principal responsive con backdrop blur -->
            <div
                class="mx-2 sm:mx-4 my-4 sm:my-8 overflow-hidden shadow-lg sm:shadow-2xl glass-effect rounded-xl sm:rounded-3xl">
                <!-- Header responsive con gradiente -->
                <div
                    class="px-4 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6 bg-gradient-to-r from-primary-600 to-secondary-600">
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <div class="p-2 sm:p-3 glass-effect rounded-lg sm:rounded-xl flex-shrink-0">
                            <i class="text-sm sm:text-lg lg:text-xl text-white fas fa-edit"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">Editar Portada</h2>
                            <p class="text-xs sm:text-sm text-secondary-100 truncate">{{ $cover->title }}</p>
                        </div>
                    </div>
                </div>

                {{-- Formulario responsive para editar la Portada --}}
                <form action="{{ route('admin.covers.update', $cover) }}" method="POST" enctype="multipart/form-data"
                    class="p-3 sm:p-6 lg:p-8">
                    @csrf
                    @method('PUT')

                    {{-- Sección de vista previa de imagen responsive --}}
                    <div class="mb-6 sm:mb-8">
                        <h3 class="flex items-center mb-3 sm:mb-4 text-base sm:text-lg font-semibold text-gray-800">
                            <i class="mr-2 fas fa-camera text-secondary-500 text-sm sm:text-base"></i>
                            <span class="truncate">Vista Previa de la Imagen</span>
                        </h3>

                        <figure class="relative group">
                            {{-- Botón para seleccionar nueva imagen responsive --}}
                            <div class="absolute z-10 top-2 right-2 sm:top-4 sm:right-4">
                                <label
                                    class="flex items-center px-3 py-2 sm:px-6 sm:py-3 text-white transition-all duration-300 transform shadow-lg cursor-pointer bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 rounded-lg sm:rounded-xl hover:scale-105 hover:shadow-xl">
                                    <i class="mr-2 sm:mr-3 text-white fas fa-camera text-xs sm:text-sm"></i>
                                    <span class="font-medium text-white text-xs sm:text-sm">
                                        <span class="hidden sm:inline">Cambiar </span>Imagen
                                    </span>
                                    <input type="file" class="hidden" accept="image/*" name="image"
                                        onchange="previewImageWithAnimation(event, '#imgPreview', 'portada')" />
                                </label>
                            </div>

                            {{-- Imagen de vista previa responsive --}}
                            <div
                                class="relative overflow-hidden border-2 sm:border-4 border-white shadow-lg sm:shadow-xl rounded-lg sm:rounded-2xl">
                                <!-- Spinner responsive DENTRO del contenedor relativo -->
                                <div id="cover-image-spinner" style="display:none;"
                                    class="absolute inset-0 z-30 flex flex-col items-center justify-center pointer-events-auto">
                                    <div class="absolute inset-0 bg-black bg-opacity-60 rounded-lg sm:rounded-2xl">
                                    </div>
                                    <div class="relative z-10 flex flex-col items-center justify-center">
                                        <div
                                            class="mb-2 sm:mb-4 border-2 sm:border-4 rounded-full animate-spin h-8 w-8 sm:h-12 sm:w-12 lg:h-14 lg:w-14 border-secondary-200 border-t-primary-600">
                                        </div>
                                        <p
                                            class="text-sm sm:text-base font-semibold text-center text-white drop-shadow">
                                            Subiendo
                                            portada...</p>
                                        <p class="mt-1 text-xs sm:text-sm text-center text-white drop-shadow">Por favor
                                            espera</p>
                                    </div>
                                </div>
                                <img id="imgPreview" src="{{ $cover->image }}" alt="Portada"
                                    class="w-full aspect-[3/1] object-cover object-center transition-transform duration-500 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-t from-black/20 to-transparent group-hover:opacity-100">
                                </div>
                            </div>

                            <figcaption class="mt-3 sm:mt-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium rounded-lg bg-secondary-100 text-secondary-700">
                                    <i class="mr-1 sm:mr-2 fas fa-info-circle text-xs sm:text-sm"></i>
                                    <span class="hidden sm:inline">Formato recomendado: </span>1200x400px<span
                                        class="hidden sm:inline"> (3:1)</span>
                                </span>
                            </figcaption>
                        </figure>
                    </div>

                    {{-- Contenedor principal del formulario responsive --}}
                    <div
                        class="p-4 sm:p-6 lg:p-8 bg-white border border-gray-100 shadow-lg sm:shadow-xl rounded-lg sm:rounded-2xl">

                        {{-- Errores de validación responsive --}}
                        <x-validation-errors class="mb-4 sm:mb-6" />

                        {{-- Grid de campos del formulario responsive --}}
                        <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2 lg:gap-8">
                            {{-- Input de Título de la portada --}}
                            <div class="lg:col-span-2">
                                <label class="flex items-center mb-2 sm:mb-3 text-sm font-semibold text-gray-700">
                                    <i class="mr-2 text-orange-500 fas fa-heading text-sm sm:text-base"></i>
                                    <span class="truncate">Título de la Portada</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title', $cover->title) }}"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 transition-all duration-300 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:shadow-md focus:shadow-lg text-sm sm:text-base"
                                    placeholder="Ingrese un título atractivo para la portada">
                            </div>

                            {{-- Input de Fecha de Inicio --}}
                            <div>
                                <label class="flex items-center mb-2 sm:mb-3 text-sm font-semibold text-gray-700">
                                    <i class="mr-2 text-green-500 fas fa-calendar-plus text-sm sm:text-base"></i>
                                    <span class="truncate">Fecha de Inicio</span>
                                </label>
                                <input type="date" name="start_at"
                                    value="{{ old('start_at', $cover->start_at->format('Y-m-d')) }}"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 transition-all duration-300 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:shadow-md focus:shadow-lg text-sm sm:text-base">
                            </div>

                            {{-- Input de Fecha de Fin --}}
                            <div>
                                <label class="flex items-center mb-2 sm:mb-3 text-sm font-semibold text-gray-700">
                                    <i class="mr-2 text-red-500 fas fa-calendar-minus text-sm sm:text-base"></i>
                                    <span class="truncate">Fecha de Fin</span>
                                    <span class="ml-1 text-xs text-gray-400">(Opcional)</span>
                                </label>
                                <input type="date" name="end_at"
                                    value="{{ old('end_at', $cover->end_at ? $cover->end_at->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 transition-all duration-300 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:shadow-md focus:shadow-lg text-sm sm:text-base">
                            </div>
                        </div>

                        {{-- Estado de la portada responsive --}}
                        <div class="mt-6 sm:mt-8">
                            <label class="flex items-center mb-3 sm:mb-4 text-sm font-semibold text-gray-700">
                                <i class="mr-2 text-blue-500 fas fa-toggle-on text-sm sm:text-base"></i>
                                <span class="truncate">Estado de la Portada</span>
                            </label>
                            <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-6">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="is_active" value="1" {{ $cover->is_active == 1 ? 'checked'
                                    : '' }}
                                    class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500 flex-shrink-0">
                                    <span
                                        class="flex items-center ml-3 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-orange-600">
                                        <i class="mr-2 text-green-500 fas fa-check-circle text-sm sm:text-base"></i>
                                        Activar Portada
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="is_active" value="0" {{ $cover->is_active == 0 ? 'checked'
                                    : '' }}
                                    class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500 flex-shrink-0">
                                    <span
                                        class="flex items-center ml-3 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-orange-600">
                                        <i class="mr-2 text-gray-400 fas fa-times-circle text-sm sm:text-base"></i>
                                        Desactivar Portada
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Botón de envío responsive --}}
                        <div
                            class="flex flex-col sm:flex-row sm:justify-end pt-4 sm:pt-6 mt-6 sm:mt-8 border-t border-gray-200">
                            <button type="submit"
                                class="w-full sm:w-auto flex items-center justify-center px-6 py-3 sm:px-8 space-x-2 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-orange-700 hover:to-amber-700 rounded-lg sm:rounded-xl hover:shadow-xl hover:scale-105 text-sm sm:text-base">
                                <i class="mr-2 text-white fas fa-save text-sm sm:text-base"></i>
                                <span class="text-white">Actualizar Portada</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts JavaScript --}}
    {{-- La función previewImageWithAnimation está disponible globalmente desde image-upload-handler.js --}}
</x-admin-layout>