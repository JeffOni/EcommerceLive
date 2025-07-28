<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Envíos',
        'route' => route('admin.shipments.index'),
    ],
    [
        'name' => 'Editar Envío',
    ],
]">
    <x-slot name="action">
        <x-link href="{{ route('admin.shipments.index') }}" type="secondary" name="Regresar"
            class="px-6 py-3 font-semibold text-white transition-all duration-300 transform bg-red-500 shadow-lg hover:bg-red-600 rounded-xl hover:shadow-xl hover:scale-105" />

    </x-slot>
    <div class="min-h-screen overflow-x-hidden bg-gray-50">
        <div class="w-full max-w-sm px-3 py-4 mx-auto sm:max-w-2xl sm:px-6 lg:max-w-5xl lg:px-8">
            <!-- Header -->
            <div class="mb-6 text-center">
                <h1 class="mb-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                    Editar Envío
                </h1>
                <p class="text-sm text-gray-600 sm:text-base">
                    Tracking: {{ $shipment->tracking_number }} • Orden #{{ $shipment->order->id }}
                </p>

                @php
                $orderStatus = $shipment->order->status ?? 1;
                if ($orderStatus == 6) {
                $statusColor = 'bg-green-100 text-green-800';
                $statusText = 'Entregado';
                $statusIcon = 'fas fa-check-circle';
                } elseif ($orderStatus == 7) {
                $statusColor = 'bg-red-100 text-red-800';
                $statusText = 'Cancelado';
                $statusIcon = 'fas fa-times-circle';
                } elseif ($orderStatus == 4) {
                $statusColor = 'bg-yellow-100 text-yellow-800';
                $statusText = 'Asignado';
                $statusIcon = 'fas fa-user-check';
                } elseif ($orderStatus == 5) {
                $statusColor = 'bg-blue-100 text-blue-800';
                $statusText = 'En tránsito';
                $statusIcon = 'fas fa-truck-moving';
                } else {
                $statusColor = 'bg-gray-100 text-gray-800';
                $statusText = 'Pendiente';
                $statusIcon = 'fas fa-clock';
                }
                @endphp

                <div class="flex justify-center mt-3">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                        <i class="{{ $statusIcon }} mr-2"></i>
                        {{ $statusText }}
                    </span>
                </div>
            </div>

            <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Información General del Envío -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                        <h3 class="flex items-center text-lg font-semibold text-gray-900">
                            <i class="mr-2 text-indigo-600 fas fa-edit"></i>
                            Información del Envío
                        </h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <!-- Orden y Tracking -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Orden Asociada</label>
                                <div class="flex">
                                    <input type="text" value="Orden #{{ $shipment->order->id }}"
                                        class="flex-1 px-3 py-2 text-sm border border-gray-300 bg-gray-50 rounded-l-md"
                                        readonly>
                                    <a href="{{ route('admin.orders.show', $shipment->order) }}"
                                        class="px-3 py-2 text-sm text-white transition-colors bg-indigo-600 border border-indigo-600 hover:bg-indigo-700 rounded-r-md"
                                        target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Número de
                                    Seguimiento</label>
                                <input type="text" value="{{ $shipment->tracking_number }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-50"
                                    readonly>
                            </div>
                        </div>

                        <!-- Repartidor -->
                        <div>
                            <label for="delivery_driver_id" class="block mb-2 text-sm font-medium text-gray-700">
                                Repartidor <span class="text-red-500">*</span>
                            </label>
                            <select name="delivery_driver_id" id="delivery_driver_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Seleccionar repartidor...</option>
                                @foreach ($deliveryDrivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('delivery_driver_id', $shipment->
                                    delivery_driver_id) == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }} - {{ $driver->phone }}
                                    @if (!$driver->is_active) (Inactivo) @endif
                                </option>
                                @endforeach
                            </select>
                            @error('delivery_driver_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notas -->
                        <div>
                            <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">
                                Notas / Observaciones
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Agregar notas sobre el envío...">{{ old('notes', $shipment->notes) }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Cliente y Orden -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                            <h3 class="flex items-center text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-green-600 fas fa-shopping-cart"></i>
                                Información de la Orden
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-sm font-medium text-gray-500">Cliente:</span>
                                    <span class="text-sm text-gray-900">{{ $shipment->order->user->name }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <span class="text-sm text-gray-900 break-all sm:break-normal">{{
                                        $shipment->order->user->email }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-sm font-medium text-gray-500">Total:</span>
                                    <span class="text-sm font-semibold text-gray-900">${{
                                        number_format($shipment->order->total, 2) }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-sm font-medium text-gray-500">Estado Orden:</span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }} mt-1 sm:mt-0">
                                        <i class="{{ $statusIcon }} mr-1"></i>
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dirección de Entrega -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                            <h3 class="flex items-center text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-red-600 fas fa-map-marker-alt"></i>
                                Dirección de Entrega
                            </h3>
                        </div>
                        <div class="p-4">
                            @if($shipment->delivery_address)
                            @php
                            $address = $shipment->delivery_address;
                            if (is_string($address)) {
                            $address = json_decode($address, true) ?? [];
                            } elseif (!is_array($address)) {
                            $address = [];
                            }
                            @endphp
                            <div class="space-y-2 text-sm text-gray-700">
                                @if(isset($address['street']))
                                <p class="font-medium">{{ $address['street'] }}</p>
                                @endif
                                @if(isset($address['city']) || isset($address['province']))
                                <p>{{ $address['city'] ?? '' }}{{ isset($address['city']) && isset($address['province'])
                                    ? ', ' : '' }}{{ $address['province'] ?? '' }}</p>
                                @endif
                                @if(isset($address['postal_code']))
                                <p>CP: {{ $address['postal_code'] }}</p>
                                @endif
                                @if(isset($address['phone']))
                                <p><strong>Teléfono:</strong> {{ $address['phone'] }}</p>
                                @endif
                                @if(isset($address['reference']))
                                <p class="italic text-gray-600">Referencia: {{ $address['reference'] }}</p>
                                @endif
                            </div>
                            @else
                            <p class="text-sm text-gray-500">No disponible</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($shipment->deliveryDriver)
                <!-- Repartidor Asignado -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-4 py-3 border-b border-gray-200 rounded-t-lg bg-gray-50">
                        <h3 class="flex items-center text-lg font-semibold text-gray-900">
                            <i class="mr-2 text-purple-600 fas fa-motorcycle"></i>
                            Repartidor Asignado
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center">
                            <div
                                class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-r from-green-500 to-emerald-500">
                                <span class="text-lg font-medium text-white">
                                    {{ substr($shipment->deliveryDriver->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">{{ $shipment->deliveryDriver->name }}</p>
                                <p class="text-sm text-gray-500">{{ $shipment->deliveryDriver->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Botones de Acción -->
                <div
                    class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-200">
                    @php
                    $orderStatus = $shipment->order->status ?? 1;
                    $isInTransit = in_array($orderStatus, [4, 5]); // Estados en tránsito
                    $isDelivered = $orderStatus == 6; // Estado entregado
                    $isCancelled = $orderStatus == 7; // Estado cancelado
                    @endphp

                    <!-- Botón Regresar - siempre visible -->
                    <a href="{{ route('admin.shipments.index') }}"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Regresar
                    </a>

                    @if($isDelivered)
                    <!-- Estado Entregado -->
                    <div
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-green-50">
                        <i class="fas fa-check-circle mr-2"></i>
                        Envío Entregado
                    </div>
                    @elseif($isCancelled)
                    <!-- Estado Cancelado -->
                    <div
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50">
                        <i class="fas fa-times-circle mr-2"></i>
                        Envío Cancelado
                    </div>
                    @elseif($isInTransit)
                    <!-- Botones de acción para envíos en tránsito -->
                    <button type="button" onclick="markAsDelivered()"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Marcar como Entregado
                    </button>
                    <button type="button" onclick="cancelShipment()"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar Envío
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Envío
                    </button>
                    @else
                    <!-- Botón de actualizar para envíos pendientes -->
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Envío
                    </button>
                    @endif
                </div>
            </form>

            <!-- Timeline del Envío -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-history text-blue-600 mr-2"></i>
                        Historial del Envío
                    </h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Envío creado</div>
                                <div class="text-sm text-gray-500">{{ $shipment->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>

                        @if($shipment->updated_at != $shipment->created_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-edit text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Última actualización</div>
                                <div class="text-sm text-gray-500">{{ $shipment->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($shipment->actual_pickup_date)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-box text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Paquete recogido</div>
                                <div class="text-sm text-gray-500">{{ $shipment->actual_pickup_date->format('d/m/Y H:i')
                                    }}</div>
                            </div>
                        </div>
                        @endif

                        @if($shipment->actual_delivery_date)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Paquete entregado</div>
                                <div class="text-sm text-gray-500">{{ $shipment->actual_delivery_date->format('d/m/Y
                                    H:i') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            function markAsDelivered() {
                Swal.fire({
                    title: '¿Marcar como entregado?',
                    text: 'Esta acción cambiará el estado del envío a entregado',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#EF4444',
                    confirmButtonText: 'Sí, entregar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route("admin.shipments.update", $shipment) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status: 6 // Estado entregado
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Entregado!',
                                    text: 'El envío ha sido marcado como entregado',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Error al entregar');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo marcar como entregado',
                                icon: 'error'
                            });
                        });
                    }
                });
            }

            function cancelShipment() {
                Swal.fire({
                    title: '¿Cancelar envío?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'No cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route("admin.shipments.update", $shipment) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status: 7 // Estado cancelado
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Cancelado!',
                                    text: 'El envío ha sido cancelado',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Error al cancelar');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo cancelar el envío',
                                icon: 'error'
                            });
                        });
                    }
                });
            }
        </script>
</x-admin-layout>