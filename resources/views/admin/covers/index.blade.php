<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
    ],
]">

    <!-- Fondo con gradiente responsive -->
    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.covers.create') }}" type="primary" name="Nueva Portada" />
            </x-slot>

            <!-- Header -->
            <div class="text-center mb-4 sm:mb-6 pt-4 sm:pt-6 px-3 sm:px-4">
                <h1
                    class="text-lg sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-900 to-secondary-500 bg-clip-text text-transparent mb-2">
                    Gestión de Portadas
                </h1>
                <p class="text-xs sm:text-sm text-secondary-600">Administra las portadas del sitio web con función de
                    arrastrar y soltar</p>
            </div>

            <!-- Contenedor principal responsivo -->
            <div
                class="max-w-5xl mx-3 sm:mx-4 lg:mx-auto glass-effect rounded-xl sm:rounded-2xl shadow-lg overflow-hidden relative">
                <!-- Header con gradiente responsive -->
                <div
                    class="px-3 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6 bg-gradient-to-r from-primary-900 to-secondary-500">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 glass-effect rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="text-sm sm:text-base lg:text-xl text-white fas fa-image"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-sm sm:text-lg lg:text-2xl font-bold text-white truncate">Portadas</h2>
                                <p class="text-xs sm:text-sm text-secondary-100 truncate">Arrastrar y soltar</p>
                            </div>
                        </div>
                        <div class="text-xs sm:text-sm text-white/80 flex-shrink-0">
                            <i class="mr-1 fas fa-images"></i>
                            {{ count($covers) }} portadas
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-5 lg:p-8">
                    @if (count($covers) > 0)
                    <!-- Lista de portadas responsive -->
                    <div
                        class="p-3 sm:p-4 mb-4 sm:mb-6 border bg-gradient-to-r from-secondary-50 to-primary-50 rounded-lg sm:rounded-xl border-secondary-200">
                        <div class="flex items-center space-x-2 text-secondary-700">
                            <i class="fas fa-info-circle text-sm"></i>
                            <span class="text-xs sm:text-sm font-medium">Puedes arrastrar y soltar las portadas para
                                cambiar su
                                orden de visualización</span>
                        </div>
                    </div>

                    <ul class="space-y-4 sm:space-y-6" id="covers-list">
                        @foreach ($covers as $cover)
                        <li class="relative overflow-hidden transition-all duration-500 bg-white border border-gray-100 shadow-lg cursor-move group rounded-2xl hover:shadow-2xl"
                            data-id="{{ $cover->id }}">
                            <div class="lg:flex">
                                <!-- Imagen de la portada -->
                                <div class="relative overflow-hidden lg:w-80">
                                    <img src="{{ $cover->image }}"
                                        class="w-full aspect-[3/1] lg:aspect-square object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                        alt="Imagen de Portada">

                                    <!-- Overlay con ícono de arrastrar -->
                                    <div
                                        class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 opacity-0 bg-black/20 group-hover:opacity-100">
                                        <div class="p-3 glass-effect rounded-xl">
                                            <i class="text-xl text-white fas fa-arrows-alt"></i>
                                        </div>
                                    </div>

                                    <!-- Badge de estado -->
                                    <div class="absolute top-4 left-4">
                                        @if ($cover->is_active)
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 border border-green-200 rounded-full">
                                            <i class="mr-1 fas fa-check-circle"></i>
                                            Activo
                                        </span>
                                        @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-800 bg-red-100 border border-red-200 rounded-full">
                                            <i class="mr-1 fas fa-times-circle"></i>
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
                                                class="mb-2 text-xl font-bold text-gray-800 transition-colors duration-300 group-hover:text-primary-600">
                                                {{ $cover->title }}
                                            </h3>
                                            <p class="text-sm text-gray-600">ID: #{{ $cover->id }}</p>
                                        </div>

                                        <!-- Información de fechas -->
                                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:flex-1 lg:max-w-lg">
                                            <div
                                                class="p-4 border border-green-100 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                                <div class="flex items-center mb-2 space-x-2">
                                                    <i class="text-green-500 fas fa-calendar-plus"></i>
                                                    <span class="text-sm font-semibold text-green-700">Fecha de
                                                        Inicio</span>
                                                </div>
                                                <p class="text-sm font-medium text-green-600">
                                                    {{ $cover->start_at->format('d/m/Y') }}</p>
                                            </div>

                                            <div
                                                class="p-4 border border-red-100 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl">
                                                <div class="flex items-center mb-2 space-x-2">
                                                    <i class="text-red-500 fas fa-calendar-minus"></i>
                                                    <span class="text-sm font-semibold text-red-700">Fecha de
                                                        Fin</span>
                                                </div>
                                                <p class="text-sm font-medium text-red-600">
                                                    {{ $cover->end_at ? $cover->end_at->format('d/m/Y') : 'Sin fecha
                                                    límite' }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Botón de acción -->
                                        <div class="lg:flex-shrink-0">
                                            <a href="{{ route('admin.covers.edit', $cover) }}"
                                                class="inline-flex items-center px-6 py-3 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 rounded-xl hover:shadow-xl hover:scale-105">
                                                <i class="mr-2 text-white fas fa-edit"></i>
                                                <span class="text-white">Editar</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Indicador de arrastre -->
                            <div
                                class="absolute transition-opacity duration-300 opacity-0 top-4 right-4 group-hover:opacity-100">
                                <div
                                    class="px-3 py-1 text-xs font-medium rounded-lg bg-secondary-100 text-secondary-600">
                                    <i class="mr-1 fas fa-grip-vertical"></i>
                                    Arrastrar
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <!-- Estado vacío mejorado -->
                    <div class="py-16 text-center">
                        <div
                            class="inline-flex items-center justify-center w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-primary-100 to-secondary-100">
                            <i class="text-4xl fas fa-image text-secondary-500"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-semibold text-gray-800">No hay portadas registradas</h3>
                        <p class="max-w-md mx-auto mb-8 text-gray-600">Todavía no has creado ninguna portada. Las
                            portadas son elementos visuales que se muestran en la página principal de tu sitio web.
                        </p>
                        <a href="{{ route('admin.covers.create') }}"
                            class="inline-flex items-center px-8 py-3 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 rounded-xl hover:shadow-xl hover:scale-105">
                            <i class="mr-3 text-white fas fa-plus"></i>
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