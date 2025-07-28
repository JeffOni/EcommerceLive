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
        'name' => 'Ver Usuario',
    ],
]">

    <x-slot name="action">
        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
            <x-link href="{{ route('admin.users.edit', $user) }}" type="primary" name="Editar Usuario" />
            <x-link href="{{ route('admin.users.index') }}" type="secondary" name="Regresar" />
        </div>
    </x-slot>

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

            <!-- Contenedor principal con backdrop blur -->
            <div class="mx-2 my-6 overflow-hidden shadow-2xl sm:mx-4 sm:my-8 glass-effect rounded-3xl">
                <!-- Header con información del usuario -->
                <div class="px-4 py-6 sm:px-6 lg:px-8 sm:py-8 bg-gradient-to-r from-indigo-900 to-purple-500">
                    <div
                        class="flex flex-col items-start space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-6">
                        <div class="relative flex-shrink-0">
                            <img class="object-cover w-16 h-16 border-4 border-white rounded-full shadow-lg sm:h-20 sm:w-20"
                                src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                            @if($user->email_verified_at)
                            <div
                                class="absolute flex items-center justify-center w-6 h-6 bg-green-500 border-2 border-white rounded-full -top-2 -right-2">
                                <i class="text-xs text-white fas fa-check"></i>
                            </div>
                            @else
                            <div
                                class="absolute flex items-center justify-center w-6 h-6 bg-red-500 border-2 border-white rounded-full -top-2 -right-2">
                                <i class="text-xs text-white fas fa-times"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-sm font-bold text-white truncate sm:text-2xl lg:text-3xl">
                                {{ $user->name }} {{ $user->last_name }}
                            </h2>
                            <p class="text-sm text-indigo-100 truncate sm:text-base">{{ $user->email }}</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @php
                                $role = $user->roles->first();
                                $roleEnum = $role ? \App\Enums\UserRole::from($role->name) : null;
                                @endphp
                                @if($roleEnum)
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-white rounded-full bg-white/20 backdrop-blur-sm">
                                    <i class="fas {{ $roleEnum->icon() }} mr-1"></i>
                                    {{ $roleEnum->label() }}
                                </span>
                                @endif
                                @if($user->email_verified_at)
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-white rounded-full bg-green-500/20 backdrop-blur-sm">
                                    <i class="mr-1 fas fa-shield-check"></i>
                                    Cuenta Verificada
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-xs text-right text-white/80 sm:text-sm">
                            <div class="whitespace-nowrap">Miembro desde</div>
                            <div class="font-semibold whitespace-nowrap">{{ $user->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-white to-gray-50">
                    <div class="max-w-full">
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">
                            <!-- Información Personal -->
                            <div class="p-6 bg-white border border-gray-100 shadow-lg rounded-2xl">
                                <div class="flex items-center mb-6">
                                    <div
                                        class="flex-shrink-0 p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                        <i class="text-lg text-white fas fa-user"></i>
                                    </div>
                                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Información Personal</h3>
                                </div>
                                <div class="space-y-4">
                                    <div
                                        class="flex flex-col py-3 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="flex items-center flex-shrink-0 text-sm text-gray-600">
                                            <i class="mr-2 text-gray-400 fas fa-id-card"></i>Documento
                                        </span>
                                        <div class="min-w-0 mt-1 text-right sm:mt-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ \App\Enums\TypeOfDocuments::from($user->document_type)->label() }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">{{ $user->document_number }}
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-col py-3 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="flex items-center flex-shrink-0 text-sm text-gray-600">
                                            <i class="mr-2 text-gray-400 fas fa-phone"></i>Teléfono
                                        </span>
                                        <span class="mt-1 text-sm font-medium text-gray-900 truncate sm:mt-0">{{
                                            $user->phone ?: 'No especificado' }}</span>
                                    </div>
                                    <div
                                        class="flex flex-col py-3 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="flex items-center flex-shrink-0 text-sm text-gray-600">
                                            <i class="mr-2 text-gray-400 fas fa-envelope"></i>Email
                                        </span>
                                        <div class="min-w-0 mt-1 text-right sm:mt-0">
                                            <div class="text-xs font-medium text-gray-900 break-words">{{ $user->email
                                                }}</div>
                                            @if($user->email_verified_at)
                                            <span
                                                class="inline-flex items-center px-2 py-1 mt-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                <i class="mr-1 fas fa-check-circle"></i>Verificado
                                            </span>
                                            @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 mt-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                <i class="mr-1 fas fa-times-circle"></i>Sin verificar
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Sistema -->
                            <div class="p-6 bg-white border border-gray-100 shadow-lg rounded-2xl">
                                <div class="flex items-center mb-6">
                                    <div
                                        class="flex-shrink-0 p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl">
                                        <i class="text-lg text-white fas fa-cog"></i>
                                    </div>
                                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Información del Sistema</h3>
                                </div>
                                <div class="space-y-4">
                                    <div
                                        class="flex flex-col py-3 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="flex items-center flex-shrink-0 text-sm text-gray-600">
                                            <i class="mr-2 text-gray-400 fas fa-calendar-plus"></i>Registrado
                                        </span>
                                        <div class="min-w-0 mt-1 text-right sm:mt-0">
                                            <div class="text-sm font-medium text-gray-900 whitespace-nowrap">{{
                                                $user->created_at->format('d/m/Y H:i') }}</div>
                                            <div class="text-xs text-gray-500 whitespace-nowrap">{{
                                                $user->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-col py-3 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="flex items-center flex-shrink-0 text-sm text-gray-600">
                                            <i class="mr-2 text-gray-400 fas fa-sync-alt"></i>Última actualización
                                        </span>
                                        <div class="min-w-0 mt-1 text-right sm:mt-0">
                                            <div class="text-sm font-medium text-gray-900 whitespace-nowrap">{{
                                                $user->updated_at->format('d/m/Y H:i') }}</div>
                                            <div class="text-xs text-gray-500 whitespace-nowrap">{{
                                                $user->updated_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col py-3 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="flex items-center flex-shrink-0 text-sm text-gray-600">
                                            <i class="mr-2 text-gray-400 fas fa-shopping-cart"></i>Total de órdenes
                                        </span>
                                        <div class="mt-1 sm:mt-0">
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">
                                                {{ $user->orders->count() }} órdenes
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Órdenes Recientes -->
                        @if($user->orders->count() > 0)
                        <div class="p-6 mt-6 bg-white border border-gray-100 shadow-lg lg:mt-8 rounded-2xl">
                            <div class="flex flex-col justify-between gap-4 mb-6 sm:flex-row sm:items-center">
                                <div class="flex items-center min-w-0">
                                    <div
                                        class="flex-shrink-0 p-3 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                                        <i class="text-lg text-white fas fa-shopping-cart"></i>
                                    </div>
                                    <h3 class="ml-4 text-lg font-semibold text-gray-900 truncate">Órdenes Recientes</h3>
                                </div>
                                @if($user->orders->count() > 5)
                                <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}"
                                    class="flex-shrink-0 text-sm font-medium text-indigo-600 hover:text-indigo-800 whitespace-nowrap">
                                    Ver todas ({{ $user->orders->count() }})
                                </a>
                                @endif
                            </div>

                            <!-- Vista de tabla para desktop -->
                            <div class="hidden overflow-x-auto lg:block">
                                <table class="w-full">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase rounded-tl">
                                                Número</th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Fecha</th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Total</th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase rounded-tr">
                                                Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($user->orders->take(5) as $order)
                                        <tr class="transition-colors hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                {{ $order->order_number }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                {{ $order->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                ${{ number_format($order->total, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                $status = \App\Enums\OrderStatus::from($order->status);
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $status->color() }}-100 text-{{ $status->color() }}-800">
                                                    {{ $status->label() }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Vista de cards para móvil/tablet -->
                            <div class="space-y-4 lg:hidden">
                                @foreach($user->orders->take(5) as $order)
                                <div class="p-4 border border-gray-200 bg-gray-50 rounded-xl">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{
                                                $order->order_number }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y
                                                H:i') }}</div>
                                        </div>
                                        @php
                                        $status = \App\Enums\OrderStatus::from($order->status);
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $status->color() }}-100 text-{{ $status->color() }}-800 ml-2 flex-shrink-0">
                                            {{ $status->label() }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Total:</span>
                                        <span class="text-sm font-medium text-gray-900">${{ number_format($order->total,
                                            2) }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
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