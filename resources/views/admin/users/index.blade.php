<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
    ],
]">

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-indigo-200/30 to-purple-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-200/30 to-indigo-300/20 blur-3xl">
            </div>
            <div
                class="absolute w-64 h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-indigo-100/40 to-purple-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <x-slot name="action">
                <x-link href="{{ route('admin.users.create') }}" type="primary" name="Nuevo Usuario" />
            </x-slot>

            <!-- Contenedor principal con backdrop blur -->
            <div class="mx-2 sm:mx-4 my-6 sm:my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header con gradiente -->
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 bg-gradient-to-r from-indigo-900 to-purple-500">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 sm:p-3 glass-effect rounded-xl flex-shrink-0">
                                <i class="text-lg sm:text-xl text-white fas fa-users"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">Gestión de
                                    Usuarios</h2>
                                <p class="text-xs sm:text-sm text-indigo-100 truncate">Administra los usuarios del
                                    sistema
                                </p>
                            </div>
                        </div>
                        <div class="text-xs sm:text-sm text-white/80 flex-shrink-0">
                            <i class="mr-1 fas fa-users"></i>
                            {{ $users->total() }} usuarios
                        </div>
                    </div>
                </div>

                <!-- Barra de herramientas con controles de vista -->
                <div class="px-4 sm:px-6 lg:px-8 py-3 sm:py-4 bg-white border-b border-gray-200">
                    <div
                        class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
                        <!-- Controles de vista -->
                        <div class="flex items-center space-x-3 sm:space-x-4 w-full sm:w-auto">
                            <span class="text-xs sm:text-sm font-medium text-gray-700 flex-shrink-0">Vista:</span>
                            <div class="flex p-1 bg-gray-100 rounded-lg flex-1 sm:flex-initial">
                                <button onclick="toggleView('cards')" id="cards-btn"
                                    class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white transition-all duration-200 bg-indigo-600 rounded-md shadow-sm view-toggle flex-1 sm:flex-initial">
                                    <i class="mr-1 sm:mr-2 fas fa-th-large"></i><span
                                        class="hidden sm:inline">Tarjetas</span><span class="sm:hidden">Cards</span>
                                </button>
                                <button onclick="toggleView('table')" id="table-btn"
                                    class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 transition-all duration-200 rounded-md view-toggle hover:text-gray-900 flex-1 sm:flex-initial">
                                    <i class="mr-1 sm:mr-2 fas fa-table"></i><span
                                        class="hidden sm:inline">Tabla</span><span class="sm:hidden">Table</span>
                                </button>
                            </div>
                        </div>

                        <!-- Filtros y búsqueda -->
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                            <form method="GET" action="{{ route('admin.users.index') }}"
                                class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                                <div class="relative w-full sm:w-auto">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Buscar usuarios..."
                                        class="w-full sm:w-48 lg:w-64 py-2 pl-8 sm:pl-10 pr-4 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <i
                                        class="absolute text-gray-400 fas fa-search left-2 sm:left-3 top-2.5 sm:top-3 text-xs sm:text-sm"></i>
                                </div>
                                <select name="role"
                                    class="px-2 sm:px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 w-full sm:w-auto">
                                    <option value="">Todos los roles</option>
                                    @foreach(\App\Enums\UserRole::toArray() as $value => $label)
                                    <option value="{{ $value }}" {{ request('role')==$value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="flex space-x-2">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-xs sm:text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <i class="fas fa-search mr-1 sm:mr-2"></i><span
                                            class="hidden sm:inline">Buscar</span>
                                    </button>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <i class="fas fa-times mr-1 sm:mr-2"></i><span
                                            class="hidden sm:inline">Limpiar</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6 lg:p-8" id="users-container">
                    @if ($users->count())
                    <!-- Vista de tabla para desktop -->
                    <div id="table-view" class="view-content hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl">
                                            Usuario
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Documento
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contacto
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rol
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Registro
                                        </th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="h-10 w-10 rounded-full object-cover shadow-md"
                                                    src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->name }} {{ $user->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ \App\Enums\TypeOfDocuments::from($user->document_type)->label() }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $user->document_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->phone }}</div>
                                            <div class="text-sm">
                                                @if($user->email_verified_at)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Verificado
                                                </span>
                                                @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>Sin verificar
                                                </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                            $role = $user->roles->first();
                                            $roleEnum = $role ? \App\Enums\UserRole::from($role->name) : null;
                                            @endphp
                                            @if($roleEnum)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $roleEnum->color() }}-100 text-{{ $roleEnum->color() }}-800">
                                                <i class="fas {{ $roleEnum->icon() }} mr-1"></i>
                                                {{ $roleEnum->label() }}
                                            </span>
                                            @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Sin rol
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d/m/Y') }}<br>
                                            <span class="text-xs">{{ $user->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                    title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                    class="text-green-600 hover:text-green-900 transition-colors"
                                                    title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($user->id !== auth()->id())
                                                <button onclick="deleteUser({{ $user->id }})"
                                                    class="text-red-600 hover:text-red-900 transition-colors"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vista de cards -->
                    <div id="cards-view" class="view-content">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                            @foreach($users as $user)
                            <div
                                class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 overflow-hidden">
                                <!-- Header de la tarjeta con gradiente -->
                                <div class="relative bg-gradient-to-br from-indigo-500 to-purple-600 px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="relative">
                                            <img class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-lg"
                                                src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                            @if($user->email_verified_at)
                                            <div
                                                class="absolute -top-1 -right-1 h-4 w-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            @else
                                            <div
                                                class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
                                                <i class="fas fa-times text-white text-xs"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-white truncate">
                                                {{ $user->name }} {{ $user->last_name }}
                                            </h3>
                                            <p class="text-xs text-indigo-100 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>

                                    <!-- Rol badge -->
                                    <div class="mt-3">
                                        @php
                                        $role = $user->roles->first();
                                        $roleEnum = $role ? \App\Enums\UserRole::from($role->name) : null;
                                        @endphp
                                        @if($roleEnum)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                                            <i class="fas {{ $roleEnum->icon() }} mr-1"></i>
                                            {{ $roleEnum->label() }}
                                        </span>
                                        @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                                            Sin rol
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Contenido de la tarjeta -->
                                <div class="px-6 py-4">
                                    <div class="space-y-3">
                                        <!-- Documento -->
                                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-id-card mr-2 text-gray-400"></i>Documento
                                            </span>
                                            <div class="text-right">
                                                <div class="text-xs font-medium text-gray-900">
                                                    {{ \App\Enums\TypeOfDocuments::from($user->document_type)->label()
                                                    }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $user->document_number }}</div>
                                            </div>
                                        </div>

                                        <!-- Teléfono -->
                                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-phone mr-2 text-gray-400"></i>Teléfono
                                            </span>
                                            <span class="text-xs font-medium text-gray-900">{{ $user->phone ?: 'No
                                                especificado' }}</span>
                                        </div>

                                        <!-- Estado -->
                                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-shield-alt mr-2 text-gray-400"></i>Estado
                                            </span>
                                            @if($user->email_verified_at)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Verificado
                                            </span>
                                            @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Sin verificar
                                            </span>
                                            @endif
                                        </div>

                                        <!-- Registro -->
                                        <div class="flex items-center justify-between py-2">
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-calendar mr-2 text-gray-400"></i>Registro
                                            </span>
                                            <div class="text-right">
                                                <div class="text-xs font-medium text-gray-900">{{
                                                    $user->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans()
                                                    }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-blue-300 rounded-lg text-blue-600 hover:bg-blue-50 transition-all duration-200 text-xs font-medium">
                                            <i class="fas fa-eye mr-1"></i>Ver
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-green-300 rounded-lg text-green-600 hover:bg-green-50 transition-all duration-200 text-xs font-medium">
                                            <i class="fas fa-edit mr-1"></i>Editar
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <button onclick="deleteUser({{ $user->id }})"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-red-300 rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200 text-xs font-medium">
                                            <i class="fas fa-trash mr-1"></i>Del
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <!-- Estado vacío mejorado -->
                    <div class="py-16 text-center">
                        <div
                            class="inline-flex items-center justify-center w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100">
                            <i class="text-4xl text-indigo-500 fas fa-users"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-semibold text-gray-800">No hay usuarios registrados</h3>
                        <p class="max-w-md mx-auto mb-8 text-gray-600">Todavía no has creado ningún usuario. Los
                            usuarios te ayudan a gestionar el acceso y permisos del sistema.</p>
                        <a href="{{ route('admin.users.create') }}"
                            class="inline-flex items-center px-8 py-3 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl hover:shadow-xl hover:scale-105">
                            <i class="mr-3 text-white fas fa-plus"></i>
                            <span class="text-white">Crear Primer Usuario</span>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Paginación -->
                @if($users->hasPages())
                <div class="px-4 sm:px-6 lg:px-8 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-center">
                        {{ $users->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- CSS para efectos glass --}}
    @push('css')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
    @endpush

    {{-- JavaScript para funcionalidad avanzada --}}
    @push('js')
    <script>
        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Estado de la vista actual
            let currentView = 'cards';

            // Función para cambiar entre vistas
            window.toggleView = function(viewType) {
                console.log('toggleView called with:', viewType); // Debug
                
                // Ocultar todas las vistas
                document.querySelectorAll('.view-content').forEach(view => {
                    view.classList.add('hidden');
                });

                // Mostrar la vista seleccionada
                const targetView = document.getElementById(viewType + '-view');
                if (targetView) {
                    targetView.classList.remove('hidden');
                } else {
                    console.error('No se encontró el elemento:', viewType + '-view');
                }

                // Actualizar botones
                document.querySelectorAll('.view-toggle').forEach(btn => {
                    btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-sm');
                    btn.classList.add('text-gray-600', 'hover:text-gray-900');
                });

                const selectedBtn = document.getElementById(viewType + '-btn');
                if (selectedBtn) {
                    selectedBtn.classList.add('bg-indigo-600', 'text-white', 'shadow-sm');
                    selectedBtn.classList.remove('text-gray-600', 'hover:text-gray-900');
                } else {
                    console.error('No se encontró el botón:', viewType + '-btn');
                }

                // Guardar preferencia
                localStorage.setItem('admin_users_view', viewType);
                currentView = viewType;
            };

            // Restaurar vista guardada
            const savedView = localStorage.getItem('admin_users_view') || 'cards';
            if (savedView !== 'cards') {
                toggleView(savedView);
            }

            // Función para eliminar usuario (también global)
            window.deleteUser = function(userId) {
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 no está cargado');
                    return;
                }
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Crear formulario dinámico para DELETE
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/users/${userId}`;
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        
                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = '{{ csrf_token() }}';
                        
                        form.appendChild(methodInput);
                        form.appendChild(tokenInput);
                        document.body.appendChild(form);
                        
                        form.submit();
                    }
                });
            };
        });
    </script>
    @endpush

</x-admin-layout>