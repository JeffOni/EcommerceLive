{{-- 
    VISTA DE SUBCATEGORÍAS - MEJORAS UI APLICADAS
    
    MEJORA UI #3: Eliminación de botones hover redundantes
    - Se removieron los botones de edición que aparecían al hacer hover sobre las tarjetas
    - Cada tarjeta mantiene su botón "Editar Subcategoría" visible en la parte inferior
    - Mejora la consistencia con familias, categorías y productos
    
    MEJORA UI #4: Paginación simplificada
    - Se eliminó el texto duplicado de paginación "Mostrando X a Y de Z resultados"
    - La paginación ahora se maneja únicamente por el componente {{ $subcategories->links() }}
--}}

<!-- Vista de tarjetas -->
<div id="cards-view" class="view-content">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @foreach ($subcategories as $subcategory)
            <div
                class="group relative bg-gradient-to-br from-white to-pink-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-pink-100 overflow-hidden flex flex-col h-full">
                <!-- Badge ID -->
                <div class="absolute top-3 left-3 z-10">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                        #{{ $subcategory->id }}
                    </span>
                </div>

                {{-- 
                    MEJORA UI #3: Botones de edición hover removidos para subcategorías
                    - Se eliminaron para evitar duplicación con el botón principal "Editar Subcategoría"
                    - Mantiene la interfaz limpia y consistente con las demás vistas del admin
                
                CÓDIGO REMOVIDO:
                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.subcategories.edit', $subcategory) }}"
                            class="p-2 bg-white/90 backdrop-blur-sm rounded-lg shadow-md hover:bg-pink-500 hover:text-white transition-all duration-200">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                    </div>
                </div>
                --}}

                <!-- Contenido de la tarjeta -->
                <div class="p-6 pt-16 flex-1 flex flex-col">
                    <div class="text-center flex-1">
                        <!-- Icono principal -->
                        <div
                            class="mx-auto w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                            <i class="fas fa-tag text-white text-2xl"></i>
                        </div>

                        <!-- Nombre de la subcategoría -->
                        <h3
                            class="text-xl font-bold text-gray-900 mb-3 group-hover:text-pink-600 transition-colors duration-300">
                            {{ $subcategory->name }}
                        </h3>

                        <!-- Jerarquía (Familia > Categoría) -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-center space-x-2 text-xs">
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-md">
                                    <i class="fas fa-layer-group mr-1"></i>
                                    {{ $subcategory->category->family->name }}
                                </span>
                            </div>
                            <div class="flex items-center justify-center">
                                <i class="fas fa-arrow-down text-gray-400 text-xs"></i>
                            </div>
                            <div class="flex items-center justify-center space-x-2 text-xs">
                                <span class="bg-teal-100 text-teal-700 px-2 py-1 rounded-md">
                                    <i class="fas fa-tags mr-1"></i>
                                    {{ $subcategory->category->name }}
                                </span>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-box text-pink-500"></i>
                                <span>{{ $subcategory->products->count() }} productos</span>
                            </div>

                            @if ($subcategory->created_at)
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="fas fa-calendar-plus text-pink-500"></i>
                                    <span>{{ $subcategory->created_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Botón de acción -->
                    <div class="mt-auto pt-4">
                        <a href="{{ route('admin.subcategories.edit', $subcategory) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Subcategoría
                        </a>
                    </div>
                </div>

                <!-- Indicador de hover -->
                <div
                    class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-pink-500 to-rose-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Vista de tabla -->
<div id="table-view" class="view-content hidden">
    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-pink-600 to-rose-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-hashtag"></i>
                                <span>ID</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-tag"></i>
                                <span>Subcategoría</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-sitemap"></i>
                                <span>Jerarquía</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-box"></i>
                                <span>Productos</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar"></i>
                                <span>Fecha de Creación</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-cogs"></i>
                                <span>Acciones</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($subcategories as $subcategory)
                        <tr class="hover:bg-pink-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    #{{ $subcategory->id }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-tag text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $subcategory->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="flex items-center">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-700">
                                            <i class="fas fa-layer-group mr-1"></i>
                                            {{ $subcategory->category->family->name }}
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-teal-100 text-teal-700">
                                            <i class="fas fa-tags mr-1"></i>
                                            {{ $subcategory->category->name }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-box mr-2 text-pink-500"></i>
                                        {{ $subcategory->products->count() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if ($subcategory->created_at)
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-plus text-pink-500 mr-2"></i>
                                        {{ $subcategory->created_at->format('d/m/Y') }}
                                        <span
                                            class="block text-xs text-gray-400">{{ $subcategory->created_at->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.subcategories.edit', $subcategory) }}"
                                        class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <i class="fas fa-edit mr-2"></i>
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 
    MEJORA UI #4: Paginación simplificada para subcategorías
    - Se eliminó el texto duplicado "Mostrando X a Y de Z resultados" 
    - Anteriormente aparecía tanto manualmente como en el componente links()
    - Ahora solo se muestra la paginación una vez usando el componente estándar de Laravel
    - Completa la consistencia en todas las vistas del panel de administración
--}}
<!-- Paginación -->
@if ($subcategories->hasPages())
    <div class="mt-8">
        {{ $subcategories->links() }}
    </div>
@endif
