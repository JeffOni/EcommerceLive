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
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Filtros -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('admin.office-addresses.index') }}"
                    class="flex items-center space-x-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nombre, dirección, provincia..."
                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <select name="per_page"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="15" {{ request('per_page')==15 ? 'selected' : '' }}>15 por página</option>
                            <option value="25" {{ request('per_page')==25 ? 'selected' : '' }}>25 por página</option>
                            <option value="50" {{ request('per_page')==50 ? 'selected' : '' }}>50 por página</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'per_page']))
                    <a href="{{ route('admin.office-addresses.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Limpiar
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabla de oficinas -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($offices as $office)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($office->is_main)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Principal
                                </span>
                                @endif
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        {{ $office->name }}
                                    </p>
                                    @if(!$office->is_active)
                                    <span
                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactiva
                                    </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $office->full_address }}</p>
                                @if($office->phone)
                                <p class="text-sm text-gray-500">📞 {{ $office->phone }}</p>
                                @endif
                                @if($office->working_hours)
                                <p class="text-sm text-gray-500">🕒 {{ $office->working_hours }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Toggle Status -->
                            <button onclick="toggleStatus({{ $office->id }})"
                                class="toggle-btn inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full {{ $office->is_active ? 'text-red-700 bg-red-100 hover:bg-red-200' : 'text-green-700 bg-green-100 hover:bg-green-200' }}">
                                {{ $office->is_active ? 'Desactivar' : 'Activar' }}
                            </button>

                            <!-- Ver -->
                            <a href="{{ route('admin.office-addresses.show', $office) }}"
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Ver
                            </a>

                            <!-- Editar -->
                            <a href="{{ route('admin.office-addresses.edit', $office) }}"
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Editar
                            </a>

                            <!-- Eliminar -->
                            @if(!$office->is_main || \App\Models\OfficeAddress::count() > 1)
                            <form method="POST" action="{{ route('admin.office-addresses.destroy', $office) }}"
                                class="inline"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta oficina?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Eliminar
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </li>
                @empty
                <li class="px-4 py-8 text-center text-gray-500">
                    No se encontraron oficinas.
                    <a href="{{ route('admin.office-addresses.create') }}"
                        class="text-indigo-600 hover:text-indigo-900 font-medium">
                        Crear la primera oficina
                    </a>
                </li>
                @endforelse
            </ul>
        </div>

        <!-- Paginación -->
        @if($offices->hasPages())
        <div class="mt-6">
            {{ $offices->appends(request()->query())->links() }}
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