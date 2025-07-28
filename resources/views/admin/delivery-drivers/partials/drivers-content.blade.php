@if ($drivers->count())
<!-- Vista de Tarjetas -->
<div id="cards-view" class="view-content">
    <div
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 p-3 sm:p-5 lg:p-8">
        @foreach($drivers as $driver)
        <div
            class="driver-card group bg-white rounded-lg sm:rounded-xl shadow-sm hover:shadow-md border border-gray-200 hover:border-primary-300 transition-all duration-300 overflow-hidden">
            <!-- Header compacto -->
            <div class="p-3 sm:p-4 bg-gradient-to-r from-primary-50 to-secondary-50 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-primary-500 to-secondary-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-sm sm:text-base">
                            {{ substr($driver->name, 0, 2) }}
                        </span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-gray-900 text-sm sm:text-base truncate">{{ $driver->name }}</h3>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500 truncate">{{ $driver->phone }}</p>
                            <span class="status-badge {{ $driver->status_class }} ml-2">
                                {{ $driver->status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido compacto -->
            <div class="p-3 sm:p-4">
                <!-- Info principal en grid -->
                <div class="grid grid-cols-2 gap-x-3 gap-y-2 mb-3 text-xs sm:text-sm">
                    <div class="flex items-center">
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
                        <i class="{{ $vehicleIcon }} text-gray-400 mr-1"></i>
                        <span class="text-gray-600 truncate">{{ ucfirst($driver->vehicle_type) }}</span>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-box text-gray-400 mr-1"></i>
                        <span class="text-gray-600">{{ $driver->total_deliveries }} entregas</span>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                        <span class="text-gray-600">{{ $driver->rating }}</span>
                    </div>

                    <div class="flex items-center">
                        <i class="fas fa-dollar-sign text-green-500 mr-1"></i>
                        <span class="text-gray-600">${{ number_format($driver->delivery_fee, 2) }}</span>
                    </div>
                </div>

                <!-- Acciones compactas -->
                <div class="flex space-x-1">
                    <button onclick="toggleDriverStatus({{ $driver->id }})"
                        title="{{ $driver->is_active ? 'Desactivar' : 'Activar' }}"
                        class="flex-1 px-1.5 py-1 text-xs font-medium {{ $driver->is_active ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} bg-white border border-gray-300 rounded transition-colors">
                        <i class="fas {{ $driver->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                    </button>

                    <a href="{{ route('admin.delivery-drivers.show', $driver) }}" title="Ver detalles"
                        class="flex-1 px-1.5 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors text-center">
                        <i class="fas fa-eye"></i>
                    </a>

                    <a href="{{ route('admin.delivery-drivers.edit', $driver) }}" title="Editar"
                        class="flex-1 px-1.5 py-1 text-xs font-medium text-blue-600 bg-white border border-gray-300 rounded hover:bg-blue-50 transition-colors text-center">
                        <i class="fas fa-edit"></i>
                    </a>

                    <button onclick="deleteDriver({{ $driver->id }})" title="Eliminar"
                        class="flex-1 px-1.5 py-1 text-xs font-medium text-red-600 bg-white border border-gray-300 rounded hover:bg-red-50 transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Vista de Tabla -->
<div id="table-view" class="view-content hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Repartidor
                    </th>
                    <th
                        class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                        Contacto
                    </th>
                    <th class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Vehículo
                    </th>
                    <th
                        class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                        Stats
                    </th>
                    <th class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th
                        class="px-2 sm:px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($drivers as $driver)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8">
                                <div
                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gradient-to-r from-primary-500 to-secondary-500 flex items-center justify-center">
                                    <span class="text-white font-medium text-xs">
                                        {{ substr($driver->name, 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-2">
                                <div class="text-xs font-medium text-gray-900 truncate max-w-20 sm:max-w-32">{{
                                    $driver->name }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-20 sm:max-w-32">{{
                                    $driver->identification_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap hidden sm:table-cell">
                        <div class="text-xs text-gray-900">
                            <div class="truncate max-w-24 sm:max-w-32"><i class="fas fa-envelope mr-1"></i>{{
                                $driver->email }}</div>
                            <div class="truncate max-w-24 sm:max-w-32"><i class="fas fa-phone mr-1"></i>{{
                                $driver->phone }}</div>
                        </div>
                    </td>
                    <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
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
                        <span class="vehicle-badge {{ $vehicleClass }} text-xs">
                            <i class="{{ $vehicleIcon }} mr-1"></i>{{ ucfirst($driver->vehicle_type) }}
                        </span>
                        @if ($driver->vehicle_plate)
                        <div class="text-xs text-gray-500 mt-1 truncate max-w-16">{{ $driver->vehicle_plate }}</div>
                        @endif
                    </td>
                    <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap hidden lg:table-cell">
                        <div class="text-xs text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-box mr-1 text-xs"></i>{{ $driver->total_deliveries }} entregas
                            </div>
                            <div class="flex items-center mt-1">
                                {!! $driver->rating_stars !!}
                                <span class="ml-1 text-xs text-gray-500">({{ $driver->rating }})</span>
                            </div>
                            <div class="text-xs text-gray-500">${{ number_format($driver->delivery_fee, 2) }} por
                                entrega</div>
                        </div>
                    </td>
                    <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                        <span class="status-badge {{ $driver->status_class }} text-xs">
                            {{ $driver->status_label }}
                        </span>
                    </td>
                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center space-x-0.5 sm:space-x-1">
                            <button onclick="toggleDriverStatus({{ $driver->id }})"
                                title="{{ $driver->is_active ? 'Desactivar' : 'Activar' }}"
                                class="inline-flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 text-xs {{ $driver->is_active ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} bg-white border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary-500 transition-colors">
                                <i class="fas {{ $driver->is_active ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                            </button>

                            <a href="{{ route('admin.delivery-drivers.show', $driver) }}" title="Ver detalles"
                                class="inline-flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 text-xs text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary-500 transition-colors">
                                <i class="fas fa-eye text-xs"></i>
                            </a>

                            <a href="{{ route('admin.delivery-drivers.edit', $driver) }}" title="Editar"
                                class="inline-flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 text-xs text-blue-600 bg-white border border-gray-300 rounded hover:bg-blue-50 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </a>

                            <button onclick="deleteDriver({{ $driver->id }})" title="Eliminar"
                                class="inline-flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 text-xs text-red-600 bg-white border border-gray-300 rounded hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-500 transition-colors">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<!-- Estado vacío responsive -->
<div class="py-8 sm:py-12 lg:py-16 text-center">
    <div
        class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 mb-4 sm:mb-6 rounded-full bg-gradient-to-br from-secondary-100 to-primary-100">
        <i class="text-2xl sm:text-3xl lg:text-4xl text-secondary-500 fas fa-truck"></i>
    </div>
    <h3 class="mb-3 sm:mb-4 text-lg sm:text-xl lg:text-2xl font-semibold text-gray-800">No hay repartidores registrados
    </h3>
    <p class="max-w-sm sm:max-w-md mx-auto mb-6 sm:mb-8 text-sm sm:text-base text-gray-600 px-4">
        No se encontraron repartidores con los filtros aplicados. Agrega el primer repartidor para comenzar.
    </p>
    <a href="{{ route('admin.delivery-drivers.create') }}"
        class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 lg:px-8 lg:py-3 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 rounded-lg sm:rounded-xl hover:shadow-xl hover:scale-105">
        <i class="mr-2 sm:mr-3 text-white fas fa-plus text-sm sm:text-base"></i>
        <span class="text-white text-sm sm:text-base">Agregar Primer Repartidor</span>
    </a>
</div>
@endif

<!-- Paginación -->
@if ($drivers->hasPages())
<div class="px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50">
    {{ $drivers->links() }}
</div>
@endif