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
        <div class="flex space-x-2">
            <x-link href="{{ route('admin.users.edit', $user) }}" type="primary" name="Editar Usuario" />
            <x-link href="{{ route('admin.users.index') }}" type="secondary" name="Regresar" />
        </div>
    </x-slot>

    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-green-50 via-white to-blue-50">
        <div class="relative">
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-green-600 to-blue-600">
                    <div class="flex items-center space-x-4">
                        <img class="h-16 w-16 rounded-full object-cover border-4 border-white"
                            src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $user->name }} {{ $user->last_name }}</h2>
                            <p class="text-sm text-green-100">{{ $user->email }}</p>
                            @php
                            $role = $user->roles->first();
                            $roleEnum = $role ? \App\Enums\UserRole::from($role->name) : null;
                            @endphp
                            @if($roleEnum)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                <i class="fas {{ $roleEnum->icon() }} mr-1"></i>
                                {{ $roleEnum->label() }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-white">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Información Personal -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user text-green-600 mr-2"></i>
                                Información Personal
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Documento:</span>
                                    <span class="font-medium">
                                        {{ \App\Enums\TypeOfDocuments::from($user->document_type)->label() }} - {{
                                        $user->document_number }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Teléfono:</span>
                                    <span class="font-medium">{{ $user->phone }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email Verificado:</span>
                                    <span class="font-medium">
                                        @if($user->email_verified_at)
                                        <span class="text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>Sí
                                        </span>
                                        @else
                                        <span class="text-red-600">
                                            <i class="fas fa-times-circle mr-1"></i>No
                                        </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Sistema -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-cog text-blue-600 mr-2"></i>
                                Información del Sistema
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Registrado:</span>
                                    <span class="font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Última actualización:</span>
                                    <span class="font-medium">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total de órdenes:</span>
                                    <span class="font-medium">{{ $user->orders->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Órdenes Recientes (si las hay) -->
                        @if($user->orders->count() > 0)
                        <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-shopping-cart text-purple-600 mr-2"></i>
                                Órdenes Recientes
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Número</th>
                                            <th class="px-4 py-2 text-left">Fecha</th>
                                            <th class="px-4 py-2 text-left">Total</th>
                                            <th class="px-4 py-2 text-left">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($user->orders->take(5) as $order)
                                        <tr>
                                            <td class="px-4 py-2">{{ $order->order_number }}</td>
                                            <td class="px-4 py-2">{{ $order->created_at->format('d/m/Y') }}</td>
                                            <td class="px-4 py-2">${{ number_format($order->total, 2) }}</td>
                                            <td class="px-4 py-2">
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
                                @if($user->orders->count() > 5)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        Ver todas las órdenes ({{ $user->orders->count() }})
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

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