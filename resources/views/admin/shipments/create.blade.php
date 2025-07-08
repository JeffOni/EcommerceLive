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
        'name' => 'Crear Envío',
    ],
]">

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

        <div class="relative py-8">
            <div class="mx-4 overflow-hidden shadow-2xl glass-effect rounded-3xl lg:mx-8">
                <!-- Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-indigo-600 to-purple-600">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 glass-effect rounded-xl">
                            <i class="text-xl text-white fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Crear Nuevo Envío</h2>
                            <p class="text-sm text-indigo-100">Registra un nuevo envío para una orden</p>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="p-8 bg-white">
                    <form action="{{ route('admin.shipments.store') }}" method="POST" class="space-y-8"
                        id="shipment-form">
                        @csrf

                        <!-- Información de la Orden -->
                        <div class="p-6 bg-gray-50 rounded-xl">
                            <h3 class="mb-4 text-lg font-medium text-gray-900">
                                <i class="mr-2 text-indigo-600 fas fa-shopping-cart"></i>
                                Información de la Orden
                            </h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Selección de Orden -->
                                <div>
                                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Orden <span class="text-red-500">*</span>
                                    </label>
                                    <select name="order_id" id="order_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('order_id') border-red-500 @enderror">
                                        <option value="">Seleccionar orden...</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}" data-total="{{ $order->total }}"
                                                data-customer="{{ $order->user->name }}"
                                                data-address="{{ $order->shipping_address }}"
                                                {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                #{{ $order->id }} - {{ $order->user->name }}
                                                (${{ number_format($order->total, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Información de la orden seleccionada -->
                                <div id="order-info" class="hidden p-4 bg-white border border-gray-200 rounded-lg">
                                    <h4 class="font-medium text-gray-900">Detalles de la orden</h4>
                                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                                        <div>Cliente: <span id="order-customer"></span></div>
                                        <div>Total: $<span id="order-total"></span></div>
                                        <div>Dirección: <span id="order-address"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Envío -->
                        <div class="p-6 bg-gray-50 rounded-xl">
                            <h3 class="mb-4 text-lg font-medium text-gray-900">
                                <i class="mr-2 text-indigo-600 fas fa-truck"></i>
                                Información del Envío
                            </h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Código de Rastreo -->
                                <div>
                                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        Código de Rastreo
                                    </label>
                                    <div class="flex">
                                        <input type="text" name="tracking_number" id="tracking_number"
                                            value="{{ old('tracking_number') }}"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tracking_number') border-red-500 @enderror"
                                            placeholder="Se generará automáticamente si se deja vacío">
                                        <button type="button" onclick="generateTrackingNumber()"
                                            class="px-4 py-2 text-sm text-white bg-indigo-600 border border-indigo-600 rounded-r-lg hover:bg-indigo-700">
                                            <i class="fas fa-random"></i>
                                        </button>
                                    </div>
                                    @error('tracking_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Estado <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                            Pendiente</option>
                                        <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>
                                            Asignado</option>
                                        <option value="in_transit"
                                            {{ old('status') == 'in_transit' ? 'selected' : '' }}>En Tránsito</option>
                                        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>
                                            Entregado</option>
                                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>
                                            Fallido</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Repartidor -->
                                <div>
                                    <label for="delivery_driver_id"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Repartidor
                                    </label>
                                    <select name="delivery_driver_id" id="delivery_driver_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('delivery_driver_id') border-red-500 @enderror">
                                        <option value="">Sin asignar</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}"
                                                {{ old('delivery_driver_id') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }} - {{ $driver->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('delivery_driver_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha Estimada de Entrega -->
                                <div>
                                    <label for="estimated_delivery_date"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha Estimada de Entrega
                                    </label>
                                    <input type="date" name="estimated_delivery_date" id="estimated_delivery_date"
                                        value="{{ old('estimated_delivery_date') }}" min="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('estimated_delivery_date') border-red-500 @enderror">
                                    @error('estimated_delivery_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="p-6 bg-gray-50 rounded-xl">
                            <h3 class="mb-4 text-lg font-medium text-gray-900">
                                <i class="mr-2 text-indigo-600 fas fa-info-circle"></i>
                                Información Adicional
                            </h3>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- Notas -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Notas del Envío
                                    </label>
                                    <textarea name="notes" id="notes" rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('notes') border-red-500 @enderror"
                                        placeholder="Instrucciones especiales, observaciones, etc.">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.shipments.index') }}"
                                class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="mr-2 fas fa-save"></i>
                                Crear Envío
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const orderSelect = document.getElementById('order_id');
                const orderInfo = document.getElementById('order-info');
                const statusSelect = document.getElementById('status');
                const driverSelect = document.getElementById('delivery_driver_id');

                // Mostrar información de la orden seleccionada
                orderSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (this.value) {
                        document.getElementById('order-customer').textContent = selectedOption.dataset.customer;
                        document.getElementById('order-total').textContent = selectedOption.dataset.total;
                        document.getElementById('order-address').textContent = selectedOption.dataset.address;
                        orderInfo.classList.remove('hidden');
                    } else {
                        orderInfo.classList.add('hidden');
                    }
                });

                // Auto-asignar repartidor cuando el estado cambia a "asignado"
                statusSelect.addEventListener('change', function() {
                    if (this.value === 'assigned' && !driverSelect.value) {
                        // Opcional: mostrar sugerencia para seleccionar repartidor
                        Swal.fire({
                            title: 'Recordatorio',
                            text: 'No olvides asignar un repartidor para este envío',
                            icon: 'info',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                });

                // Configurar fecha estimada por defecto (mañana)
                const estimatedDate = document.getElementById('estimated_delivery_date');
                if (!estimatedDate.value) {
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    estimatedDate.value = tomorrow.toISOString().split('T')[0];
                }
            });

            function generateTrackingNumber() {
                const prefix = 'ENV';
                const timestamp = Date.now().toString().substr(-6);
                const random = Math.random().toString(36).substr(2, 4).toUpperCase();
                const trackingNumber = `${prefix}${timestamp}${random}`;

                document.getElementById('tracking_number').value = trackingNumber;
            }

            // Validación del formulario
            document.getElementById('shipment-form').addEventListener('submit', function(e) {
                const orderId = document.getElementById('order_id').value;
                const status = document.getElementById('status').value;
                const driverId = document.getElementById('delivery_driver_id').value;

                if (!orderId) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error',
                        text: 'Debe seleccionar una orden',
                        icon: 'error'
                    });
                    return;
                }

                if (status === 'assigned' && !driverId) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Confirmación',
                        text: 'El envío está marcado como "Asignado" pero no tiene repartidor. ¿Desea continuar?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                    return;
                }
            });
        </script>
    @endpush

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
