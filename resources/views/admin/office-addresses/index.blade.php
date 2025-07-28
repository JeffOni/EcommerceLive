<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Oficinas',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.office-addresses.create') }}" type="primary" name="Nueva Oficina" />
    </x-slot>
    <div class="min-h-screen overflow-x-hidden bg-gray-50">
        <div class="w-full max-w-sm px-3 py-4 mx-auto sm:max-w-2xl sm:px-6 lg:max-w-7xl lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <form method="GET" action="{{ route('admin.office-addresses.index') }}"
                        class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por nombre, dirección, provincia..."
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="w-full sm:w-auto">
                            <select name="per_page"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full text-sm border-gray-300 rounded-md">
                                <option value="15" {{ request('per_page')==15 ? 'selected' : '' }}>15 por página
                                </option>
                                <option value="25" {{ request('per_page')==25 ? 'selected' : '' }}>25 por página
                                </option>
                                <option value="50" {{ request('per_page')==50 ? 'selected' : '' }}>50 por página
                                </option>
                            </select>
                        </div>
                        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-search mr-2"></i>
                                Filtrar
                            </button>
                            @if(request()->hasAny(['search', 'per_page']))
                            <a href="{{ route('admin.office-addresses.index') }}"
                                class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-times mr-2"></i>
                                Limpiar
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de oficinas -->
            <div class="bg-white shadow-sm overflow-hidden rounded-lg border border-gray-200">
                <div class="divide-y divide-gray-200">
                    @forelse($offices as $office)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div
                            class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                            <!-- Información principal -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-base font-medium text-indigo-600 truncate">
                                        {{ $office->name }}
                                    </h3>
                                    @if($office->is_main)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>
                                        Principal
                                    </span>
                                    @endif
                                    @if(!$office->is_active)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Inactiva
                                    </span>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    <p class="text-sm text-gray-600 flex items-start">
                                        <i class="fas fa-map-marker-alt mt-0.5 mr-2 text-gray-400 flex-shrink-0"></i>
                                        <span class="break-words">{{ $office->full_address }}</span>
                                    </p>
                                    @if($office->phone)
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-phone mt-0.5 mr-2 text-gray-400 flex-shrink-0"></i>
                                        <span>{{ $office->phone }}</span>
                                    </p>
                                    @endif
                                    @if($office->working_hours)
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-clock mt-0.5 mr-2 text-gray-400 flex-shrink-0"></i>
                                        <span>{{ $office->working_hours }}</span>
                                    </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-wrap items-center gap-2 pt-3 sm:pt-0 sm:flex-nowrap sm:ml-4">
                                <!-- Toggle Status -->
                                <button onclick="toggleStatus({{ $office->id }})"
                                    class="toggle-btn inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full transition-colors {{ $office->is_active ? 'text-red-700 bg-red-100 hover:bg-red-200' : 'text-green-700 bg-green-100 hover:bg-green-200' }}">
                                    <i class="fas {{ $office->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                    {{ $office->is_active ? 'Desactivar' : 'Activar' }}
                                </button>

                                <!-- Ver -->
                                <a href="{{ route('admin.office-addresses.show', $office) }}"
                                    class="inline-flex items-center px-3 py-1 border border-indigo-300 text-xs font-medium rounded-full text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('admin.office-addresses.edit', $office) }}"
                                    class="inline-flex items-center px-3 py-1 border border-blue-300 text-xs font-medium rounded-full text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </a>

                                <!-- Eliminar -->
                                @if(!$office->is_main || \App\Models\OfficeAddress::count() > 1)
                                <form method="POST" action="{{ route('admin.office-addresses.destroy', $office) }}"
                                    class="inline"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta oficina?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1 border border-red-300 text-xs font-medium rounded-full text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-12 text-center">
                        <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                            <i class="fas fa-building text-4xl"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">No se encontraron oficinas</h3>
                        <p class="text-sm text-gray-500 mb-4">Comienza creando tu primera oficina</p>
                        <a href="{{ route('admin.office-addresses.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i>
                            Crear primera oficina
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Paginación -->
            @if($offices->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="w-full overflow-x-auto">
                    {{ $offices->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>

        <script>
            function toggleStatus(officeId) {
            fetch(`/admin/office-addresses/${officeId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al cambiar el estado');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cambiar el estado');
            });
        }
        </script>
</x-admin-layout>