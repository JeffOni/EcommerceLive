<!-- Vista de Tarjetas -->
<div id="cards-view" class="view-content p-3 sm:p-6">
    @if($orders->isEmpty())
    <div class="text-center py-12">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 mb-4">
            <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay pedidos</h3>
        <p class="text-gray-500">No se encontraron pedidos que coincidan con los filtros seleccionados.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($orders as $order)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <!-- Header de la tarjeta -->
            <div class="bg-gradient-to-r from-primary-600 to-secondary-600 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-receipt text-white text-sm"></i>
                        <span class="text-white font-semibold text-sm">Pedido #{{ $order->id }}</span>
                    </div>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', match($order->status) {
                                1 => 'pendiente',
                                2 => 'pagado',
                                3 => 'preparando',
                                4 => 'asignado',
                                5 => 'enviado',
                                6 => 'entregado',
                                7 => 'cancelado',
                                default => 'pendiente'
                            })) }} text-xs">
                        {{ match($order->status) {
                        1 => 'Pendiente de Pago',
                        2 => 'Pago Confirmado',
                        3 => 'Preparando Pedido',
                        4 => 'Asignado a Repartidor',
                        5 => 'En Camino',
                        6 => 'Entregado',
                        7 => 'Cancelado',
                        default => 'Pendiente de Pago'
                        } }}
                    </span>
                </div>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="p-4 space-y-3">
                <!-- Información del cliente -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xs"></i>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $order->user->email }}</p>
                    </div>
                </div>

                <!-- Información del pedido -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Total:</span>
                        <span class="font-semibold text-green-600">${{ number_format($order->total, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Pago:</span>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ match($order->payment_method) {
                            0 => 'Transferencia',
                            1 => 'QR con DeUna',
                            2 => 'Efectivo',
                            default => 'No especificado'
                            } }}
                        </span>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex flex-wrap gap-1 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.orders.show', $order) }}"
                        class="flex-1 min-w-0 text-center px-2 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-1"></i><span class="hidden sm:inline">Ver</span>
                    </a>

                    @if($order->status == 1 && in_array($order->payment_method, [0, 1]))
                    <button onclick="updateOrderStatus({{ $order->id }}, 2)"
                        class="px-2 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-1"></i><span class="hidden sm:inline">Confirmar Pago</span>
                    </button>
                    @elseif($order->status == 1 && $order->payment_method == 2)
                    <button onclick="openAssignDriverModal({{ $order->id }})"
                        class="px-2 py-1.5 bg-yellow-600 text-white text-xs font-medium rounded hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-user-plus mr-1"></i><span class="hidden sm:inline">Asignar Repartidor</span>
                    </button>
                    @elseif($order->status == 2)
                    <button onclick="updateOrderStatus({{ $order->id }}, 3)"
                        class="px-2 py-1.5 bg-purple-600 text-white text-xs font-medium rounded hover:bg-purple-700 transition-colors">
                        <i class="fas fa-box mr-1"></i><span class="hidden sm:inline">Preparar</span>
                    </button>
                    @elseif($order->status == 3)
                    <button onclick="openAssignDriverModal({{ $order->id }})"
                        class="px-2 py-1.5 bg-yellow-600 text-white text-xs font-medium rounded hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-user-plus mr-1"></i><span class="hidden sm:inline">Asignar Repartidor</span>
                    </button>
                    @elseif($order->status == 4)
                    <button onclick="updateOrderStatus({{ $order->id }}, 5)"
                        class="px-2 py-1.5 bg-orange-600 text-white text-xs font-medium rounded hover:bg-orange-700 transition-colors">
                        <i class="fas fa-truck mr-1"></i><span class="hidden sm:inline">En Camino</span>
                    </button>
                    @endif

                    @if($order->status == 5)
                    <button onclick="updateOrderStatus({{ $order->id }}, 6)"
                        class="px-2 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                        <i class="fas fa-check-circle mr-1"></i><span class="hidden sm:inline">Entregar</span>
                    </button>
                    @endif

                    @if($order->status < 6) <button onclick="updateOrderStatus({{ $order->id }}, 7)"
                        class="px-2 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-1"></i><span class="hidden sm:inline">Cancelar</span>
                        </button>
                        @endif

                        @if($order->pdf_path)
                        <button onclick="downloadPDF({{ $order->id }})"
                            class="px-2 py-1.5 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700 transition-colors">
                            <i class="fas fa-download"></i>
                        </button>
                        @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Vista de Tabla -->
<div id="table-view" class="view-content hidden p-3 sm:p-6">
    @if($orders->isEmpty())
    <div class="text-center py-12">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 mb-4">
            <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay pedidos</h3>
        <p class="text-gray-500">No se encontraron pedidos que coincidan con los filtros seleccionados.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Pedido
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Cliente
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Estado
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Total
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Pago
                    </th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-4 whitespace-nowrap sm:px-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                                    <i class="fas fa-receipt text-primary-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ match($order->payment_method) {
                                    0 => 'Transferencia',
                                    1 => 'QR con DeUna',
                                    2 => 'Efectivo',
                                    default => 'No especificado'
                                    } }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap sm:px-6">
                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $order->user->email }}</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap sm:px-6">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', match($order->status) {
                                    1 => 'pendiente',
                                    2 => 'pagado',
                                    3 => 'preparando',
                                    4 => 'asignado',
                                    5 => 'enviado',
                                    6 => 'entregado',
                                    7 => 'cancelado',
                                    default => 'pendiente'
                                })) }}">
                            {{ match($order->status) {
                            1 => 'Pendiente de Pago',
                            2 => 'Pago Confirmado',
                            3 => 'Preparando Pedido',
                            4 => 'Asignado a Repartidor',
                            5 => 'En Camino',
                            6 => 'Entregado',
                            7 => 'Cancelado',
                            default => 'Pendiente de Pago'
                            } }}
                        </span>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm font-semibold text-green-600 sm:px-6">
                        ${{ number_format($order->total, 2) }}
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 sm:px-6">
                        <div class="font-medium">
                            {{ match($order->payment_method) {
                            0 => 'Transferencia',
                            1 => 'QR con DeUna',
                            2 => 'Efectivo',
                            default => 'No especificado'
                            } }}
                        </div>
                        <div class="text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        <div class="text-xs">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium space-x-1 sm:px-6">
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <i class="fas fa-eye mr-1"></i>
                            <span class="hidden sm:inline">Ver</span>
                        </a>

                        @if($order->status == 1 && in_array($order->payment_method, [0, 1]))
                        <button onclick="updateOrderStatus({{ $order->id }}, 2)"
                            class="inline-flex items-center px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                            <i class="fas fa-check mr-1"></i>
                            <span class="hidden lg:inline">Verificar</span>
                        </button>
                        @elseif($order->status == 1 && $order->payment_method == 2)
                        <button onclick="openAssignDriverModal({{ $order->id }})"
                            class="inline-flex items-center px-2 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">
                            <i class="fas fa-user-plus mr-1"></i>
                            <span class="hidden lg:inline">Asignar</span>
                        </button>
                        @elseif($order->status == 2)
                        <button onclick="updateOrderStatus({{ $order->id }}, 3)"
                            class="inline-flex items-center px-2 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700">
                            <i class="fas fa-box mr-1"></i>
                            <span class="hidden lg:inline">Preparar</span>
                        </button>
                        @elseif($order->status == 3)
                        <button onclick="openAssignDriverModal({{ $order->id }})"
                            class="inline-flex items-center px-2 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">
                            <i class="fas fa-user-plus mr-1"></i>
                            <span class="hidden lg:inline">Asignar</span>
                        </button>
                        @elseif($order->status == 4)
                        <button onclick="updateOrderStatus({{ $order->id }}, 5)"
                            class="inline-flex items-center px-2 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700">
                            <i class="fas fa-truck mr-1"></i>
                            <span class="hidden lg:inline">En Camino</span>
                        </button>
                        @endif

                        @if($order->status == 5)
                        <button onclick="updateOrderStatus({{ $order->id }}, 6)"
                            class="inline-flex items-center px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span class="hidden lg:inline">Entregar</span>
                        </button>
                        @endif

                        @if($order->status < 6) <button onclick="updateOrderStatus({{ $order->id }}, 7)"
                            class="inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                            <i class="fas fa-times mr-1"></i>
                            <span class="hidden lg:inline">Cancelar</span>
                            </button>
                            @endif

                            @if($order->pdf_path)
                            <button onclick="downloadPDF({{ $order->id }})"
                                class="inline-flex items-center px-2 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                                <i class="fas fa-download"></i>
                            </button>
                            @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Paginación -->
