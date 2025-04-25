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
                                <option value="" >Seleccione un valor</option>
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
</div>
