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
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-amber-50 relative overflow-hidden">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-orange-200/30 to-amber-300/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-amber-200/30 to-orange-300/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-orange-100/40 to-amber-100/40 rounded-full blur-2xl">
            </div>
        </div>

        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.covers.index') }}" type="secondary" name="Regresar" />
            </x-slot>

            <!-- Contenedor principal con backdrop blur -->
            <div
                class="backdrop-blur-sm bg-white/70 rounded-3xl shadow-2xl border border-white/20 mx-4 my-8 overflow-hidden">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-orange-600 to-amber-600 px-8 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Editar Portada</h2>
                            <p class="text-orange-100 text-sm">{{ $cover->title }}</p>
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
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-camera text-orange-500 mr-2"></i>
                            Vista Previa de la Imagen
                        </h3>

                        <figure class="relative group">
                            {{-- Botón para seleccionar una nueva imagen mejorado --}}
                            <div class="absolute top-4 right-4 z-10">
                                <label
                                    class="flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white rounded-xl shadow-lg cursor-pointer transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                                    <i class="fas fa-camera mr-3 text-white"></i>
                                    <span class="font-medium text-white">Cambiar Imagen</span>
                                    <input type="file" class="hidden" accept="image/*" name="image"
                                        onchange="previewImage(event, '#imgPreview')" />
                                </label>
                            </div>

                            {{-- Imagen de vista previa mejorada --}}
                            <div class="relative overflow-hidden rounded-2xl shadow-xl border-4 border-white">
                                <img id="imgPreview" src="{{ $cover->image }}" alt="Portada"
                                    class="w-full aspect-[3/1] object-cover object-center transition-transform duration-500 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                            </div>

                            <figcaption class="mt-4 text-center">
                                <span
                                    class="inline-flex items-center px-4 py-2 bg-orange-100 text-orange-700 rounded-lg text-sm font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Formato recomendado: 1200x400px (3:1)
                                </span>
                            </figcaption>
                        </figure>
                    </div>

                    {{-- Contenedor principal del formulario mejorado --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

                        {{-- Errores de validación con diseño mejorado --}}
                        <x-validation-errors class="mb-6" />

                        {{-- Grid de campos del formulario --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Input de Título de la portada --}}
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-heading text-orange-500 mr-2"></i>
                                    Título de la Portada
                                </label>
                                <input type="text" name="title" value="{{ old('title', $cover->title) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 bg-white hover:shadow-md focus:shadow-lg"
                                    placeholder="Ingrese un título atractivo para la portada">
                            </div>

                            {{-- Input de Fecha de Inicio --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-calendar-plus text-green-500 mr-2"></i>
                                    Fecha de Inicio
                                </label>
                                <input type="date" name="start_at"
                                    value="{{ old('start_at', $cover->start_at->format('Y-m-d')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 bg-white hover:shadow-md focus:shadow-lg">
                            </div>

                            {{-- Input de Fecha de Fin --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-calendar-minus text-red-500 mr-2"></i>
                                    Fecha de Fin <span class="text-gray-400 text-xs ml-1">(Opcional)</span>
                                </label>
                                <input type="date" name="end_at"
                                    value="{{ old('end_at', $cover->end_at ? $cover->end_at->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 bg-white hover:shadow-md focus:shadow-lg">
                            </div>
                        </div>

                        {{-- Estado de la portada --}}
                        <div class="mt-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-toggle-on text-blue-500 mr-2"></i>
                                Estado de la Portada
                            </label>
                            <div class="flex space-x-6">
                                <label class="flex items-center group cursor-pointer">
                                    <input type="radio" name="is_active" value="1"
                                        {{ $cover->is_active == 1 ? 'checked' : '' }}
                                        class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700 group-hover:text-orange-600 transition-colors duration-200 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Activar Portada
                                    </span>
                                </label>
                                <label class="flex items-center group cursor-pointer">
                                    <input type="radio" name="is_active" value="0"
                                        {{ $cover->is_active == 0 ? 'checked' : '' }}
                                        class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700 group-hover:text-orange-600 transition-colors duration-200 flex items-center">
                                        <i class="fas fa-times-circle text-gray-400 mr-2"></i>
                                        Desactivar Portada
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Botón de envío del formulario mejorado --}}
                        <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                                <i class="fas fa-save mr-2 text-white"></i>
                                <span class="text-white">Actualizar Portada</span>
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
            /**
             * Función para mostrar la vista previa de una imagen antes de subirla
             * @param {Event} event - El evento de cambio del input file
             * @param {String} querySelector - Selector CSS del elemento img donde se mostrará la vista previa
             */
            function previewImage(event, querySelector) {

                //Recuperamos el input que desencadeno la acción
                let input = event.target;

                //Recuperamos la etiqueta img donde cargaremos la imagen
                let imgPreview = document.querySelector(querySelector);

                // Verificamos si existe una imagen seleccionada
                if (!input.files.length) return

                //Recuperamos el archivo subido
                let file = input.files[0];

                //Creamos la url temporal para la vista previa
                let objectURL = URL.createObjectURL(file);

                //Modificamos el atributo src de la etiqueta img
                imgPreview.src = objectURL;
            }
        </script>
    @endpush
</x-admin-layout>
