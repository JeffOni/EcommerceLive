{{--
VISTA DE FAMILIAS - MEJORAS UI APLICADAS

MEJORA UI #3: Eliminación de botones hover redundantes
- Se removieron los botones de edición que aparecían al hacer hover sobre las tarjetas
- Cada tarjeta mantiene su botón "Editar Familia" visible en la parte inferior
- Esto mejora la consistencia con las otras vistas y evita duplicación de controles

MEJORA UI #4: Paginación simplificada
- Se eliminó el texto duplicado de paginación "Mostrando X a Y de Z resultados"
- La paginación ahora se maneja únicamente por el componente {{ $families->links() }}
--}}

<!-- Vista de tarjetas -->
<div id="cards-view" class="view-content">
    <div
        class="grid grid-cols-1 gap-3 px-2 mb-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 sm:gap-4 lg:gap-6 sm:mb-8 sm:px-0">
        @foreach ($families as $family)
        <div
            class="relative flex flex-col h-full overflow-hidden transition-all duration-500 transform border shadow-lg group bg-gradient-to-br from-white to-secondary-50 rounded-xl sm:rounded-2xl hover:shadow-2xl hover:-translate-y-1 sm:hover:-translate-y-2 border-secondary-100">
            <!-- Badge ID -->
            <div class="absolute z-10 top-2 sm:top-3 left-2 sm:left-3">
                <span
                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-secondary-100 text-secondary-800">
                    #{{ $family->id }}
                </span>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="flex flex-col flex-1 p-4 pt-12 sm:p-6 sm:pt-16">
                <div class="flex-1 text-center">
                    <!-- Icono principal -->
                    <div
                        class="flex items-center justify-center w-12 h-12 mx-auto mb-3 shadow-lg sm:w-16 sm:h-16 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-xl sm:rounded-2xl sm:mb-4">
                        <i class="text-lg text-white fas fa-layer-group sm:text-2xl"></i>
                    </div>

                    <!-- Nombre de la familia -->
                    <h3
                        class="mb-2 text-base font-bold text-gray-900 truncate transition-colors duration-300 sm:text-xl group-hover:text-primary-600">
                        {{ $family->name }}
                    </h3>

                    <!-- Información adicional -->
                    <div class="space-y-1 text-xs text-gray-600 sm:space-y-2 sm:text-sm">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            <i class="fas fa-sitemap text-secondary-500"></i>
                            <span>{{ $family->categories->count() }} categorías</span>
                        </div>

                        @if ($family->created_at)
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            <i class="fas fa-calendar-plus text-secondary-500"></i>
                            <span class="hidden sm:inline">Creada {{ $family->created_at->diffForHumans() }}</span>
                            <span class="sm:hidden">{{ $family->created_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Botón de acción -->
                <div class="pt-3 mt-auto sm:pt-4">
                    <a href="{{ route('admin.families.edit', $family) }}"
                        class="w-full inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="mr-1 fas fa-edit sm:mr-2"></i>
                        <span class="hidden sm:inline">Editar Familia</span>
                        <span class="sm:hidden">Editar</span>
                    </a>
                </div>
            </div>

            <!-- Indicador de hover -->
            <div
                class="absolute inset-x-0 bottom-0 h-1 transition-transform duration-300 transform scale-x-0 bg-gradient-to-r from-primary-500 to-secondary-600 group-hover:scale-x-100">
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Vista de tabla -->
<div id="table-view" class="hidden view-content">
    <div class="mx-2 overflow-hidden border border-gray-200 shadow-lg rounded-xl sm:rounded-2xl sm:mx-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-primary-900 to-secondary-500">
                    <tr>
                        <th scope="col"
                            class="px-3 py-3 text-xs font-medium tracking-wider text-left text-white uppercase sm:px-6">
                            ID
                        </th>
                        <th scope="col"
                            class="px-3 py-3 text-xs font-medium tracking-wider text-left text-white uppercase sm:px-6">
                            Familia
                        </th>
                        <th scope="col"
                            class="hidden px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase sm:table-cell">
                            Categorías
                        </th>
                        <th scope="col"
                            class="hidden px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase lg:table-cell">
                            Fecha Creación
                        </th>
                        <th scope="col"
                            class="px-3 py-3 text-xs font-medium tracking-wider text-right text-white uppercase sm:px-6">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($families as $family)
                    <tr class="transition-colors duration-200 hover:bg-gray-50">
                        <td class="px-3 py-4 text-xs font-medium text-gray-900 sm:px-6 whitespace-nowrap sm:text-sm">
                            #{{ $family->id }}
                        </td>
                        <td class="px-3 py-4 sm:px-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg sm:h-10 sm:w-10 bg-gradient-to-br from-primary-500 to-secondary-600">
                                    <i class="text-xs text-white fas fa-layer-group sm:text-sm"></i>
                                </div>
                                <div class="ml-2 sm:ml-4">
                                    <div
                                        class="text-xs font-medium text-gray-900 truncate sm:text-sm max-w-24 sm:max-w-none">
                                        {{ $family->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="hidden px-6 py-4 text-sm text-gray-500 sm:table-cell whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800">
                                {{ $family->categories->count() }} categorías
                            </span>
                        </td>
                        <td class="hidden px-6 py-4 text-sm text-gray-500 lg:table-cell whitespace-nowrap">
                            @if ($family->created_at)
                            <div class="text-sm text-gray-900">{{ $family->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $family->created_at->format('H:i') }}</div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 text-xs font-medium text-right sm:px-6 whitespace-nowrap sm:text-sm">
                            <a href="{{ route('admin.families.edit', $family) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-white transition-all duration-200 rounded-md shadow-sm sm:px-3 sm:py-2 bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 hover:shadow-md">
                                <i class="mr-1 fas fa-edit"></i>
                                <span class="hidden sm:inline">Editar</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


</div>
</div>
</div>

<!-- Paginación responsive -->
@if ($families->hasPages())
<div class="px-2 mt-6 sm:mt-8 sm:px-0">
    <div class="pagination-container">
        {{ $families->links() }}
    </div>
</div>
@endif