@if($orders->hasPages())
<div class="px-3 py-4 sm:px-6">
    <div class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($orders->onFirstPage())
            <span
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                Anterior
            </span>
            @else
            <a href="{{ $orders->previousPageUrl() }}"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Anterior
            </a>
            @endif

            @if ($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}"
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Siguiente
            </a>
            @else
            <span
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                Siguiente
            </span>
            @endif
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ $orders->firstItem() ?? 0 }}</span>
                    a
                    <span class="font-medium">{{ $orders->lastItem() ?? 0 }}</span>
                    de
                    <span class="font-medium">{{ $orders->total() }}</span>
                    resultados
                </p>
            </div>
            <div>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal para Asignar Repartidor -->
<div id="assign-driver-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Asignar Repartidor</h3>
                <button onclick="closeAssignDriverModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-3">Orden: <span id="modal-order-id" class="font-semibold"></span></p>
                <label for="driver-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Repartidor:
                </label>
                <select id="driver-select"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Cargando repartidores...</option>
                </select>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Se muestra la cantidad de envíos activos de cada repartidor
                </p>
            </div>

            <div class="flex justify-end space-x-3">
                <button onclick="closeAssignDriverModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancelar
                </button>
                <button onclick="confirmAssignDriver()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Asignar
                </button>
            </div>
        </div>
    </div>
</div>