<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50 relative overflow-hidden">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-primary-200/30 to-secondary-300/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-secondary-200/30 to-primary-300/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-primary-100/40 to-secondary-100/40 rounded-full blur-2xl">
            </div>
        </div>

        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.covers.create') }}" type="primary" name="Nueva Portada" />
            </x-slot>

            <!-- Contenedor principal con backdrop blur -->
            <div class="glass-effect rounded-3xl shadow-2xl mx-4 my-8 overflow-hidden">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-primary-600 to-secondary-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="fas fa-image text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Gestión de Portadas</h2>
                                <p class="text-secondary-100 text-sm">Administra las portadas del sitio web con función
                                    de
                                    arrastrar y soltar</p>
                            </div>
                        </div>
                        <div class="text-white/80 text-sm">
                            <i class="fas fa-images mr-1"></i>
                            {{ count($covers) }} portadas
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    @if (count($covers) > 0)
                    <!-- Lista de portadas mejorada con drag & drop -->
                    <div
                        class="mb-6 p-4 bg-gradient-to-r from-secondary-50 to-primary-50 rounded-xl border border-secondary-200">
                        <div class="flex items-center space-x-2 text-secondary-700">
                            <i class="fas fa-info-circle"></i>
                            <span class="text-sm font-medium">Puedes arrastrar y soltar las portadas para cambiar su
                                orden de visualización</span>
                        </div>
                    </div>

                    <ul class="space-y-6" id="covers-list">
                        @foreach ($covers as $cover)
                        <li class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 cursor-move overflow-hidden border border-gray-100"
                            data-id="{{ $cover->id }}">
                            <div class="lg:flex">
                                <!-- Imagen de la portada -->
                                <div class="lg:w-80 relative overflow-hidden">
                                    <img src="{{ $cover->image }}"
                                        class="w-full aspect-[3/1] lg:aspect-square object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                        alt="Imagen de Portada">

                                    <!-- Overlay con ícono de arrastrar -->
                                    <div
                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <div class="glass-effect rounded-xl p-3">
                                            <i class="fas fa-arrows-alt text-white text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Badge de estado -->
                                    <div class="absolute top-4 left-4">
                                        @if ($cover->is_active)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Activo
                                        </span>
                                        @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Inactivo
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Información de la portada -->
                                <div class="flex-1 p-6 lg:p-8">
                                    <div
                                        class="space-y-6 lg:space-y-0 lg:flex lg:items-center lg:justify-between lg:h-full">
                                        <!-- Título y descripción -->
                                        <div class="lg:flex-1">
                                            <h3
                                                class="text-xl font-bold text-gray-800 mb-2 group-hover:text-primary-600 transition-colors duration-300">
                                                {{ $cover->title }}
                                            </h3>
                                            <p class="text-gray-600 text-sm">ID: #{{ $cover->id }}</p>
                                        </div>

                                        <!-- Información de fechas -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:flex-1 lg:max-w-lg">
                                            <div
                                                class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border border-green-100">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <i class="fas fa-calendar-plus text-green-500"></i>
                                                    <span class="text-sm font-semibold text-green-700">Fecha de
                                                        Inicio</span>
                                                </div>
                                                <p class="text-sm text-green-600 font-medium">
                                                    {{ $cover->start_at->format('d/m/Y') }}</p>
                                            </div>

                                            <div
                                                class="bg-gradient-to-r from-red-50 to-rose-50 p-4 rounded-xl border border-red-100">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <i class="fas fa-calendar-minus text-red-500"></i>
                                                    <span class="text-sm font-semibold text-red-700">Fecha de
                                                        Fin</span>
                                                </div>
                                                <p class="text-sm text-red-600 font-medium">
                                                    {{ $cover->end_at ? $cover->end_at->format('d/m/Y') : 'Sin fecha
                                                    límite' }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Botón de acción -->
                                        <div class="lg:flex-shrink-0">
                                            <a href="{{ route('admin.covers.edit', $cover) }}"
                                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                                <i class="fas fa-edit mr-2 text-white"></i>
                                                <span class="text-white">Editar</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Indicador de arrastre -->
                            <div
                                class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div
                                    class="bg-secondary-100 text-secondary-600 px-3 py-1 rounded-lg text-xs font-medium">
                                    <i class="fas fa-grip-vertical mr-1"></i>
                                    Arrastrar
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <!-- Estado vacío mejorado -->
                    <div class="text-center py-16">
                        <div
                            class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-full mb-6">
                            <i class="fas fa-image text-4xl text-secondary-500"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">No hay portadas registradas</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">Todavía no has creado ninguna portada. Las
                            portadas son elementos visuales que se muestran en la página principal de tu sitio web.
                        </p>
                        <a href="{{ route('admin.covers.create') }}"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-plus mr-3 text-white"></i>
                            <span class="text-white">Crear Primera Portada</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-blue-200 {
            background-color: #bfdbfe !important;
        }
    </style>

    @push('js')
    {{-- script de libreria sortable --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.6/Sortable.min.js"></script>
    {{-- script de funcion sortable --}}
    <script>
        new Sortable(document.getElementById('covers-list'), {
                animation: 150, // Duración de la animación al mover elementos
                ghostClass: 'bg-blue-200', // Clase para el elemento fantasma mientras se arrastra
                store: {
                    // Guardar el orden de los elementos en el almacenamiento local y enviar al backend
                    set: function(sortable) {
                        // Genera un array de objetos {id, order} según el nuevo orden
                        const sorts = sortable.toArray().map((id, index) => ({
                            id: id,
                            order: index + 1
                        }));
                        // Guarda el orden en localStorage para persistencia en el frontend
                        localStorage.setItem('covers-order', JSON.stringify(sorts));
                        // Envía el nuevo orden al backend mediante una petición POST
                        axios.post("{{ route('api.covers-list') }}", {
                            sorts: sorts
                        }).catch((error) => {
                            // Muestra un error en consola si la petición falla
                            console.error('Error al guardar el orden:', error);
                        });
                    },
                    // Recuperar el orden de los elementos del almacenamiento local
                    get: function(sortable) {
                        // Obtiene el array de objetos guardado en localStorage
                        const sorts = localStorage.getItem('covers-order');
                        // Devuelve solo los IDs para que Sortable pueda reconstruir el orden
                        return sorts ? JSON.parse(sorts).map(obj => obj.id) : [];
                    }
                },
            });
    </script>
    @endpush

</x-admin-layout>