<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'route' => route('admin.users.index'),
    ],
    [
        'name' => 'Editar Usuario',
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
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <x-link href="{{ route('admin.users.show', $user) }}" type="secondary" name="Ver Usuario" />
                    <x-link href="{{ route('admin.users.index') }}" type="primary" name="Regresar" />
                </div>
            </x-slot>

            <!-- Contenedor principal con backdrop blur -->
            <div class="mx-2 sm:mx-4 my-6 sm:my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header con información del usuario -->
                <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8 bg-gradient-to-r from-indigo-900 to-purple-500">
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <div class="p-3 glass-effect rounded-xl flex-shrink-0">
                            <i class="text-lg sm:text-xl text-white fas fa-user-edit"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">Editar Usuario
                            </h2>
                            <p class="text-sm sm:text-base text-indigo-100 truncate">
                                Modifica la información de: {{ $user->name }} {{ $user->last_name }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <img class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-lg"
                                src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-white to-gray-50">
                    @if ($errors->any())
                    <div class="mb-6 sm:mb-8 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <h3 class="text-sm font-medium text-red-800">Por favor corrige los siguientes errores:</h3>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST"
                        class="space-y-6 sm:space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Información Personal -->
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                                <h3 class="ml-4 text-lg font-semibold text-gray-900">Información Personal</h3>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-1 text-gray-400"></i>Nombre *
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-1 text-gray-400"></i>Apellido *
                                    </label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-id-card mr-1 text-gray-400"></i>Tipo de Documento *
                                    </label>
                                    <select name="document_type" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        @foreach($documentTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('document_type', $user->document_type) ==
                                            $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-hashtag mr-1 text-gray-400"></i>Número de Documento *
                                    </label>
                                    <input type="text" name="document_number"
                                        value="{{ old('document_number', $user->document_number) }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl">
                                    <i class="fas fa-envelope text-white text-lg"></i>
                                </div>
                                <h3 class="ml-4 text-lg font-semibold text-gray-900">Información de Contacto</h3>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-envelope mr-1 text-gray-400"></i>Correo Electrónico *
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-phone mr-1 text-gray-400"></i>Teléfono *
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                                        maxlength="10" pattern="[0-9]{10}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    <p class="mt-1 text-xs text-gray-500">Ingresa 10 dígitos sin espacios ni guiones</p>
                                </div>
                            </div>
                        </div>

                        <!-- Credenciales de Acceso -->
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                            <div class="flex items-center mb-6">
                                <div class="p-3 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                                    <i class="fas fa-key text-white text-lg"></i>
                                </div>
                                <h3 class="ml-4 text-lg font-semibold text-gray-900">Credenciales de Acceso</h3>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-1 text-gray-400"></i>Nueva Contraseña (Opcional)
                                    </label>
                                    <input type="password" name="password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    <p class="mt-1 text-xs text-gray-500">Deja en blanco para mantener la contraseña
                                        actual</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-1 text-gray-400"></i>Confirmar Contraseña
                                    </label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                </div>

                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user-tag mr-1 text-gray-400"></i>Rol del Usuario *
                                    </label>
                                    <select name="role" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        @foreach($roles as $value => $label)
                                        @if($value !== 'cliente')
                                        <option value="{{ $value }}" {{ old('role', $currentRole)==$value ? 'selected'
                                            : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Define los permisos y accesos del usuario en
                                        el sistema</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div
                            class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.users.index') }}"
                                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 font-medium">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
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

</x-admin-layout>