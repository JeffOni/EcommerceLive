<!-- Vista responsiva: Tabla en desktop, cards en móvil -->
<div class="hidden lg:block">
    <!-- Tabla para escritorio -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Pedido
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Cliente
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Total
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Método de Pago
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Fecha
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="transition-colors duration-200 hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">#{{ $order->id }}</div>
                        @if ($order->pdf_path)
                        <div class="text-xs text-green-600">
                            <i class="mr-1 fas fa-file-pdf"></i>PDF disponible
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $order->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">${{ number_format($order->total, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @switch($order->payment_method)
                            @case(0)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Otro
                            </span>
                            @break

                            @case(1)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="mr-1 fas fa-credit-card"></i>Tarjeta

                            </span>
                            @break

                            @case(2)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="mr-1 fas fa-university"></i>Transferencia
                            </span>
                            @break

                            @default

                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="mr-1 fas fa-money-bill"></i>Efectivo
                            </span>
                            @endswitch
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                        <span class="status-badge {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        <div>{{ $order->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Cambiar Estado -->
                            <div class="relative inline-block text-left">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    onclick="toggleStatusDropdown({{ $order->id }})">
                                    <i class="mr-1 fas fa-edit"></i>
                                    Estado
                                    <i class="ml-1 fas fa-chevron-down"></i>
                                </button>
                                <div id="status-dropdown-{{ $order->id }}"
                                    class="absolute right-0 z-10 hidden w-48 mt-2 bg-white border border-gray-200 rounded-md shadow-lg">
                                    <div class="py-1">
                                        @if ($order->status == 1)
                                        @if ($order->payment_method == 2)
                                        {{-- Efectivo: de Pendiente directo a Asignar Repartidor --}}
                                        <button onclick="openAssignDriverModal({{ $order->id }})"
                                            class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                            <i class="mr-2 text-yellow-500 fas fa-user-plus"></i>Asignar Repartidor
                                        </button>
                                        @else
                                        {{-- Transferencia/QR: de Pendiente a Verificado --}}
                                        <button onclick="updateOrderStatus({{ $order->id }}, 2)"
                                            class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                            <i class="mr-2 text-blue-500 fas fa-check"></i>Marcar como Verificado
                                        </button>
                                        @endif
                                        @endif
                                        @if ($order->status == 2)
                                        {{-- Pago Verificado: Asignar Repartidor --}}
                                        <button onclick="openAssignDriverModal({{ $order->id }})"
                                            class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                            <i class="mr-2 text-yellow-500 fas fa-user-plus"></i>Asignar Repartidor
                                        </button>
                                        @endif
                                        @if ($order->status == 3)
                                        {{-- Preparando: Solo Asignar Repartidor si no tiene envío --}}
                                        @if (!$order->hasShipment())
                                        <button onclick="openAssignDriverModal({{ $order->id }})"
                                            class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                            <i class="mr-2 text-yellow-500 fas fa-user-plus"></i>Asignar Repartidor
                                        </button>
                                        @else
                                        <button onclick="redirectToShipment({{ $order->id }})"
                                            class="block w-full px-4 py-2 text-sm text-left text-blue-600 hover:bg-blue-50">
                                            <i class="mr-2 text-blue-500 fas fa-truck"></i>Ver Envío
                                        </button>
                                        @endif
                                        @endif
                                        @if (in_array($order->status, [4]))
                                        {{-- Estado Asignado: Solo En Camino si tiene repartidor --}}
                                        @if ($order->hasShipment() && $order->shipment->delivery_driver_id)
                                        <button onclick="updateOrderStatus({{ $order->id }}, 5)"
                                            class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                            <i class="mr-2 text-orange-500 fas fa-truck"></i>En Camino
                                        </button>
                                        @else
                                        <button onclick="openAssignDriverModal({{ $order->id }})"
                                            class="block w-full px-4 py-2 text-sm text-left text-yellow-600 hover:bg-yellow-50">
                                            <i class="mr-2 text-yellow-500 fas fa-user-plus"></i>Asignar Repartidor
                                        </button>
                                        @endif
                                        @endif
                                        @if ($order->status == 5)
                                        <button onclick="updateOrderStatus({{ $order->id }}, 6)"
                                            class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                            <i class="mr-2 text-green-500 fas fa-check-circle"></i>Entregado
                                        </button>
                                        @endif
                                        @if ($order->status != 7 && $order->status != 6)
                                        <hr class="my-1">
                                        <button onclick="updateOrderStatus({{ $order->id }}, 7)"
                                            class="block w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-red-50">
                                            <i class="mr-2 fas fa-times"></i>Cancelar
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Descargar PDF -->
                            @if ($order->pdf_path)
                            <button onclick="downloadPDF({{ $order->id }})"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="mr-1 fas fa-download"></i>
                                PDF
                            </button>
                            @endif

                            <!-- Ver Detalles -->
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="mr-1 fas fa-eye"></i>
                                Ver
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="mb-4 text-6xl text-gray-300 fas fa-shopping-cart"></i>
                            <h3 class="mb-2 text-lg font-medium text-gray-900">No hay pedidos</h3>
                            <p class="text-gray-500">No se encontraron pedidos con los filtros aplicados.</p>
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
        @forelse($orders as $order)
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-medium text-gray-900">#{{ $order->id }}</div>
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
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Cliente:</span>
                        <span class="font-medium">{{ $order->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total:</span>
                        <span class="font-medium">${{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Fecha:</span>
                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-200">
                    <div class="flex space-x-2">
                        @if ($order->pdf_path)
                        <button onclick="downloadPDF({{ $order->id }})"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 border border-transparent rounded hover:bg-red-700">
                            <i class="mr-1 fas fa-download"></i>PDF
                        </button>
                        @endif
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                            <i class="mr-1 fas fa-eye"></i>Ver
                        </a>
                    </div>

                    <button type="button" onclick="toggleStatusDropdown({{ $order->id }})"
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                        <i class="mr-1 fas fa-edit"></i>Estado
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="py-12 text-center">
            <i class="mb-4 text-6xl text-gray-300 fas fa-shopping-cart"></i>
            <h3 class="mb-2 text-lg font-medium text-gray-900">No hay pedidos</h3>
            <p class="text-gray-500">No se encontraron pedidos con los filtros aplicados.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Paginación -->
@if ($orders->hasPages())
<div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
    {{ $orders->links() }}
</div>
@endif

<script>
    function toggleStatusDropdown(orderId) {
        const dropdown = document.getElementById(`status-dropdown-${orderId}`);
        const allDropdowns = document.querySelectorAll('[id^="status-dropdown-"]');
        // Cerrar todos los dropdowns excepto el actual
        allDropdowns.forEach(d => {
            if (d.id !== `status-dropdown-${orderId}`) {
                d.classList.add('hidden');
            }
        });
        // Toggle del dropdown actual
        dropdown.classList.toggle('hidden');
    }

    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[id^="status-dropdown-"]') && !event.target.closest('button[onclick*="toggleStatusDropdown"]')) {
            document.querySelectorAll('[id^="status-dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });

        // Función para abrir modal de asignación de repartidor
    function openAssignDriverModal(orderId) {
        // Verificar si la orden ya tiene un envío
        fetch(`/admin/orders/${orderId}/check-shipment`)
            .then(response => response.json())
            .then(data => {
                if (data.hasShipment) {
                    // Si ya tiene envío, redirigir al panel de envíos
                    Swal.fire({
                        icon: 'info',
                        title: 'Envío ya creado',
                        text: 'Esta orden ya tiene un envío asignado. Te redirigiremos al panel de envíos.',
                        confirmButtonText: 'Ver envío'
                    }).then(() => {
                        window.location.href = `/admin/shipments?search=${orderId}`;
                    });
                } else {
                    // Si no tiene envío, mostrar modal para asignar
                    showAssignDriverModal(orderId);
                }
            })
            .catch(() => {
                // Si hay error, mostrar modal de asignación
                showAssignDriverModal(orderId);
            });
    }

    function showAssignDriverModal(orderId) {
        // Obtener lista de repartidores disponibles
        fetch('/admin/delivery-drivers/active')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(drivers => {
                if (drivers.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No hay repartidores disponibles',
                        text: 'Debes crear al menos un repartidor activo antes de asignar envíos.',
                        confirmButtonText: 'Gestionar repartidores'
                    }).then(() => {
                        window.location.href = '/admin/delivery-drivers';
                    });
                    return;
                }

                const driversOptions = drivers.map(driver => {
                    const photoUrl = driver.profile_photo_url || `https://www.gravatar.com/avatar/${driver.email}?d=mp&s=40`;
                    return `<option value="${driver.id}" data-photo="${photoUrl}">${driver.name} - ${driver.phone} (${driver.vehicle_type})</option>`;
                }).join('');

                Swal.fire({
                    title: 'Asignar Repartidor',
                    html: `
                        <div class="text-left">
                            <p class="mb-4 text-sm text-gray-600">Selecciona un repartidor para la orden #${orderId}</p>
                            <div class="mb-4">
                                <select id="driverSelect" class="w-full p-3 border border-gray-300 rounded-md">
                                    <option value="">Seleccionar repartidor...</option>
                                    ${driversOptions}
                                </select>
                            </div>
                            <div id="driverInfo" class="hidden p-3 rounded-md bg-gray-50">
                                <div class="flex items-center space-x-3">
                                    <img id="driverPhoto" src="" alt="Foto" class="object-cover w-12 h-12 rounded-full">
                                    <div>
                                        <p id="driverName" class="font-medium"></p>
                                        <p id="driverDetails" class="text-sm text-gray-600"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Asignar',
                    cancelButtonText: 'Cancelar',
                    didOpen: () => {
                        const select = document.getElementById('driverSelect');
                        const driverInfo = document.getElementById('driverInfo');
                        const driverPhoto = document.getElementById('driverPhoto');
                        const driverName = document.getElementById('driverName');
                        const driverDetails = document.getElementById('driverDetails');

                        select.addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            if (selectedOption.value) {
                                const driver = drivers.find(d => d.id == selectedOption.value);
                                if (driver) {
                                    driverPhoto.src = driver.profile_photo_url || `https://www.gravatar.com/avatar/${btoa(driver.email)}?d=mp&s=80`;
                                    driverName.textContent = driver.name;
                                    driverDetails.textContent = `${driver.phone} - ${driver.vehicle_type}`;
                                    driverInfo.classList.remove('hidden');
                                }
                            } else {
                                driverInfo.classList.add('hidden');
                            }
                        });
                    },
                    preConfirm: () => {
                        const driverId = document.getElementById('driverSelect').value;
                        if (!driverId) {
                            Swal.showValidationMessage('Por favor selecciona un repartidor');
                            return false;
                        }
                        return driverId;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        assignDriverToOrder(orderId, result.value);
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message.includes('404')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de ruta',
                        text: 'La ruta para obtener repartidores no está disponible. Contacta al administrador del sistema.'
                    });
                } else if (error.message.includes('401') || error.message.includes('403')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesión expirada',
                        text: 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.',
                        confirmButtonText: 'Ir al login'
                    }).then(() => {
                        window.location.href = '/login';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la lista de repartidores. Inténtalo nuevamente.'
                    });
                }
            });
    }

    // Función para asignar repartidor a la orden
    function assignDriverToOrder(orderId, driverId) {
        fetch(`/admin/orders/${orderId}/assign-driver`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                delivery_driver_id: driverId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Repartidor asignado!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Recargar para mostrar cambios
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo asignar el repartidor'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al asignar el repartidor'
            });
        });
    }

    // Función para redirigir al panel de envíos
    function redirectToShipment(orderId) {
        window.location.href = `/admin/shipments?search=${orderId}`;
    }
</script>