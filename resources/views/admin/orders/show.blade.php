<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pedidos',
        'route' => route('admin.orders.index'),
    ],
    [
        'name' => 'Detalle #' . $order->id,
    ],
]">
    <x-slot name="action">
        <x-link href="{{ route('admin.orders.index') }}" type="secondary" name="Regresar" />
    </x-slot>
    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-blue-200/30 to-purple-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-200/30 to-blue-300/20 blur-3xl">
            </div>
        </div>

        <div class="relative">
            <!-- Contenedor principal -->
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-green-600 to-blue-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="text-xl text-white fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Detalle del Pedido #{{ $order->id }}</h2>
                                <p class="text-sm text-green-100">Información completa del pedido</p>
                            </div>
                        </div>
                        <div class="text-sm text-white/80">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-8 bg-white">
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <!-- Información del Cliente -->
                        <div class="lg:col-span-1">
                            <div class="p-6 rounded-lg bg-gray-50">
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                    <i class="mr-2 text-blue-500 fas fa-user"></i>
                                    Información del Cliente
                                </h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Nombre:</label>
                                        <p class="text-gray-900">{{ $order->user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Email:</label>
                                        <p class="text-gray-900">{{ $order->user->email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Teléfono:</label>
                                        <p class="text-gray-900">{{ $order->user->phone ?? 'No especificado' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado del Pedido -->
                            <div class="p-6 mt-6 rounded-lg bg-gray-50">
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                    <i class="mr-2 text-green-500 fas fa-info-circle"></i>
                                    Estado del Pedido
                                </h3>
                                @php
                                $statusClass = match ($order->status) {
                                1 => 'status-pendiente',
                                2 => 'status-verificado',
                                3 => 'status-preparando',
                                4 => 'status-asignado',
                                5 => 'status-en-camino',
                                6 => 'status-entregado',
                                7 => 'status-cancelado',
                                default => 'status-pendiente',
                                };

                                $statusText = match ($order->status) {
                                1 => 'Pendiente',
                                2 => 'Pago Verificado',
                                3 => 'Preparando',
                                4 => 'Asignado',
                                5 => 'En Camino',
                                6 => 'Entregado',
                                7 => 'Cancelado',
                                default => 'Pendiente',
                                };
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="status-badge {{ $statusClass }} text-sm">
                                        {{ $statusText }}
                                    </span>
                                    @if ($order->pdf_path)
                                    <a href="{{ route('admin.orders.downloadPDF', $order) }}"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                        <i class="mr-2 fas fa-download"></i>
                                        Descargar PDF
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del Pedido -->
                        <div class="lg:col-span-2">
                            <!-- Dirección de Envío -->
                            <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg">
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                    <i class="mr-2 text-red-500 fas fa-map-marker-alt"></i>
                                    Dirección de Envío
                                </h3>
                                @if ($shippingAddress && $shippingAddress['address'])
                                <div class="space-y-3">
                                    <!-- Destinatario -->
                                    @if ($shippingAddress['recipient_name'])
                                    <div class="flex items-center space-x-2">
                                        <i class="text-gray-400 fas fa-user"></i>
                                        <span class="font-medium text-gray-900">{{ $shippingAddress['recipient_name']
                                            }}</span>
                                        @if ($shippingAddress['recipient_document'])
                                        <span class="text-sm text-gray-500">(CI:
                                            {{ $shippingAddress['recipient_document'] }})</span>
                                        @endif
                                    </div>
                                    @endif

                                    <!-- Teléfono -->
                                    @if ($shippingAddress['phone'])
                                    <div class="flex items-center space-x-2">
                                        <i class="text-gray-400 fas fa-phone"></i>
                                        <span class="text-gray-900">{{ $shippingAddress['phone'] }}</span>
                                    </div>
                                    @endif

                                    <!-- Dirección completa -->
                                    <div class="p-4 rounded-lg bg-gray-50">
                                        <div class="flex items-start space-x-2">
                                            <i class="mt-1 text-gray-400 fas fa-map-marker-alt"></i>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900">
                                                    {{ $shippingAddress['address'] }}
                                                </p>

                                                <!-- Ubicación geográfica -->
                                                @php
                                                $parish = $shippingAddress['parish'] ?? '';
                                                $canton = $shippingAddress['canton'] ?? '';
                                                $province = $shippingAddress['province'] ?? '';
                                                $postal = $shippingAddress['postal_code'] ?? '';
                                                $hasLocation =
                                                ($parish && $parish !== 'Sin parroquia') ||
                                                ($canton && $canton !== 'Sin cantón') ||
                                                ($province && $province !== 'Sin provincia') ||
                                                $postal;
                                                @endphp
                                                @if ($hasLocation)
                                                <p class="mt-1 text-sm text-gray-600">
                                                    @if ($parish && $parish !== 'Sin parroquia')
                                                    {{ $parish }}
                                                    @endif
                                                    @if ($canton && $canton !== 'Sin cantón')
                                                    , {{ $canton }}
                                                    @endif
                                                    @if ($province && $province !== 'Sin provincia')
                                                    , {{ $province }}
                                                    @endif
                                                    @if ($postal)
                                                    - CP: {{ $postal }}
                                                    @endif
                                                </p>
                                                @endif

                                                <!-- Referencia -->
                                                @if ($shippingAddress['reference'])
                                                <div class="p-2 mt-2 border-l-4 border-blue-200 rounded bg-blue-50">
                                                    <p class="text-sm text-blue-800">
                                                        <i class="mr-1 fas fa-info-circle"></i>
                                                        <strong>Referencia:</strong>
                                                        {{ $shippingAddress['reference'] }}
                                                    </p>
                                                </div>
                                                @endif

                                                <!-- Dirección completa formateada (si existe) -->
                                                @if ($shippingAddress['full_address'])
                                                <div class="p-2 mt-2 border-l-4 border-green-200 rounded bg-green-50">
                                                    <p class="text-sm text-green-800">
                                                        <i class="mr-1 fas fa-map"></i>
                                                        <strong>Dirección completa:</strong>
                                                        {{ $shippingAddress['full_address'] }}
                                                    </p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tipo de dirección -->
                                    @if ($shippingAddress['type'])
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $shippingAddress['type'] === 'home' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            @if ($shippingAddress['type'] === 'home')
                                            <i class="mr-1 fas fa-home"></i> Casa
                                            @else
                                            <i class="mr-1 fas fa-briefcase"></i> Trabajo
                                            @endif
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <div class="py-8 text-center">
                                    <i class="mb-2 text-3xl text-yellow-500 fas fa-exclamation-triangle"></i>
                                    <p class="text-gray-500">No se especificó dirección de envío</p>
                                    <p class="text-sm text-gray-400">Esta orden fue creada antes del sistema de
                                        direcciones</p>
                                </div>
                                @endif
                            </div>

                            <!-- Productos del Pedido -->
                            <div class="p-6 bg-white border border-gray-200 rounded-lg">
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                    <i class="mr-2 text-purple-500 fas fa-shopping-bag"></i>
                                    Productos del Pedido
                                </h3>

                                @if ($order->content)
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Producto</th>
                                                <th
                                                    class="px-4 py-2 text-xs font-medium text-center text-gray-500 uppercase">
                                                    Cantidad</th>
                                                <th
                                                    class="px-4 py-2 text-xs font-medium text-right text-gray-500 uppercase">
                                                    Precio</th>
                                                <th
                                                    class="px-4 py-2 text-xs font-medium text-right text-gray-500 uppercase">
                                                    Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($order->content as $item)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-900">
                                                        {{ $item['name'] ?? 'Producto' }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-center">{{ $item['quantity'] ?? 1 }}
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    ${{ number_format($item['price'] ?? 0, 2) }}</td>
                                                <td class="px-4 py-3 font-medium text-right">
                                                    ${{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                                                    2) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                            <tr>
                                                <td colspan="3"
                                                    class="px-4 py-3 font-semibold text-right text-gray-900">Total:
                                                </td>
                                                <td class="px-4 py-3 text-lg font-bold text-right text-green-600">
                                                    ${{ number_format($order->total, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                @else
                                <p class="text-gray-500">No hay información de productos disponible</p>
                                @endif
                            </div>
                        </div>
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

        .status-badge {
            @apply px-3 py-1 text-xs font-medium rounded-full;
        }

        .status-pendiente {
            @apply bg-yellow-100 text-yellow-800;
        }

        .status-verificado {
            @apply bg-blue-100 text-blue-800;
        }

        .status-preparando {
            @apply bg-purple-100 text-purple-800;
        }

        .status-asignado {
            @apply bg-indigo-100 text-indigo-800;
        }

        .status-en-camino {
            @apply bg-orange-100 text-orange-800;
        }

        .status-entregado {
            @apply bg-green-100 text-green-800;
        }

        .status-cancelado {
            @apply bg-red-100 text-red-800;
        }
    </style>
    @endpush

</x-admin-layout>