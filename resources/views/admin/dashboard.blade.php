<x-admin-layout :breadcrumbs="[['name' => 'Dashboard']]">

    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-blue-200/30 to-purple-300/20 blur-3xl"></div>
            <div class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-200/30 to-blue-300/20 blur-3xl"></div>
        </div>

        <div class="relative px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard Administrativo</h1>
                        <p class="mt-2 text-gray-600">Resumen general de tu tienda online</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg">
                            <img class="object-cover w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}" />
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de estadísticas principales -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Total de Órdenes -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Órdenes</p>
                            <p class="text-2xl font-bold text-gray-900" id="totalOrders">{{ \App\Models\Order::count() }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-sm text-gray-500">Hoy: {{ \App\Models\Order::whereDate('created_at', today())->count() }}</span>
                            </div>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="text-xl text-blue-600 fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>

                <!-- Ingresos Totales -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Ingresos Totales</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format(\App\Models\Order::where('status', '>=', 2)->sum('total'), 2) }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-sm text-gray-500">Hoy: ${{ number_format(\App\Models\Order::where('status', '>=', 2)->whereDate('created_at', today())->sum('total'), 2) }}</span>
                            </div>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="text-xl text-green-600 fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <!-- Pagos Pendientes -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pagos Pendientes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Payment::where('status', 'pending')->whereNotNull('receipt_path')->count() }}</p>
                            <div class="flex items-center mt-1">
                                <a href="{{ route('admin.payments.verification') }}" class="text-sm text-blue-600 hover:text-blue-500">
                                    Ver verificaciones
                                </a>
                            </div>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="text-xl text-yellow-600 fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Clientes -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Clientes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::whereHas('orders')->count() }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-sm text-gray-500">Nuevos hoy: {{ \App\Models\User::whereDate('created_at', today())->count() }}</span>
                            </div>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="text-xl text-purple-600 fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Órdenes recientes y acciones rápidas -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Órdenes recientes -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Órdenes Recientes</h3>
                        <a href="{{ route('admin.orders.index') }}" 
                           class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            Ver todas
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @php
                            $recentOrders = \App\Models\Order::with(['user', 'payment'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
                        @endphp
                        
                        @forelse($recentOrders as $order)
                            <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                                        <span class="text-sm font-bold text-gray-900">${{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-gray-500">{{ $order->user->name ?? 'Usuario' }}</span>
                                        @php
                                            $statusColors = [
                                                1 => 'bg-yellow-100 text-yellow-800',
                                                2 => 'bg-blue-100 text-blue-800',
                                                3 => 'bg-purple-100 text-purple-800',
                                                4 => 'bg-green-100 text-green-800',
                                                5 => 'bg-red-100 text-red-800',
                                            ];
                                            $statusLabels = [
                                                1 => 'Pendiente',
                                                2 => 'Verificado',
                                                3 => 'Enviado',
                                                4 => 'Entregado',
                                                5 => 'Cancelado',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$order->status] ?? 'Desconocido' }}
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">No hay órdenes recientes</p>
                        @endforelse
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <h3 class="mb-6 text-lg font-semibold text-gray-900">Acciones Rápidas</h3>
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <a href="{{ route('admin.payments.verification') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <i class="text-yellow-600 fas fa-credit-card"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Verificar Pagos</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\Payment::where('status', 'pending')->count() }} pendientes</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.orders.verified') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="text-blue-600 fas fa-box"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Órdenes Verificadas</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\Order::where('status', 2)->count() }} listas</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.orders.index') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="text-green-600 fas fa-list"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Todas las Órdenes</p>
                                <p class="text-xs text-gray-500">Gestión completa</p>
                            </div>
                        </a>

                        <a href="#" 
                           class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="text-purple-600 fas fa-chart-bar"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Reportes</p>
                                <p class="text-xs text-gray-500">Análisis de ventas</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pagos pendientes destacados -->
            @php
                $pendingPayments = \App\Models\Payment::with('user')
                    ->where('status', 'pending')
                    ->whereNotNull('receipt_path')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            @endphp

            @if(count($pendingPayments) > 0)
                <div class="mt-8">
                    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-yellow-600 fas fa-exclamation-triangle"></i>
                                Pagos Requieren Verificación
                            </h3>
                            <a href="{{ route('admin.payments.verification') }}" 
                               class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                Ver todos ({{ \App\Models\Payment::where('status', 'pending')->whereNotNull('receipt_path')->count() }})
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            @foreach($pendingPayments as $payment)
                                <div class="p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $payment->user->name ?? 'Usuario' }}</span>
                                        <span class="text-sm font-bold text-gray-900">${{ number_format($payment->amount, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs text-gray-600">{{ ucfirst($payment->payment_method) }}</span>
                                        <span class="text-xs text-gray-500">{{ $payment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-200 rounded-full">
                                            Pendiente
                                        </span>
                                        <a href="{{ route('admin.payments.verification') }}" 
                                           class="text-xs font-medium text-blue-600 hover:text-blue-500">
                                            Verificar →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-admin-layout>
