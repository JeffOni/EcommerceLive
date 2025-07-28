<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Repartidores',
        'route' => route('admin.delivery-drivers.index'),
    ],
    [
        'name' => $deliveryDriver->name,
    ],
]">

    <x-slot name="action">
        <div class="flex gap-2">
            <x-link href="{{ route('admin.delivery-drivers.edit', $deliveryDriver) }}" type="primary" name="Editar" />
            <x-link href="{{ route('admin.delivery-drivers.index') }}" type="secondary" name="Volver" />
        </div>
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
            <div
                class="absolute w-64 h-64 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 bg-gradient-to-r from-blue-100/40 to-purple-100/40 blur-2xl">
            </div>
        </div>

        <div class="relative">
            <!-- Contenedor principal con backdrop blur -->
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header con gradiente -->
                <div class="px-8 py-6 bg-gradient-to-r from-primary-900 to-secondary-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 glass-effect rounded-xl">
                                <i class="text-2xl text-white fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ $deliveryDriver->name }}</h1>
                                <p class="text-blue-100">Información detallada del repartidor</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-4 py-2 text-sm font-bold text-white rounded-full bg-gradient-to-r 
                                {{ $deliveryDriver->is_active ? 'from-secondary-700 to-secondary-900' : 'from-red-400 to-rose-500' }}">
                                {{ $deliveryDriver->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="p-8 bg-white/80 backdrop-blur-sm">
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <!-- Información Principal -->
                        <div class="space-y-6 lg:col-span-2">
                            <!-- Perfil del conductor -->
                            <div class="p-6 bg-white shadow-lg rounded-2xl">
                                <div class="flex flex-col items-start space-y-6 md:flex-row md:space-y-0 md:space-x-6">
                                    <!-- Foto del perfil -->
                                    <div class="flex-shrink-0 text-center">
                                        @if($deliveryDriver->profile_photo)
                                        <img src="{{ $deliveryDriver->profile_photo_url }}"
                                            alt="{{ $deliveryDriver->name }}"
                                            class="object-cover w-32 h-32 mx-auto border-4 border-blue-200 rounded-full shadow-lg">
                                        @else
                                        <div
                                            class="flex items-center justify-center w-32 h-32 mx-auto text-4xl font-bold text-white rounded-full shadow-lg bg-gradient-to-br from-blue-500 to-purple-600">
                                            {{ substr($deliveryDriver->name, 0, 1) }}
                                        </div>
                                        @endif
                                        <div class="mt-4">
                                            @if($deliveryDriver->rating_stars)
                                            <div class="mb-2">{!! $deliveryDriver->rating_stars !!}</div>
                                            <span class="text-sm text-gray-600">({{
                                                number_format($deliveryDriver->rating, 1) }})</span>
                                            @endif
                                            <p class="flex items-center justify-center mt-2 text-gray-600">
                                                <i class="mr-2 text-blue-500 fas fa-shipping-fast"></i>
                                                {{ $deliveryDriver->total_deliveries }} entregas realizadas
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Información de contacto y detalles -->
                                    <div class="grid flex-1 grid-cols-1 gap-6 md:grid-cols-2">
                                        <!-- Columna izquierda -->
                                        <div class="space-y-4">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                                                    <i class="text-sm text-blue-600 fas fa-envelope"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Email</p>
                                                    <a href="mailto:{{ $deliveryDriver->email }}"
                                                        class="font-medium text-blue-600 hover:text-blue-800">
                                                        {{ $deliveryDriver->email }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg">
                                                    <i class="text-sm text-green-600 fas fa-phone"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Teléfono</p>
                                                    <a href="tel:{{ $deliveryDriver->phone }}"
                                                        class="font-medium text-green-600 hover:text-green-800">
                                                        {{ $deliveryDriver->phone }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-lg">
                                                    <i class="text-sm text-purple-600 fas fa-id-card"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Licencia</p>
                                                    <p class="font-medium text-gray-900">{{
                                                        $deliveryDriver->license_number ?? 'No especificada' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-orange-100 rounded-lg">
                                                    <i class="text-sm text-orange-600 fas fa-map-marker-alt"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Zona</p>
                                                    <p class="font-medium text-gray-900">{{ $deliveryDriver->zone ?? 'No
                                                        especificada' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Columna derecha -->
                                        <div class="space-y-4">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-lg">
                                                    <i class="text-sm text-indigo-600 fas fa-car"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Tipo de Vehículo</p>
                                                    <p class="font-medium text-gray-900">{{
                                                        $deliveryDriver->vehicle_type ? ucfirst(str_replace('_', ' ',
                                                        $deliveryDriver->vehicle_type)) : 'No especificado' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-lg">
                                                    <i class="text-sm text-yellow-600 fas fa-barcode"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Placa</p>
                                                    <p class="font-medium text-gray-900">{{
                                                        $deliveryDriver->vehicle_plate ?? 'No especificada' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-teal-100 rounded-lg">
                                                    <i class="text-sm text-teal-600 fas fa-calendar-alt"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Registrado</p>
                                                    <p class="font-medium text-gray-900">{{
                                                        $deliveryDriver->created_at->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg">
                                                    <i class="text-sm text-gray-600 fas fa-info-circle"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Estado</p>
                                                    <span
                                                        class="px-3 py-1 text-sm font-medium rounded-full 
                                                        {{ $deliveryDriver->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $deliveryDriver->is_active ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($deliveryDriver->notes)
                                <div class="p-4 mt-6 border border-blue-200 bg-blue-50 rounded-xl">
                                    <h6 class="flex items-center mb-2 font-semibold text-blue-800">
                                        <i class="mr-2 fas fa-sticky-note"></i>
                                        Notas:
                                    </h6>
                                    <p class="text-blue-700">{{ $deliveryDriver->notes }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Historial de envíos -->
                            <div class="p-6 bg-white shadow-lg rounded-2xl">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="flex items-center text-xl font-bold text-gray-900">
                                        <i class="mr-3 text-blue-600 fas fa-shipping-fast"></i>
                                        Historial de Envíos
                                    </h3>
                                    <a href="{{ route('admin.shipments.index', ['driver_id' => $deliveryDriver->id]) }}"
                                        class="px-4 py-2 font-medium text-blue-700 transition-colors bg-blue-100 rounded-lg hover:bg-blue-200">
                                        <i class="mr-2 fas fa-eye"></i> Ver Todos
                                    </a>
                                </div>

                                @if ($deliveryDriver->shipments->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="border-b border-gray-200">
                                                <th class="px-2 py-3 font-semibold text-left text-gray-700">Tracking
                                                </th>
                                                <th class="px-2 py-3 font-semibold text-left text-gray-700">Orden</th>
                                                <th class="px-2 py-3 font-semibold text-left text-gray-700">Cliente</th>
                                                <th class="px-2 py-3 font-semibold text-left text-gray-700">Estado</th>
                                                <th class="px-2 py-3 font-semibold text-left text-gray-700">Fecha</th>
                                                <th class="px-2 py-3 font-semibold text-left text-gray-700">Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($deliveryDriver->shipments->take(10) as $shipment)
                                            <tr class="transition-colors hover:bg-gray-50">
                                                <td class="px-2 py-3">
                                                    <span class="font-bold text-blue-600">{{ $shipment->tracking_number
                                                        }}</span>
                                                </td>
                                                <td class="px-2 py-3">
                                                    <a href="{{ route('admin.orders.show', $shipment->order) }}"
                                                        class="font-medium text-blue-600 hover:text-blue-800">
                                                        #{{ $shipment->order->order_number }}
                                                    </a>
                                                </td>
                                                <td class="px-2 py-3">{{ $shipment->order->user->name }}</td>
                                                <td class="px-2 py-3">
                                                    <span
                                                        class="px-3 py-1 text-xs font-medium rounded-full
                                                        {{ $shipment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                           ($shipment->status == 'picked_up' ? 'bg-blue-100 text-blue-800' : 
                                                           ($shipment->status == 'in_transit' ? 'bg-indigo-100 text-indigo-800' : 
                                                           ($shipment->status == 'out_for_delivery' ? 'bg-orange-100 text-orange-800' : 
                                                           ($shipment->status == 'delivered' ? 'bg-green-100 text-green-800' : 
                                                           ($shipment->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))))) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $shipment->status->value)) }}
                                                    </span>
                                                </td>
                                                <td class="px-2 py-3">{{ $shipment->created_at->format('d/m/Y') }}</td>
                                                <td class="px-2 py-3">
                                                    <a href="{{ route('admin.shipments.show', $shipment) }}"
                                                        class="px-3 py-1 text-sm text-blue-700 transition-colors bg-blue-100 rounded-lg hover:bg-blue-200">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($deliveryDriver->shipments->count() > 10)
                                <div class="mt-6 text-center">
                                    <a href="{{ route('admin.shipments.index', ['driver_id' => $deliveryDriver->id]) }}"
                                        class="px-6 py-2 font-medium text-blue-700 transition-colors bg-blue-100 rounded-lg hover:bg-blue-200">
                                        Ver todos los {{ $deliveryDriver->shipments->count() }} envíos
                                    </a>
                                </div>
                                @endif
                                @else
                                <div class="py-12 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full">
                                        <i class="text-2xl text-gray-400 fas fa-inbox"></i>
                                    </div>
                                    <p class="mb-4 text-gray-600">No hay envíos asignados a este repartidor</p>
                                    <a href="{{ route('admin.shipments.create', ['driver_id' => $deliveryDriver->id]) }}"
                                        class="px-6 py-3 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        <i class="mr-2 fas fa-plus"></i> Crear Primer Envío
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Estadísticas -->
                            <div class="p-6 bg-white shadow-lg rounded-2xl">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-900">
                                    <i class="mr-3 text-purple-600 fas fa-chart-pie"></i>
                                    Estadísticas de Rendimiento
                                </h3>
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="p-4 text-center bg-blue-50 rounded-xl">
                                        <div class="mb-2 text-blue-600">
                                            <i class="text-2xl fas fa-shipping-fast"></i>
                                        </div>
                                        <h5 class="text-2xl font-bold text-gray-900">{{
                                            $deliveryDriver->shipments->count() }}</h5>
                                        <span class="text-sm font-medium text-gray-600 uppercase">Total Envíos</span>
                                    </div>
                                    <div class="p-4 text-center bg-green-50 rounded-xl">
                                        <div class="mb-2 text-green-600">
                                            <i class="text-2xl fas fa-check"></i>
                                        </div>
                                        <h5 class="text-2xl font-bold text-gray-900">
                                            {{ $deliveryDriver->shipments->where('status', 'delivered')->count() }}
                                        </h5>
                                        <span class="text-sm font-medium text-gray-600 uppercase">Entregados</span>
                                    </div>
                                    <div class="p-4 text-center bg-yellow-50 rounded-xl">
                                        <div class="mb-2 text-yellow-600">
                                            <i class="text-2xl fas fa-clock"></i>
                                        </div>
                                        <h5 class="text-2xl font-bold text-gray-900">
                                            {{ $deliveryDriver->shipments->whereIn('status', ['pending', 'picked_up',
                                            'in_transit', 'out_for_delivery'])->count() }}
                                        </h5>
                                        <span class="text-sm font-medium text-gray-600 uppercase">En Proceso</span>
                                    </div>
                                    <div class="p-4 text-center bg-red-50 rounded-xl">
                                        <div class="mb-2 text-red-600">
                                            <i class="text-2xl fas fa-times"></i>
                                        </div>
                                        <h5 class="text-2xl font-bold text-gray-900">
                                            {{ $deliveryDriver->shipments->where('status', 'failed')->count() }}
                                        </h5>
                                        <span class="text-sm font-medium text-gray-600 uppercase">Fallidos</span>
                                    </div>
                                </div>

                                @if ($deliveryDriver->shipments->count() > 0)
                                @php
                                $successRate = $deliveryDriver->shipments->count() > 0
                                ? round(($deliveryDriver->shipments->where('status', 'delivered')->count() /
                                $deliveryDriver->shipments->count()) * 100, 1)
                                : 0;
                                @endphp
                                <div class="mt-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-gray-700">Tasa de Éxito</span>
                                        <span class="font-bold text-gray-900">{{ $successRate }}%</span>
                                    </div>
                                    <div class="w-full h-3 bg-gray-200 rounded-full">
                                        <div class="h-3 rounded-full transition-all duration-500 
                                            {{ $successRate >= 80 ? 'bg-gradient-to-r from-green-400 to-green-600' : 
                                               ($successRate >= 60 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : 'bg-gradient-to-r from-red-400 to-red-600') }}"
                                            style="width: {{ $successRate }}%"></div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Información de contacto -->
                            <div class="p-6 bg-white shadow-lg rounded-2xl">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-900">
                                    <i class="mr-3 text-green-600 fas fa-address-card"></i>
                                    Información de Contacto
                                </h3>
                                <div class="space-y-3">
                                    <a href="tel:{{ $deliveryDriver->phone }}"
                                        class="flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors bg-green-600 rounded-xl hover:bg-green-700">
                                        <i class="mr-2 fas fa-phone"></i>
                                        Llamar: {{ $deliveryDriver->phone }}
                                    </a>
                                    <a href="mailto:{{ $deliveryDriver->email }}"
                                        class="flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors bg-blue-600 rounded-xl hover:bg-blue-700">
                                        <i class="mr-2 fas fa-envelope"></i>
                                        Enviar Email
                                    </a>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $deliveryDriver->phone) }}"
                                        target="_blank"
                                        class="flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors bg-green-500 rounded-xl hover:bg-green-600">
                                        <i class="mr-2 fab fa-whatsapp"></i>
                                        WhatsApp
                                    </a>
                                </div>
                            </div>

                            <!-- Información del vehículo -->
                            @if ($deliveryDriver->vehicle_type)
                            <div class="p-6 bg-white shadow-lg rounded-2xl">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-900">
                                    <i class="mr-3 text-indigo-600 fas fa-car"></i>
                                    Información del Vehículo
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                        <span class="font-medium text-gray-600">Tipo:</span>
                                        <span class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ',
                                            $deliveryDriver->vehicle_type)) }}</span>
                                    </div>
                                    @if ($deliveryDriver->vehicle_plate)
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                        <span class="font-medium text-gray-600">Placa:</span>
                                        <span class="font-semibold text-gray-900">{{ $deliveryDriver->vehicle_plate
                                            }}</span>
                                    </div>
                                    @endif
                                    @if ($deliveryDriver->license_number)
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                        <span class="font-medium text-gray-600">Licencia:</span>
                                        <span class="font-semibold text-gray-900">{{ $deliveryDriver->license_number
                                            }}</span>
                                    </div>
                                    @endif
                                    @if ($deliveryDriver->zone)
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                        <span class="font-medium text-gray-600">Zona:</span>
                                        <span class="font-semibold text-gray-900">{{ $deliveryDriver->zone }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Acciones rápidas -->
                            <div class="p-6 bg-white shadow-lg rounded-2xl">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-900">
                                    <i class="mr-3 text-yellow-600 fas fa-bolt"></i>
                                    Acciones Rápidas
                                </h3>
                                <div class="space-y-3">
                                    <a href="{{ route('admin.delivery-drivers.edit', $deliveryDriver) }}"
                                        class="flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors bg-blue-600 rounded-xl hover:bg-blue-700">
                                        <i class="mr-2 fas fa-edit"></i>
                                        Editar Información
                                    </a>

                                    <form action="{{ route('admin.delivery-drivers.toggle-status', $deliveryDriver) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="flex items-center justify-center w-full px-4 py-3 rounded-xl transition-colors font-medium
                                                {{ $deliveryDriver->is_active ? 'bg-yellow-600 hover:bg-yellow-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                                            onclick="return confirm('¿Estás seguro de {{ $deliveryDriver->is_active ? 'desactivar' : 'activar' }} este repartidor?')">
                                            <i
                                                class="fas fa-{{ $deliveryDriver->is_active ? 'pause' : 'play' }} mr-2"></i>
                                            {{ $deliveryDriver->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.shipments.create', ['driver_id' => $deliveryDriver->id]) }}"
                                        class="flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors bg-purple-600 rounded-xl hover:bg-purple-700">
                                        <i class="mr-2 fas fa-plus"></i>
                                        Crear Envío
                                    </a>

                                    <a href="{{ route('admin.shipments.index', ['driver_id' => $deliveryDriver->id]) }}"
                                        class="flex items-center justify-center w-full px-4 py-3 font-medium text-white transition-colors bg-gray-600 rounded-xl hover:bg-gray-700">
                                        <i class="mr-2 fas fa-list"></i>
                                        Ver Todos los Envíos
                                    </a>
                                </div>
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
    </style>
    @endpush

</x-admin-layout>