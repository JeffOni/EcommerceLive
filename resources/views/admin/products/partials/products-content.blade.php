<!-- Vista de tarjetas -->
<div id="cards-view" class="view-content">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @foreach ($products as $product)
            <div
                class="group relative bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-blue-100 overflow-hidden flex flex-col h-full">

                {{-- 
                    MEJORA UI #1: Badge ID modificado
                    - Se mantiene únicamente el badge con el ID del producto
                    - Se eliminaron los badges de estado "Activo/Inactivo" para limpiar la interfaz
                    - El badge ID usa colores azules consistentes con el tema del diseño
                --}}
                <div class="absolute top-3 left-3 z-10">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        #{{ $product->id }}
                    </span>
                    {{-- 
                        ESTADO DEL PRODUCTO COMENTADO - MEJORA UI #1
                        Los badges de "Activo/Inactivo" fueron removidos para simplificar la interfaz
                        y evitar redundancia visual en las tarjetas de productos.
                        Se mantiene el código comentado para futura referencia si se necesita restaurar.
                    
                    @if ($product->status)
                        <span class="block bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Activo
                        </span>
                    @else
                        <span class="block bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-times-circle mr-1"></i>Inactivo
                        </span>
                    @endif
                    --}}
                </div> {{-- 
                    MEJORA UI #3: Botones de edición hover removidos
                    - Se eliminaron los botones de edición que aparecían al hacer hover
                    - Esto evita duplicación ya que cada tarjeta tiene un botón "Editar Producto" visible
                    - Mejora la experiencia de usuario al reducir elementos redundantes
                    - Se mantiene el botón principal de edición en la parte inferior de cada tarjeta
                --}}
                {{-- CÓDIGO REMOVIDO - Botones de acción hover
                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="p-2 bg-white/90 backdrop-blur-sm rounded-lg shadow-md hover:bg-blue-500 hover:text-white transition-all duration-200">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                    </div>
                </div>
                --}}

                <!-- Imagen del producto -->
                <div class="relative w-full h-48 bg-gray-100 overflow-hidden">
                    @if ($product->image_path)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100">
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        </div>
                    @endif

                    <!-- Overlay con precio -->
                    <div class="absolute bottom-3 left-3 right-3">
                        <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2">
                            <div class="text-lg font-bold text-gray-900">
                                ${{ number_format($product->price, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido de la tarjeta -->
                <div class="p-6">
                    <!-- Nombre del producto -->
                    <h3
                        class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2">
                        {{ $product->name }}
                    </h3>

                    <!-- SKU -->
                    @if ($product->sku)
                        <div class="mb-3">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-mono bg-gray-100 text-gray-700">
                                SKU: {{ $product->sku }}
                            </span>
                        </div>
                    @endif

                    <!-- Jerarquía (Familia > Categoría > Subcategoría) -->
                    <div class="space-y-1 mb-4 text-xs">
                        <div class="flex items-center">
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-md">
                                <i class="fas fa-layer-group mr-1"></i>
                                {{ $product->subcategory->category->family->name }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="bg-teal-100 text-teal-700 px-2 py-1 rounded-md">
                                <i class="fas fa-tags mr-1"></i>
                                {{ $product->subcategory->category->name }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="bg-pink-100 text-pink-700 px-2 py-1 rounded-md">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $product->subcategory->name }}
                            </span>
                        </div>
                    </div> <!-- Información adicional -->
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        @if ($product->created_at)
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-plus text-blue-500"></i>
                                <span>{{ $product->created_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Botón de acción -->
                    <div class="mt-auto">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Producto
                        </a>
                    </div>
                </div>

                <!-- Indicador de hover -->
                <div
                    class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-blue-500 to-purple-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Vista de tabla -->
{{-- 
    MEJORA UI #2: Tabla de productos con columna ID agregada
    - Se añadió una nueva columna "ID" como primera columna de la tabla
    - Mantiene consistencia visual con los badges ID de las tarjetas
    - Facilita la identificación rápida de productos en vista tabular
    - Uso de íconos Font Awesome para mejorar la presentación visual
--}}
<div id="table-view" class="view-content hidden">
    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-600 to-purple-600">
                    <tr>
                        {{-- MEJORA UI #2: Nueva columna ID agregada --}}
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-hashtag"></i>
                                <span>ID</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-box"></i>
                                <span>Producto</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-barcode"></i>
                                <span>SKU</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Precio</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-cogs"></i>
                                <span>Acciones</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr class="hover:bg-blue-50 transition-colors duration-200">
                            {{-- MEJORA UI #2: Celda ID con badge azul consistente --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    #{{ $product->id }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden mr-3 bg-gray-100 flex-shrink-0">
                                        @if ($product->image_path)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100">
                                                <i class="fas fa-image text-gray-400 text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $product->subcategory->category->family->name }} >
                                            {{ $product->subcategory->category->name }} >
                                            {{ $product->subcategory->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($product->sku)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-mono bg-gray-100 text-gray-700">
                                        {{ $product->sku }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Sin SKU</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">
                                    ${{ number_format($product->price, 2) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <i class="fas fa-edit mr-2"></i>
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 
    MEJORA UI #4: Paginación simplificada
    - Se eliminó el texto duplicado "Mostrando X a Y de Z resultados" 
    - Anteriormente aparecía tanto manualmente (izquierda) como en el componente links() (derecha)
    - Ahora solo se muestra la paginación una vez usando el componente estándar de Laravel
    - Mejora la limpieza visual y evita información redundante
--}}
<!-- Paginación -->
@if ($products->hasPages())
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif
