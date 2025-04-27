<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <section class="bg-white border border-gray-100 rounded-lg shadow-lg ">

        <header class="px-6 py-2 border-b border-gray-200">
            <div class="flex justify-between">
                <h1 class="text-lg font-semibold text-gray-700">Opciones</h1>
                <x-button wire:click="$set('openModal', true)" name="Nuevo" />
            </div>


        </header>

        <div class="p-6">

            @if ($product->options->count())
                <div class="space-y-6">

                    @foreach ($product->options as $option)
                        <div wire:key="product-option-{{ $option->id }}"
                            class="relative p-6 mb-4 border border-gray-200 rounded-lg">

                            <div class="absolute px-4 bg-white -top-3">

                                <button>
                                    <i class="text-red-500 fas fa-trash-can hover:text-red-600"
                                        onclick="confirmDeleteOption({{ $option->id }})">
                                    </i>
                                </button>

                                <span class="ml-2 text-sm font-semibold text-gray-500">
                                    {{ $option->name }}
                                </span>

                            </div>


                            {{-- Valores --}}

                            <div class="flex flex-wrap">

                                @foreach ($option->pivot->features as $feature)
                                    @switch($option->type)
                                        @case(1)
                                            <span
                                                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 pl-2.5 pr-1.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">
                                                {{ $feature['description'] }}

                                                <button class="ml-0.5"
                                                    onclick="confirmDeleteFeature({{ $option->id }},{{ $feature['id'] }})"
                                                    {{-- wire:click="deleteFeature({{ $feature->id }})" --}}>
                                                    {{-- icono de eliminar --}}

                                                    <i class="fa-solid fa-xmark hover:text-red-500"></i>

                                                </button>
                                            </span>
                                        @break

                                        @case(2)
                                            <div class="relative">
                                                {{-- para obtener colores se obtiene el color guardado en value --}}
                                                <span
                                                    class="inline-block w-6 h-6 mr-4 border-2 border-gray-300 rounded-full shadow-lg"
                                                    style="background-color: {{ $feature['value'] }};">
                                                </span>

                                                <button
                                                    class="absolute z-10 flex items-center justify-center w-4 h-4 bg-gray-500 rounded-full hover:bg-red-500 -top-2 left-3"
                                                    onclick="confirmDeleteFeature({{ $option->id }}, {{ $feature['id'] }})"
                                                    {{-- wire:click="deleteFeature({{ $feature->id }})" --}}>
                                                    {{-- icono de eliminar --}}
                                                    <i class="text-xs text-white fa-solid fa-xmark"></i>

                                                </button>

                                            </div>
                                        @break

                                        @default
                                    @endswitch
                                @endforeach

                            </div>

                            {{-- Fin Valores --}}
                        </div>
                    @endforeach

                </div>
            @else
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

    <x-dialog-modal title="nueva Variante" wire:model.defer="openModal">
        <x-slot name="content">
            {{-- errores de validacion --}}
            <x-validation-errors class="mb-4" />
            {{-- Options --}}
            <div class="mb-4">
                <x-label class="mb-1" value="{{ __('Opcion') }}" />
                <x-select class="w-full" wire:model.live="variants.option_id" name="variants.option_id"
                    id="variants.option_id">
                    {{-- wire:model.live el .live sirve para que se vaya acutalizando el estdo del option_id conforme se vaya escribiendo --}}
                    <option value="" disabled>Seleccione una opción</option>
                    @foreach ($options as $option)
                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Valores --}}
            <div class="flex items-center mb-6">
                <hr class="flex-1">

                <span class="mx-4">Valores</span>

                <hr class="flex-1">
            </div>
            {{-- detalle de valores --}}
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
                                {{-- wire:model.live el .live sirve para que se vaya acutalizando el estdo del option_id conforme se vaya escribiendo --}}
                                <option value="">Seleccione un valor</option>
                                @foreach ($this->features as $feature)
                                    <option value="{{ $feature->id }}">{{ $feature->description }}</option>)
                                @endforeach
                            </x-select>

                        </div>


                    </li>
                @endforeach
            </ul>

            {{-- Add nuevos valores --}}
            <div class="flex justify-end">
                <x-button wire:click="addFeature" name="Agregar Valor" positive />
            </div>

        </x-slot>
        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <x-danger-button name="Cancelar" flat wire:click="$set('openModal', false)" />
                <x-button name="Guardar" wire:click="save" positive />
            </div>
        </x-slot>
    </x-dialog-modal>

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
