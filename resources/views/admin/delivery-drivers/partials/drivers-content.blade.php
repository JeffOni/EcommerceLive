<!-- Vista responsiva: Tabla en desktop, cards en móvil -->
<div class="hidden lg:block">
    <!-- Tabla para escritorio -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Repartidor
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contacto
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Vehículo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estadísticas
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($drivers as $driver)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-r from-orange-400 to-red-500 flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">
                                        {{ substr($driver->name, 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">{{ $driver->name }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $driver->identification_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <div><i class="fas fa-envelope mr-1"></i>{{ $driver->email }}</div>
                            <div><i class="fas fa-phone mr-1"></i>{{ $driver->phone }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            @php
                            $vehicleClass = match ($driver->vehicle_type) {
                            'moto' => 'vehicle-moto',
                            'auto' => 'vehicle-auto',
                            'bicicleta' => 'vehicle-bicicleta',
                            'camion' => 'vehicle-camion',
                            'furgoneta' => 'vehicle-furgoneta',
                            default => 'bg-gray-100 text-gray-800',
                            };

                            $vehicleIcon = match ($driver->vehicle_type) {
                            'moto' => 'fas fa-motorcycle',
                            'auto' => 'fas fa-car',
                            'bicicleta' => 'fas fa-bicycle',
                            'camion' => 'fas fa-truck',
                            'furgoneta' => 'fas fa-shipping-fast',
                            default => 'fas fa-question',
                            };
                            @endphp
                            <span class="vehicle-badge {{ $vehicleClass }}">
                                <i class="{{ $vehicleIcon }} mr-1"></i>{{ ucfirst($driver->vehicle_type) }}
                            </span>
                            @if ($driver->vehicle_plate)
                            <div class="text-xs text-gray-500 mt-1">{{ $driver->vehicle_plate }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-box mr-1"></i>
                                {{ $driver->total_deliveries }} entregas
                            </div>
                            <div class="flex items-center mt-1">
                                {!! $driver->rating_stars !!}
                                <span class="ml-1 text-xs text-gray-500">({{ $driver->rating }})</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                ${{ number_format($driver->delivery_fee, 2) }} por entrega
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge {{ $driver->status_class }}">
                            {{ $driver->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Toggle Estado -->
                            <button onclick="toggleDriverStatus({{ $driver->id }})"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium {{ $driver->is_active ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <i class="fas {{ $driver->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                {{ $driver->is_active ? 'Desactivar' : 'Activar' }}
                            </button>

                            <!-- Editar -->
                            <a href="{{ route('admin.delivery-drivers.edit', $driver) }}"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-600 bg-white border border-gray-300 rounded-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit mr-1"></i>
                                Editar
                            </a>

                            <!-- Ver Detalles -->
                            <a href="{{ route('admin.delivery-drivers.show', $driver) }}"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <i class="fas fa-eye mr-1"></i>
                                Ver
                            </a>

                            <!-- Eliminar -->
                            <button onclick="deleteDriver({{ $driver->id }})"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-600 bg-white border border-gray-300 rounded-md hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-trash mr-1"></i>
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="mb-4 text-6xl text-gray-300 fas fa-truck"></i>
                            <h3 class="mb-2 text-lg font-medium text-gray-900">No hay repartidores</h3>
                            <p class="text-gray-500">No se encontraron repartidores con los filtros aplicados.</p>
                            <a href="{{ route('admin.delivery-drivers.create') }}"
                                class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md hover:bg-orange-700">
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Primer Repartidor
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Cards para móvil -->
<div class="lg:hidden">
    <div class="space-y-4">
        @forelse($drivers as $driver)
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div
                                class="h-10 w-10 rounded-full bg-gradient-to-r from-orange-400 to-red-500 flex items-center justify-center">
                                <span class="text-white font-medium text-sm">
                                    {{ substr($driver->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">{{ $driver->name }}</div>
                            <div class="text-sm text-gray-500">{{ $driver->email }}</div>
                        </div>
                    </div>
                    <span class="status-badge {{ $driver->status_class }}">
                        {{ $driver->status_label }}
                    </span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Vehículo:</span>
                        @php
                        $vehicleClass = match ($driver->vehicle_type) {
                        'moto' => 'vehicle-moto',
                        'auto' => 'vehicle-auto',
                        'bicicleta' => 'vehicle-bicicleta',
                        'camion' => 'vehicle-camion',
                        'furgoneta' => 'vehicle-furgoneta',
                        default => 'bg-gray-100 text-gray-800',
                        };
                        @endphp
                        <span class="vehicle-badge {{ $vehicleClass }}">
                            {{ ucfirst($driver->vehicle_type) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Entregas:</span>
                        <span>{{ $driver->total_deliveries }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Calificación:</span>
                        <div class="flex items-center">
                            {!! $driver->rating_stars !!}
                            <span class="ml-1 text-xs">({{ $driver->rating }})</span>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tarifa:</span>
                        <span>${{ number_format($driver->delivery_fee, 2) }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.delivery-drivers.show', $driver) }}"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                            <i class="fas fa-eye mr-1"></i>Ver
                        </a>
                        <a href="{{ route('admin.delivery-drivers.edit', $driver) }}"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-white border border-gray-300 rounded hover:bg-blue-50">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </a>
                    </div>

                    <div class="flex space-x-2">
                        <button onclick="toggleDriverStatus({{ $driver->id }})"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium {{ $driver->is_active ? 'text-red-600' : 'text-green-600' }} bg-white border border-gray-300 rounded">
                            <i class="fas {{ $driver->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                            {{ $driver->is_active ? 'Pausar' : 'Activar' }}
                        </button>
                        <button onclick="deleteDriver({{ $driver->id }})"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 bg-white border border-gray-300 rounded hover:bg-red-50">
                            <i class="fas fa-trash mr-1"></i>Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="mb-4 text-6xl text-gray-300 fas fa-truck"></i>
            <h3 class="mb-2 text-lg font-medium text-gray-900">No hay repartidores</h3>
            <p class="text-gray-500">No se encontraron repartidores con los filtros aplicados.</p>
            <a href="{{ route('admin.delivery-drivers.create') }}"
                class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md hover:bg-orange-700">
                <i class="fas fa-plus mr-2"></i>
                Agregar Primer Repartidor
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Paginación -->
@if ($drivers->hasPages())
<div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
    {{ $drivers->links() }}
</div>
@endif