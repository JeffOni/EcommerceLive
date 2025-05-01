<div>
    {{-- Sección principal que contiene toda la gestión de opciones y características de producto --}}
    <section class="bg-white border border-gray-100 rounded-lg shadow-lg ">

        <header class="px-6 py-2 border-b border-gray-200">
            <div class="flex justify-between">
                <h1 class="text-lg font-semibold text-gray-700">Opciones</h1>
                {{-- Botón para abrir el modal de agregar nueva opción al producto --}}
                <x-button wire:click="$set('openModal', true)" name="Nuevo" />
            </div>
        </header>

        <div class="p-6">
            {{-- Sección donde se muestran las opciones existentes del producto con sus características --}}
            @if ($product->options->count())
                <div class="space-y-6">
                    {{-- Iteramos sobre cada opción del producto --}}
                    @foreach ($product->options as $option)
                        <div wire:key="product-option-{{ $option->id }}"
                            class="relative p-6 mb-4 border border-gray-200 rounded-lg">

                            <div class="absolute px-4 bg-white -top-3">
                                {{-- Botón para eliminar la opción completa --}}
                                <button>
                                    <i class="text-red-500 fas fa-trash-can hover:text-red-600"
                                        onclick="confirmDeleteOption({{ $option->id }})">
                                    </i>
                                </button>

                                <span class="ml-2 text-sm font-semibold text-gray-500">
                                    {{ $option->name }}
                                </span>
                                {{-- ÁREA CRÍTICA DE RENDIMIENTO: Componente que muestra el botón + para agregar características --}}
                                {{-- Este componente renderiza un botón que al hacer clic emite un evento al componente padre --}}
                                {{-- La demora ocurre entre el clic en este botón y la apertura del menú de características --}}
                                <livewire:admin.products.add-feature-to-option :option="$option" :product="$product" :key="'add-feature-button-'.$option->id" />
                            </div>

                            {{-- Visualización de las características existentes en la opción --}}
                            <div class="flex flex-wrap">
                                @foreach ($option->pivot->features as $feature)
                                    <div wire:key="option-{{$option->id}}-feature-{{ $feature['id'] }}">
                                        @switch($option->type)
                                            @case(1)
                                                {{-- Para características de tipo texto (type=1) --}}
                                                <span
                                                    class="bg-gray-100 text-gray-800 text-xs font-medium me-2 pl-2.5 pr-1.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $feature['description'] }}

                                                    <button class="ml-0.5"
                                                        onclick="confirmDeleteFeature({{ $option->id }},{{ $feature['id'] }})">
                                                        <i class="fa-solid fa-xmark hover:text-red-500"></i>
                                                    </button>
                                                </span>
                                            @break

                                            @case(2)
                                                {{-- Para características de tipo color (type=2) --}}
                                                <div class="relative">
                                                    <span
                                                        class="inline-block w-6 h-6 mr-4 border-2 border-gray-300 rounded-full shadow-lg"
                                                        style="background-color: {{ $feature['value'] }};">
                                                    </span>

                                                    <button
                                                        class="absolute z-10 flex items-center justify-center w-4 h-4 bg-gray-500 rounded-full hover:bg-red-500 -top-2 left-3"
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
                            <div wire:key="feature-form-container-{{ $option->id }}" id="feature-form-{{ $option->id }}"
                                 class="feature-form-container mt-3"
                                 wire:loading.class="opacity-50"
                                 wire:target="toggleAddFeature">
                                @if($showAddFeature === $option->id)
                                <div class="border-t pt-3">
                                    <form wire:submit.prevent="addFeaturesToOption({{ $option->id }})">
                                        <div class="mb-2 font-semibold text-gray-700">Selecciona las características a agregar:</div>

                                        {{-- OPTIMIZACIÓN: Reemplazamos la consulta directa por la propiedad computada --}}
                                        {{-- Ya no necesitamos ejecutar código PHP en la vista --}}
                                        @forelse($this->getAvailableFeaturesForOption($option->id) as $feature)
                                            <label class="flex items-center space-x-2 mb-2">
                                                <input type="checkbox" wire:model.defer="featuresToAdd.{{ $option->id }}.{{ $feature->id }}" value="1" class="form-checkbox">
                                                <span>{{ $feature->description }}</span>
                                            </label>
                                        @empty
                                            <div class="text-gray-400 text-sm">No hay características disponibles para agregar.</div>
                                        @endforelse

                                        @error('featuresToAdd.'.$option->id)
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-3 flex gap-2">
                                            <x-button type="submit" name="Agregar seleccionados" positive />
                                            <x-danger-button type="button" name="Cancelar" flat wire:click="toggleAddFeature(null)" />
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
                <div class="flex items-center p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                    role="alert">
                    <svg class="inline w-4 h-4 shrink-0 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">Alerta Informativa!</span> No hay opciones disponibles para este
                        producto.
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Modal para agregar una nueva opción al producto --}}
    <x-dialog-modal title="nueva Variante" wire:model.defer="openModal">
        <x-slot name="content">
            {{-- Errores de validación --}}
            <x-validation-errors class="mb-4" />

            {{-- Selector de opciones disponibles --}}
            <div class="mb-4">
                <x-label class="mb-1" value="{{ __('Opcion') }}" />
                <x-select class="w-full" wire:model.live="variants.option_id" name="variants.option_id"
                    id="variants.option_id">
                    <option value="" disabled>Seleccione una opción</option>
                    @foreach ($options as $option)
                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Sección para seleccionar características de la opción --}}
            <div class="flex items-center mb-6">
                <hr class="flex-1">
                <span class="mx-4">Valores</span>
                <hr class="flex-1">
            </div>

            {{-- Lista de características seleccionadas --}}
            <ul class="mb-4 space-y-4">
                @foreach ($variants['features'] as $index => $feature)
                    <li wire:key="variants-feature-{{ $index }}"
                        class="relative p-6 mb-4 border border-gray-200 rounded-lg">
                        <div class="absolute px-4 bg-white -top-3">
                            <button>
                                <i class="text-red-500 fas fa-trash-can hover:text-red-600"
                                    wire:click="removeFeature({{ $index }})"></i>
                            </button>
                        </div>
                        <div>
                            <x-label class="mb-1" value="{{ __('Valores') }}" />
                            <x-select class="w-full" wire:model.live="variants.features.{{ $index }}.id"
                                name="variants.features.{{ $index }}.id"
                                id="variants.features.{{ $index }}.id"
                                wire:change="feature_change({{ $index }})">
                                <option value="">Seleccione un valor</option>
                                @foreach ($this->features as $feature)
                                    <option value="{{ $feature->id }}">{{ $feature->description }}</option>)
                                @endforeach
                            </x-select>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- Botón para agregar más características --}}
            <div class="flex justify-end">
                <x-button wire:click="addFeature" name="Agregar Valor" positive />
            </div>
        </x-slot>

        {{-- Botones del footer del modal --}}
        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <x-danger-button name="Cancelar" flat wire:click="$set('openModal', false)" />
                <x-button name="Guardar" wire:click="save" positive />
            </div>
        </x-slot>
    </x-dialog-modal>

    {{-- Scripts JS para las confirmaciones de eliminación --}}
    @push('js')
        <script>
            function confirmDeleteFeature(option_id, feature_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteFeature', option_id, feature_id);
                    }
                })
            }

            function confirmDeleteOption(option_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteOption', option_id);
                    }
                })
            }
        </script>
    @endpush
</div>
