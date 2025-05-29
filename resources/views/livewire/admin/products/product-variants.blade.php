<div class="min-h-screen bg-gray-50 p-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
            Gestión de Variantes
        </h1>
        <p class="text-gray-600 text-lg">Configure las opciones y variantes del producto</p>
    </div>

    <div class="max-w-6xl mx-auto space-y-8">
        {{-- Sección principal que contiene toda la gestión de opciones y características de producto --}}
        <section class="bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden">
            <header class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-cogs text-indigo-500 mr-3 text-xl"></i>
                        <h1 class="text-2xl font-bold text-gray-800">Opciones</h1>
                    </div>
                    {{-- Botón para abrir el modal de agregar nueva opción al producto --}}
                    <x-button wire:click="$set('openModal', true)"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                        name="Nueva Opción" />
                </div>
            </header>

            <div class="p-8">
                {{-- Sección donde se muestran las opciones existentes del producto con sus características --}}
                @if ($product->options->count())
                    <div class="space-y-8">
                        {{-- Iteramos sobre cada opción del producto --}}
                        @foreach ($product->options as $option)
                            <div wire:key="product-option-{{ $option->id }}"
                                class="relative p-8 bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                                <div
                                    class="absolute -top-4 left-6 px-6 py-2 bg-white rounded-xl shadow-lg border border-gray-200">
                                    {{-- Botón para eliminar la opción completa --}}
                                    <button
                                        class="mr-3 p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200">
                                        <i class="fas fa-trash-can text-lg"
                                            onclick="confirmDeleteOption({{ $option->id }})"></i>
                                    </button>

                                    <span class="text-lg font-bold text-gray-700 mr-4">
                                        {{ $option->name }}
                                    </span>
                                    {{-- ÁREA CRÍTICA DE RENDIMIENTO: Componente que muestra el botón + para agregar características --}}
                                    {{-- Este componente renderiza un botón que al hacer clic emite un evento al componente padre --}}
                                    <livewire:admin.products.add-feature-to-option :option="$option" :product="$product"
                                        :key="'add-feature-button-' . $option->id" />
                                </div>

                                {{-- Visualización de las características existentes en la opción --}}
                                <div class="flex flex-wrap gap-3 mt-4">
                                    @foreach ($option->pivot->features as $feature)
                                        <div wire:key="option-{{ $option->id }}-feature-{{ $feature['id'] }}">
                                            @switch($option->type)
                                                @case(1)
                                                    {{-- Para características de tipo texto (type=1) --}}
                                                    <span
                                                        class="bg-gradient-to-r from-blue-100 to-indigo-100 text-indigo-800 text-sm font-semibold px-4 py-2 rounded-xl border border-indigo-200 shadow-sm hover:shadow-md transition-all duration-200 flex items-center">
                                                        {{ $feature['description'] }}
                                                        <button
                                                            class="ml-2 p-1 hover:bg-red-100 rounded-full transition-colors duration-200"
                                                            onclick="confirmDeleteFeature({{ $option->id }},{{ $feature['id'] }})">
                                                            <i
                                                                class="fa-solid fa-xmark text-red-500 hover:text-red-700 text-xs"></i>
                                                        </button>
                                                    </span>
                                                @break

                                                @case(2)
                                                    {{-- Para características de tipo color (type=2) --}}
                                                    <div class="relative">
                                                        <span
                                                            class="inline-block w-12 h-12 border-4 border-white rounded-full shadow-lg ring-2 ring-gray-200 hover:ring-gray-300 transition-all duration-200"
                                                            style="background-color: {{ $feature['value'] }};"></span>
                                                        <button
                                                            class="absolute -top-1 -right-1 flex items-center justify-center w-6 h-6 bg-red-500 hover:bg-red-600 rounded-full shadow-lg transition-all duration-200 transform hover:scale-110"
                                                            onclick="confirmDeleteFeature({{ $option->id }}, {{ $feature['id'] }})">
                                                            <i class="text-xs text-white fa-solid fa-xmark"></i>
                                                        </button>
                                                    </div>
                                                @break

                                                @default
                                            @endswitch
                                        </div>
                                    @endforeach
                                </div>

                                {{-- SECCIÓN CRÍTICA: Área donde se muestra el formulario para agregar características --}}
                                {{-- Esta es la sección que se muestra/oculta al hacer clic en el botón + --}}
                                {{-- La demora ocurre principalmente al mostrar este contenedor con las características disponibles --}}
                                <div wire:key="feature-form-container-{{ $option->id }}"
                                    id="feature-form-{{ $option->id }}" class="mt-6 feature-form-container"
                                    wire:loading.class="opacity-50" wire:target="toggleAddFeature">
                                    @if ($showAddFeature === $option->id)
                                        <div class="pt-6 border-t-2 border-gray-200">
                                            <form wire:submit.prevent="addFeaturesToOption({{ $option->id }})"
                                                class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200 shadow-inner">
                                                <div class="mb-4 text-lg font-bold text-gray-700 flex items-center">
                                                    <i class="fas fa-plus-circle text-blue-500 mr-2"></i>
                                                    Selecciona las características a agregar:
                                                </div>

                                                {{-- OPTIMIZACIÓN: Reemplazamos la consulta directa por la propiedad computada --}}
                                                {{-- Ya no necesitamos ejecutar código PHP en la vista --}}
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                                    @forelse($this->getAvailableFeaturesForOption($option->id) as $feature)
                                                        <label
                                                            class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                                                            <input type="checkbox"
                                                                wire:model.defer="featuresToAdd.{{ $option->id }}.{{ $feature->id }}"
                                                                value="1"
                                                                class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                            <span
                                                                class="ml-3 text-gray-700 font-medium">{{ $feature->description }}</span>
                                                        </label>
                                                    @empty
                                                        <div class="col-span-2 text-center py-8">
                                                            <i
                                                                class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                                                            <div class="text-gray-500">No hay características
                                                                disponibles para agregar.</div>
                                                        </div>
                                                    @endforelse
                                                </div>

                                                @error('featuresToAdd.' . $option->id)
                                                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                        <div class="text-sm text-red-600 flex items-center">
                                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                                            {{ $message }}
                                                        </div>
                                                    </div>
                                                @enderror

                                                <div class="flex gap-4 justify-end">
                                                    <x-button type="submit"
                                                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                                                        name="Agregar seleccionados" positive />
                                                    <x-danger-button type="button"
                                                        class="px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                                                        name="Cancelar" flat wire:click="toggleAddFeature(null)" />
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Mensaje cuando el producto no tiene opciones configuradas --}}
                    <div class="flex items-center justify-center p-12 text-center">
                        <div class="max-w-md">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-cogs text-blue-500 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay opciones disponibles</h3>
                            <p class="text-gray-500 mb-6">Este producto aún no tiene opciones configuradas. Agregue una
                                nueva opción para comenzar.</p>
                            <x-button wire:click="$set('openModal', true)"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                                name="Agregar Primera Opción" />
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- end section --}}

        {{-- Sección de Variantes --}}
        <section class="bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden">
            <header class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-layer-group text-purple-500 mr-3 text-xl"></i>
                        <h1 class="text-2xl font-bold text-gray-800">Variantes del Producto</h1>
                    </div>
                    @if ($product->options->count())
                        <div
                            class="text-sm text-gray-600 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                            <i class="fas fa-info-circle mr-2"></i>
                            Total: {{ $product->variants->count() }}
                            variante{{ $product->variants->count() !== 1 ? 's' : '' }}
                        </div>
                    @endif
                </div>
            </header>

            <div class="p-8">
                @if ($product->variants->count())
                    {{-- Grid de variantes del producto --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        {{-- Iteramos sobre cada variante del producto --}}
                        @foreach ($product->variants as $item)
                            <div wire:key="variant-{{ $item->id }}"
                                class="group relative bg-gradient-to-br from-white to-gray-50 border-2 border-gray-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] overflow-hidden flex flex-col min-h-[400px]">
                                {{-- Imagen de la variante --}}
                                <div class="relative overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->image }}" alt="Imagen de la variante"
                                        class="w-full h-48 object-cover object-center transition-transform duration-300 group-hover:scale-110">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>
                                </div>

                                {{-- Contenido de la variante --}}
                                <div class="p-6 flex flex-col h-full">
                                    {{-- Características de la variante - ocupa el espacio disponible --}}
                                    <div class="flex-1 mb-4">
                                        <h3 class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">
                                            Características</h3>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($item->features as $feature)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-indigo-100 text-indigo-700 border border-indigo-200">
                                                    <i class="fas fa-tag mr-1 text-indigo-500"></i>
                                                    {{ $feature->description }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Botón de edición en la parte inferior --}}
                                    <div class="flex justify-end pt-4 border-t border-gray-100">
                                        <x-link name="Editar Variante"
                                            href="{{ route('admin.products.variants', [$product, $item]) }}"
                                            target="_blank"
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                            <i class="fas fa-edit mr-2"></i>
                                            Editar
                                        </x-link>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Estado vacío moderno cuando el producto no tiene variantes configuradas --}}
                    <div class="flex items-center justify-center p-12 text-center">
                        <div class="max-w-md">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-layer-group text-purple-500 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay variantes disponibles</h3>
                            <p class="text-gray-500 mb-6">Este producto aún no tiene variantes configuradas. Las
                                variantes se generan automáticamente cuando agrega opciones y características.</p>
                            <div
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-dashed border-indigo-200 rounded-xl p-6">
                                <div class="flex items-center text-sm text-indigo-700">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    <span class="font-medium">Consejo:</span>
                                </div>
                                <p class="text-indigo-600 text-sm mt-2">Agregue opciones (como talla, color) y sus
                                    características para generar variantes automáticamente.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>




        {{-- Modal moderno para agregar una nueva opción al producto --}}
        <x-dialog-modal title="Nueva Variante" wire:model.defer="openModal">
            <x-slot name="content">
                <div class="space-y-6">
                    {{-- Header del modal con icono --}}
                    <div class="text-center pb-4 border-b border-gray-200">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus-circle text-indigo-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Agregar Nueva Variante</h3>
                        <p class="text-sm text-gray-600 mt-1">Configure las opciones y características para la nueva
                            variante</p>
                    </div>

                    {{-- Errores de validación con estilo moderno --}}
                    <x-validation-errors class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg" />

                    {{-- Selector de opciones disponibles --}}
                    <div class="space-y-2">
                        <x-label class="text-sm font-semibold text-gray-700 flex items-center" value="Opción">
                            <i class="fas fa-cog text-indigo-500 mr-2"></i>
                            Opción
                        </x-label>
                        <x-select
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                            wire:model.live="variants.option_id" name="variants.option_id" id="variants.option_id">
                            <option value="" disabled>Seleccione una opción</option>
                            @foreach ($options as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </x-select>
                    </div>

                    {{-- Divider decorativo --}}
                    <div class="flex items-center my-6">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        <div
                            class="px-4 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full border border-indigo-200">
                            <span class="text-sm font-medium text-indigo-700">Características</span>
                        </div>
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    </div>

                    {{-- Lista de características seleccionadas --}}
                    <div class="space-y-4">
                        @foreach ($variants['features'] as $index => $feature)
                            <div wire:key="variants-feature-{{ $index }}"
                                class="relative bg-gradient-to-r from-gray-50 to-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-200">
                                {{-- Botón de eliminar característica --}}
                                <div class="absolute -top-3 -right-3">
                                    <button
                                        class="flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-110"
                                        wire:click="removeFeature({{ $index }})">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>

                                {{-- Selector de valores --}}
                                <div class="space-y-2">
                                    <x-label class="text-sm font-semibold text-gray-700 flex items-center"
                                        value="Valores">
                                        <i class="fas fa-tag text-purple-500 mr-2"></i>
                                        Valores
                                    </x-label>
                                    <x-select
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"
                                        wire:model.live="variants.features.{{ $index }}.id"
                                        name="variants.features.{{ $index }}.id"
                                        id="variants.features.{{ $index }}.id"
                                        wire:change="feature_change({{ $index }})">
                                        <option value="">Seleccione un valor</option>
                                        @foreach ($this->features as $feature)
                                            <option value="{{ $feature->id }}">{{ $feature->description }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Botón para agregar más características --}}
                    <div class="flex justify-center pt-4">
                        <x-button wire:click="addFeature"
                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                            name="Agregar Valor" positive>
                            <i class="fas fa-plus mr-2"></i>
                        </x-button>
                    </div>
                </div>
            </x-slot>

            {{-- Footer del modal con botones mejorados --}}
            <x-slot name="footer">
                <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                    <x-danger-button name="Cancelar" flat wire:click="$set('openModal', false)"
                        class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" />
                    <x-button name="Guardar" wire:click="save" positive
                        class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>
                        Guardar
                    </x-button>
                </div>
            </x-slot>
        </x-dialog-modal>

        {{-- Scripts JS modernos para las confirmaciones de eliminación --}}
        @push('js')
            <script>
                function confirmDeleteFeature(option_id, feature_id) {
                    Swal.fire({
                        title: '¿Eliminar característica?',
                        text: "Esta acción no se puede deshacer",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Sí, eliminar',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl',
                            title: 'text-gray-800 font-bold',
                            confirmButton: 'rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300',
                            cancelButton: 'rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('deleteFeature', option_id, feature_id);
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: 'La característica ha sido eliminada.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-2xl shadow-2xl'
                                }
                            });
                        }
                    })
                }

                function confirmDeleteOption(option_id) {
                    Swal.fire({
                        title: '¿Eliminar opción completa?',
                        text: "Se eliminarán todas las características asociadas",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Sí, eliminar todo',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl',
                            title: 'text-gray-800 font-bold',
                            confirmButton: 'rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300',
                            cancelButton: 'rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('deleteOption', option_id);
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: 'La opción y todas sus características han sido eliminadas.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-2xl shadow-2xl'
                                }
                            });
                        }
                    })
                }
            </script>
        @endpush
    </div>
