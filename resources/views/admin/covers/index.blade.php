<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.covers.create') }}" type="primary" name="Nueva Portada" />
    </x-slot>

    <ul class="space-y-4" id="covers-list">
        {{-- Sección de encabezado --}}
        {{-- Aquí se puede agregar un encabezado o título para la lista de portadas --}}
        {{-- Itera sobre cada portada y muestra su información --}}
        {{-- Cada portada se representa como un elemento de lista --}}
        {{-- Se utiliza el componente "cover-card" para mostrar la información de la portada --}}
        @foreach ($covers as $cover)
            <li class="overflow-hidden bg-white rounded-lg shadow-md cursor-move lg:flex" data-id="{{ $cover->id }}">

                <img src="{{ $cover->image }}" class="lg:w-52 w-full aspect-[3/1]  object-center "
                    alt="Imagen de Portada">

                <div class="p-4 space-y-3 lg:space-y-0 lg:flex lg:items-center lg:justify-between lg:flex-1">
                    <div>
                        <h1 class="font-semibold">{{ $cover->title }}</h1>
                        @if ($cover->is_active)
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">Activo</span>
                        @else
                            <span
                                class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">Inactivo</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-bold">Fecha de Inicio</p>
                        <p class="text-sm text-gray-500">{{ $cover->start_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Fecha de Finalización</p>
                        <p class="text-sm text-gray-500">
                            {{ $cover->end_at ? $cover->end_at->format('d/m/Y') : 'Sin fecha' }}</p>
                    </div>

                    <div>
                        <x-link href="{{ route('admin.covers.edit', $cover) }}" type="primary" name="Editar" />
                    </div>

                </div>
            </li>
        @endforeach
    </ul>

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
                        const sorts = sortable.toArray().map((id, index) => ({ id: id, order: index + 1 }));
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
