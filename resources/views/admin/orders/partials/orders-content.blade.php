<!-- Vista responsiva: Tabla en desktop, cards en móvil -->
<div class="hidden lg:block">
    <!-- Tabla para escritorio -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pedido
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cliente
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Método de Pago
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">#{{ $order->id }}</div>
                            @if ($order->pdf_path)
                                <div class="text-xs text-green-600">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF disponible
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
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-university mr-1"></i>Transferencia
                                        </span>
                                    @break

                                    @case(1)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-credit-card mr-1"></i>Tarjeta
                                        </span>
                                    @break

                                    @case(2)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-money-bill mr-1"></i>Efectivo
                                        </span>
                                    @break

                                    @default
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Otro
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $order->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Cambiar Estado -->
                                <div class="relative inline-block text-left">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                        onclick="toggleStatusDropdown({{ $order->id }})">
                                        <i class="fas fa-edit mr-1"></i>
                                        Estado
                                        <i class="ml-1 fas fa-chevron-down"></i>
                                    </button>
                                    <div id="status-dropdown-{{ $order->id }}"
                                        class="absolute right-0 z-10 hidden w-48 mt-2 bg-white border border-gray-200 rounded-md shadow-lg">
                                        <div class="py-1">
                                            @if ($order->status == 1)
                                                <button onclick="updateOrderStatus({{ $order->id }}, 2)"
                                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-check mr-2 text-blue-500"></i>Marcar como
                                                    Verificado
                                                </button>
                                            @endif
                                            @if ($order->status == 2)
                                                <button onclick="updateOrderStatus({{ $order->id }}, 3)"
                                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-box mr-2 text-purple-500"></i>Preparando
                                                </button>
                                            @endif
                                            @if (in_array($order->status, [3, 4]))
                                                <button onclick="updateOrderStatus({{ $order->id }}, 5)"
                                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-truck mr-2 text-orange-500"></i>En Camino
                                                </button>
                                            @endif
                                            @if ($order->status == 5)
                                                <button onclick="updateOrderStatus({{ $order->id }}, 6)"
                                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-check-circle mr-2 text-green-500"></i>Entregado
                                                </button>
                                            @endif
                                            @if ($order->status != 7 && $order->status != 6)
                                                <hr class="my-1">
                                                <button onclick="updateOrderStatus({{ $order->id }}, 7)"
                                                    class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                                    <i class="fas fa-times mr-2"></i>Cancelar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Descargar PDF -->
                                @if ($order->pdf_path)
                                    <button onclick="downloadPDF({{ $order->id }})"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-download mr-1"></i>
                                        PDF
                                    </button>
                                @endif

                                <!-- Ver Detalles -->
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-eye mr-1"></i>
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
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
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

                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                            <div class="flex space-x-2">
                                @if ($order->pdf_path)
                                    <button onclick="downloadPDF({{ $order->id }})"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 border border-transparent rounded hover:bg-red-700">
                                        <i class="fas fa-download mr-1"></i>PDF
                                    </button>
                                @endif
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </a>
                            </div>

                            <button type="button" onclick="toggleStatusDropdown({{ $order->id }})"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                                <i class="fas fa-edit mr-1"></i>Estado
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="mb-4 text-6xl text-gray-300 fas fa-shopping-cart"></i>
                    <h3 class="mb-2 text-lg font-medium text-gray-900">No hay pedidos</h3>
                    <p class="text-gray-500">No se encontraron pedidos con los filtros aplicados.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Paginación -->
    @if ($orders->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    @endif

    <script>
        function toggleStatusDropdown(orderId) {
            const dropdown = document.getElementById(`status-dropdown-${orderId}`);
            const allDropdowns = document.querySelectorAll('[id^="status-dropdown-"]');

            // Cerrar todos los otros dropdowns
            allDropdowns.forEach(d => {
                if (d.id !== `status-dropdown-${orderId}`) {
                    d.classList.add('hidden');
                }
            });

            // Toggle el dropdown actual
            dropdown.classList.toggle('hidden');
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('[onclick*="toggleStatusDropdown"]') && !e.target.closest(
                    '[id^="status-dropdown-"]')) {
                const allDropdowns = document.querySelectorAll('[id^="status-dropdown-"]');
                allDropdowns.forEach(d => d.classList.add('hidden'));
            }
        });
    </script>
