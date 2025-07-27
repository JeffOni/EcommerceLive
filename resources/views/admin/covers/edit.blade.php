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

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-primary-200/30 to-secondary-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-secondary-200/30 to-primary-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-64 h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-primary-100/40 to-secondary-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.covers.index') }}" type="secondary" name="Regresar" />
            </x-slot>

            <!-- Contenedor principal con backdrop blur -->
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header con gradiente -->
                <div class="px-8 py-6 bg-gradient-to-r from-primary-600 to-secondary-600">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 glass-effect rounded-xl">
                            <i class="text-xl text-white fas fa-edit"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Editar Portada</h2>
                            <p class="text-sm text-secondary-100">{{ $cover->title }}</p>
                        </div>
                    </div>
                </div>

                {{-- Formulario para editar la Portada --}}
                <form action="{{ route('admin.covers.update', $cover) }}" method="POST" enctype="multipart/form-data"
                    class="p-8">
                    @csrf
                    @method('PUT')

                    {{-- Sección de vista previa de imagen mejorada --}}
                    <div class="mb-8">
                        <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-800">
                            <i class="mr-2 fas fa-camera text-secondary-500"></i>
                            Vista Previa de la Imagen
                        </h3>

                        <figure class="relative group">
                            {{-- Botón para seleccionar una nueva imagen mejorado --}}
                            <div class="absolute z-10 top-4 right-4">
                                <label
                                    class="flex items-center px-6 py-3 text-white transition-all duration-300 transform shadow-lg cursor-pointer bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 rounded-xl hover:scale-105 hover:shadow-xl">
                                    <i class="mr-3 text-white fas fa-camera"></i>
                                    <span class="font-medium text-white">Cambiar Imagen</span>
                                    <input type="file" class="hidden" accept="image/*" name="image"
                                        onchange="previewImageWithAnimation(event, '#imgPreview', 'portada')" />
                                </label>
                            </div>

                            {{-- Imagen de vista previa mejorada --}}
                            <div class="relative overflow-hidden border-4 border-white shadow-xl rounded-2xl">
                                <!-- Spinner DENTRO del contenedor relativo -->
                                <div id="cover-image-spinner" style="display:none;"
                                    class="absolute inset-0 z-30 flex flex-col items-center justify-center pointer-events-auto">
                                    <div class="absolute inset-0 bg-black bg-opacity-60 rounded-2xl"></div>
                                    <div class="relative z-10 flex flex-col items-center justify-center">
                                        <div
                                            class="mb-4 border-4 rounded-full animate-spin h-14 w-14 border-secondary-200 border-t-primary-600">
                                        </div>
                                        <p class="text-base font-semibold text-center text-white drop-shadow">Subiendo
                                            portada...</p>
                                        <p class="mt-1 text-xs text-center text-white drop-shadow">Por favor espera</p>
                                    </div>
                                </div>
                                <img id="imgPreview" src="{{ $cover->image }}" alt="Portada"
                                    class="w-full aspect-[3/1] object-cover object-center transition-transform duration-500 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-t from-black/20 to-transparent group-hover:opacity-100">
                                </div>
                            </div>

                            <figcaption class="mt-4 text-center">
                                <span
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-secondary-100 text-secondary-700">
                                    <i class="mr-2 fas fa-info-circle"></i>
                                    Formato recomendado: 1200x400px (3:1)
                                </span>
                            </figcaption>
                        </figure>
                    </div>

                    {{-- Contenedor principal del formulario mejorado --}}
                    <div class="p-8 bg-white border border-gray-100 shadow-xl rounded-2xl">

                        {{-- Errores de validación con diseño mejorado --}}
                        <x-validation-errors class="mb-6" />

                        {{-- Grid de campos del formulario --}}
                        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                            {{-- Input de Título de la portada --}}
                            <div class="lg:col-span-2">
                                <label class="flex items-center block mb-3 text-sm font-semibold text-gray-700">
                                    <i class="mr-2 text-orange-500 fas fa-heading"></i>
                                    Título de la Portada
                                </label>
                                <input type="text" name="title" value="{{ old('title', $cover->title) }}"
                                    class="w-full px-4 py-3 transition-all duration-300 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:shadow-md focus:shadow-lg"
                                    placeholder="Ingrese un título atractivo para la portada">
                            </div>

                            {{-- Input de Fecha de Inicio --}}
                            <div>
                                <label class="flex items-center block mb-3 text-sm font-semibold text-gray-700">
                                    <i class="mr-2 text-green-500 fas fa-calendar-plus"></i>
                                    Fecha de Inicio
                                </label>
                                <input type="date" name="start_at"
                                    value="{{ old('start_at', $cover->start_at->format('Y-m-d')) }}"
                                    class="w-full px-4 py-3 transition-all duration-300 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:shadow-md focus:shadow-lg">
                            </div>

                            {{-- Input de Fecha de Fin --}}
                            <div>
                                <label class="flex items-center block mb-3 text-sm font-semibold text-gray-700">
                                    <i class="mr-2 text-red-500 fas fa-calendar-minus"></i>
                                    Fecha de Fin <span class="ml-1 text-xs text-gray-400">(Opcional)</span>
                                </label>
                                <input type="date" name="end_at"
                                    value="{{ old('end_at', $cover->end_at ? $cover->end_at->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-3 transition-all duration-300 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:shadow-md focus:shadow-lg">
                            </div>
                        </div>

                        {{-- Estado de la portada --}}
                        <div class="mt-8">
                            <label class="flex items-center block mb-4 text-sm font-semibold text-gray-700">
                                <i class="mr-2 text-blue-500 fas fa-toggle-on"></i>
                                Estado de la Portada
                            </label>
                            <div class="flex space-x-6">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="is_active" value="1" {{ $cover->is_active == 1 ? 'checked'
                                    : '' }}
                                    class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                                    <span
                                        class="flex items-center ml-3 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-orange-600">
                                        <i class="mr-2 text-green-500 fas fa-check-circle"></i>
                                        Activar Portada
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="is_active" value="0" {{ $cover->is_active == 0 ? 'checked'
                                    : '' }}
                                    class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                                    <span
                                        class="flex items-center ml-3 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-orange-600">
                                        <i class="mr-2 text-gray-400 fas fa-times-circle"></i>
                                        Desactivar Portada
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Botón de envío del formulario mejorado --}}
                        <div class="flex justify-end pt-6 mt-8 border-t border-gray-200">
                            <button type="submit"
                                class="flex items-center px-8 py-3 space-x-2 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-orange-700 hover:to-amber-700 rounded-xl hover:shadow-xl hover:scale-105">
                                <i class="mr-2 text-white fas fa-save"></i>
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