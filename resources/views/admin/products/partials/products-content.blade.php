<!-- Vista de tarjetas -->
<div id="cards-view" class="view-content">
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($products as $product)
        <div
            class="relative flex flex-col h-full overflow-hidden transition-all duration-500 transform border border-blue-100 shadow-lg group bg-gradient-to-br from-white to-blue-50 rounded-2xl hover:shadow-2xl hover:-translate-y-2">

            {{--
            MEJORA UI #1: Badge ID modificado
            - Se mantiene únicamente el badge con el ID del producto
            - Se eliminaron los badges de estado "Activo/Inactivo" para limpiar la interfaz
            - El badge ID usa colores azules consistentes con el tema del diseño
            --}}
            <div class="absolute z-10 top-3 left-3">
                <span
                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                    #{{ $product->id }}
                </span>
            </div>

            {{-- Toggle is_active --}}
            <div class="absolute z-10 top-3 right-3">
                <button onclick="toggleProductStatus({{ $product->id }}, {{ $product->is_active ? 'false' : 'true' }})"
                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full transition-all duration-200 hover:scale-105
                               {{ $product->is_active ? 'text-green-800 bg-green-100 hover:bg-green-200' : 'text-red-800 bg-red-100 hover:bg-red-200' }}">
                    <i class="mr-1 fas {{ $product->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                    {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                </button>
            </div> {{--
            MEJORA UI #3: Botones de edición hover removidos
            - Se eliminaron los botones de edición que aparecían al hacer hover
            - Esto evita duplicación ya que cada tarjeta tiene un botón "Editar Producto" visible
            - Mejora la experiencia de usuario al reducir elementos redundantes
            - Se mantiene el botón principal de edición en la parte inferior de cada tarjeta
            --}}
            {{-- CÓDIGO REMOVIDO - Botones de acción hover
            <div class="absolute transition-opacity duration-300 opacity-0 top-3 right-3 group-hover:opacity-100">
                <div class="flex space-x-2">
                    <a href="{{ route('admin.products.edit', $product) }}"
                        class="p-2 transition-all duration-200 rounded-lg shadow-md glass-effect hover:bg-blue-500 hover:text-white">
                        <i class="text-sm fas fa-edit"></i>
                    </a>
                </div>
            </div>
            --}}

            <!-- Imagen del producto -->
            <div class="relative w-full h-48 overflow-hidden bg-gray-100">
                @if ($product->image_path)
                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                    class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                @else
                <div
                    class="flex items-center justify-center w-full h-full bg-gradient-to-br from-blue-100 to-purple-100">
                    <i class="text-4xl text-gray-400 fas fa-image"></i>
                </div>
                @endif

                <!-- Overlay con precio -->
                <div class="absolute bottom-3 left-3 right-3">
                    <div class="px-3 py-2 rounded-lg glass-effect">
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
                    class="mb-2 text-lg font-bold text-gray-900 transition-colors duration-300 group-hover:text-blue-600 line-clamp-2">
                    {{ $product->name }}
                </h3>

                <!-- SKU -->
                @if ($product->sku)
                <div class="mb-3">
                    <span
                        class="inline-flex items-center px-2 py-1 font-mono text-xs text-gray-700 bg-gray-100 rounded-md">
                        SKU: {{ $product->sku }}
                    </span>
                </div>
                @endif

                <!-- Jerarquía (Familia > Categoría > Subcategoría) -->
                <div class="mb-4 space-y-1 text-xs">
                    <div class="flex items-center">
                        <span class="px-2 py-1 text-indigo-700 bg-indigo-100 rounded-md">
                            <i class="mr-1 fas fa-layer-group"></i>
                            {{ $product->subcategory->category->family->name }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="px-2 py-1 text-teal-700 bg-teal-100 rounded-md">
                            <i class="mr-1 fas fa-tags"></i>
                            {{ $product->subcategory->category->name }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="px-2 py-1 text-pink-700 bg-pink-100 rounded-md">
                            <i class="mr-1 fas fa-tag"></i>
                            {{ $product->subcategory->name }}
                        </span>
                    </div>
                </div> <!-- Información adicional -->
                <div class="mb-4 space-y-2 text-sm text-gray-600">
                    @if ($product->created_at)
                    <div class="flex items-center space-x-2">
                        <i class="text-blue-500 fas fa-calendar-plus"></i>
                        <span>{{ $product->created_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>

                <!-- Botón de acción -->
                <div class="mt-auto">
                    <a href="{{ route('admin.products.edit', $product) }}"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="mr-2 fas fa-edit"></i>
                        Editar Producto
                    </a>
                </div>
            </div>

            <!-- Indicador de hover -->
            <div
                class="absolute inset-x-0 bottom-0 h-1 transition-transform duration-300 transform scale-x-0 bg-gradient-to-r from-blue-500 to-purple-600 group-hover:scale-x-100">
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
<div id="table-view" class="hidden view-content">
    <div class="overflow-hidden border border-gray-200 shadow-lg rounded-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-600 to-purple-600">
                    <tr>
                        {{-- MEJORA UI #2: Nueva columna ID agregada --}}
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-white uppercase">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-hashtag"></i>
                                <span>ID</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-white uppercase">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-box"></i>
                                <span>Producto</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-white uppercase">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-barcode"></i>
                                <span>SKU</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-white uppercase">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Precio</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-center text-white uppercase">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-toggle-on"></i>
                                <span>Estado</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold tracking-wider text-center text-white uppercase">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-cogs"></i>
                                <span>Acciones</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                    <tr class="transition-colors duration-200 hover:bg-blue-50">
                        {{-- MEJORA UI #2: Celda ID con badge azul consistente --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                #{{ $product->id }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 mr-3 overflow-hidden bg-gray-100 rounded-lg">
                                    @if ($product->image_path)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                        class="object-cover w-full h-full">
                                    @else
                                    <div
                                        class="flex items-center justify-center w-full h-full bg-gradient-to-br from-blue-100 to-purple-100">
                                        <i class="text-sm text-gray-400 fas fa-image"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
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
                                class="inline-flex items-center px-2 py-1 font-mono text-xs text-gray-700 bg-gray-100 rounded-md">
                                {{ $product->sku }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">Sin SKU</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-bold text-green-600">
                                ${{ number_format($product->price, 2) }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <button
                                onclick="toggleProductStatus({{ $product->id }}, {{ $product->is_active ? 'false' : 'true' }})"
                                class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full transition-all duration-200 hover:scale-105
                                           {{ $product->is_active ? 'text-green-800 bg-green-100 hover:bg-green-200' : 'text-red-800 bg-red-100 hover:bg-red-200' }}">
                                <i class="mr-1 fas {{ $product->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="mr-2 fas fa-edit"></i>
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