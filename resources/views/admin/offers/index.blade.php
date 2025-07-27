<x-admin-layout>
    <x-slot name="title">Gestión de Ofertas</x-slot>

    <div class="space-y-6">
        {{-- Header con estadísticas --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">🎯 Gestión de Ofertas</h1>
                    <p class="text-gray-600">Administra las ofertas y descuentos de tus productos</p>
                </div>
                <div class="flex gap-3">
                    <form action="{{ route('admin.offers.clean-expired') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors"
                            onclick="return confirm('¿Estás seguro de limpiar todas las ofertas vencidas?')">
                            🧹 Limpiar Vencidas
                        </button>
                    </form>
                    <a href="{{ route('admin.offers.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        ➕ Nueva Oferta
                    </a>
                </div>
            </div>

            {{-- Estadísticas --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-blue-500 rounded-full p-2 mr-3">
                            <i class="fas fa-tags text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Total Ofertas</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['total_offers'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-green-500 rounded-full p-2 mr-3">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-600 font-medium">Ofertas Activas</p>
                            <p class="text-2xl font-bold text-green-900">{{ $stats['active_offers'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-red-500 rounded-full p-2 mr-3">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-red-600 font-medium">Ofertas Vencidas</p>
                            <p class="text-2xl font-bold text-red-900">{{ $stats['expired_offers'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-purple-500 rounded-full p-2 mr-3">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Ahorros Totales</p>
                            <p class="text-2xl font-bold text-purple-900">${{ number_format($stats['total_savings'], 2)
                                }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de ofertas --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Productos con Ofertas</h2>
            </div>

            @if($products->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay ofertas activas</h3>
                <p class="text-gray-600 mb-6">Comienza creando tu primera oferta para productos</p>
                <a href="{{ route('admin.offers.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    ➕ Crear Primera Oferta
                </a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Oferta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precios
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vigencia
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                        class="w-12 h-12 rounded-lg object-cover mr-4"
                                        onerror="this.src='https://via.placeholder.com/48x48/e5e7eb/9ca3af?text=IMG'">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($product->name, 30)
                                            }}</div>
                                        <div class="text-sm text-gray-500">{{
                                            $product->subcategory->category->family->name ?? 'Sin categoría' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $product->offer_name ?? 'Sin nombre'
                                    }}</div>
                                <div class="text-sm text-red-600 font-semibold">{{ $product->offer_percentage }}% OFF
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}
                                </div>
                                <div class="text-sm font-bold text-red-600">${{ number_format($product->offer_price, 2)
                                    }}</div>
                                <div class="text-xs text-green-600">Ahorra: ${{ number_format($product->price -
                                    $product->offer_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div>📅 {{ \Carbon\Carbon::parse($product->offer_starts_at)->format('d/m/Y') }}</div>
                                <div>🔚 {{ \Carbon\Carbon::parse($product->offer_ends_at)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">
                                    @if(\Carbon\Carbon::parse($product->offer_ends_at)->isPast())
                                    Vencida hace {{ \Carbon\Carbon::parse($product->offer_ends_at)->diffForHumans() }}
                                    @else
                                    Vence {{ \Carbon\Carbon::parse($product->offer_ends_at)->diffForHumans() }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->is_on_valid_offer)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ Activa
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    ❌ Vencida
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.offers.edit', $product) }}"
                                        class="text-blue-600 hover:text-blue-900" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.offers.destroy', $product) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar"
                                            onclick="return confirm('¿Estás seguro de eliminar esta oferta?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->links() }}
            </div>
            @endif
            @endif
        </div>
    </div>
</x-admin-layout>