<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Envíos',
    ],
]">

    <!-- Header -->
    <div class="mb-6 text-center">
        <h1 class="mb-2 text-2xl font-bold text-gray-900 sm:text-3xl">
            Gestión de Envíos
        </h1>
        <p class="text-sm text-gray-600 sm:text-base">Administra y rastrea todos los envíos</p>
    </div>

    <!-- Stats Cards -->
    @php
    $totalShipments = $shipments->total() ?? $shipments->count();
    $deliveredCount = 0;
    $pendingCount = 0;
    $cancelledCount = 0;

    // Contar por estado de orden
    foreach ($shipments as $shipment) {
    $orderStatus = $shipment->order->status ?? 1;
    try {
    if ($orderStatus == 6) { // Entregado
    $deliveredCount++;
    } elseif ($orderStatus == 7) { // Cancelado
    $cancelledCount++;
    } else { // Pendiente o en proceso
    $pendingCount++;
    }
    } catch (Exception $e) {
    $pendingCount++;
    }
    }
    @endphp

    <!-- Validación de datos nulos -->
    @if($totalShipments !== null && $deliveredCount !== null && $cancelledCount !== null && $pendingCount !== null)
    <div class="grid grid-cols-2 gap-3 mb-6 sm:grid-cols-4 sm:gap-4">
        <div class="p-3 bg-white border border-blue-200 rounded-lg shadow-sm sm:p-4">
            <div class="flex items-center">
                <div class="p-2 text-blue-600 bg-blue-100 rounded-lg">
                    <i class="text-sm fas fa-truck sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 sm:text-sm">Total</p>
                    <p class="text-lg font-bold text-gray-900 sm:text-xl">{{ $totalShipments }}</p>
                </div>
            </div>
        </div>

        <div class="p-3 bg-white border border-green-200 rounded-lg shadow-sm sm:p-4">
            <div class="flex items-center">
                <div class="p-2 text-green-600 bg-green-100 rounded-lg">
                    <i class="text-sm fas fa-check-circle sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 sm:text-sm">Entregados</p>
                    <p class="text-lg font-bold text-gray-900 sm:text-xl">{{ $deliveredCount }}</p>
                </div>
            </div>
        </div>

        <div class="p-3 bg-white border border-orange-200 rounded-lg shadow-sm sm:p-4">
            <div class="flex items-center">
                <div class="p-2 text-orange-600 bg-orange-100 rounded-lg">
                    <i class="text-sm fas fa-clock sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 sm:text-sm">Pendientes</p>
                    <p class="text-lg font-bold text-gray-900 sm:text-xl">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>

        <div class="p-3 bg-white border border-red-200 rounded-lg shadow-sm sm:p-4">
            <div class="flex items-center">
                <div class="p-2 text-red-600 bg-red-100 rounded-lg">
                    <i class="text-sm fas fa-times sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 sm:text-sm">Cancelados</p>
                    <p class="text-lg font-bold text-gray-900 sm:text-xl">{{ $cancelledCount }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters and Search -->
    <div class="mb-6 bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.shipments.index') }}"
                class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <!-- Search -->
                <div class="flex-1">
                    <label for="search" class="block mb-1 text-sm font-medium text-gray-700">Buscar</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Número de seguimiento, orden, cliente..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Status Filter -->
                <div class="sm:w-48">
                    <label for="status" class="block mb-1 text-sm font-medium text-gray-700">Estado</label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="delivered" {{ request('status')=='delivered' ? 'selected' : '' }}>Entregado
                        </option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelado
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-md hover:bg-indigo-700">
                        <i class="mr-2 fas fa-search"></i>Buscar
                    </button>
                    <a href="{{ route('admin.shipments.index') }}"
                        class="px-4 py-2 text-sm font-medium text-white transition-colors bg-gray-600 rounded-md hover:bg-gray-700">
                        <i class="mr-2 fas fa-times"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Shipments Content -->
    <div id="shipments-content">
        @include('admin.shipments.partials.shipments-content', compact('shipments'))
    </div>

    <!-- Scripts -->
    <script>
        // Auto-submit form on select change
        document.getElementById('status').addEventListener('change', function() {
            this.closest('form').submit();
        });

        function updateContent() {
            const url = new URL(window.location);
            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('shipments-content').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Handle pagination
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('.pagination a').href;
                loadShipmentsPage(url);
            }
        });

        function loadShipmentsPage(url) {
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('shipments-content').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al cargar los envíos',
                    icon: 'error'
                });
            });
        }
    </script>
</x-admin-layout>