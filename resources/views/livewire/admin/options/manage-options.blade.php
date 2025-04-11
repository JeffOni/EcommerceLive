<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <section class="bg-white rounded-lg shadow-lg ">

        <header class="px-6 py-2 border-b border-gray-200">
            <div class="flex justify-between">
                <h1 class="text-lg font-semibold text-gray-700">Opciones</h1>
                <x-button wire:click="$set('openModal', true)" name="Nuevo" />
            </div>


        </header>

        <div class="p-6">

            <div class="space-y-6">

                @foreach ($options as $option)
                    {{-- Tarjeta de Opcion --}}
                    <div class="relative p-6 border border-gray-200 rounded-lg">
                        {{-- Nombre de Opciones --}}
                        <div class="absolute px-4 bg-white -top-3.5">

                            <span>
                                {{ $option->name }}
                            </span>

                        </div>
                        {{-- Valores --}}

                        <div class="flex flex-wrap">

                            @foreach ($option->features as $feature)
                                @switch($option->type)
                                    @case(1)
                                        <span
                                            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">
                                            {{ $feature->description }}
                                        </span>
                                    @break

                                    @case(2)
                                        {{-- para obtener colores se obtiene el color guardado en value --}}
                                        <span class="inline-block w-6 h-6 mr-4 border-2 border-gray-300 rounded-full shadow-lg"
                                            style="background-color: {{ $feature->value }};">
                                        </span>
                                    @break

                                    @default
                                @endswitch
                            @endforeach

                        </div>

                    </div>
                    {{-- Fin Tarjeta de Opcion --}}
                @endforeach

            </div>

        </div>

    </section>

    {{-- Modal Section --}}

    <x-dialog-modal wire:model="openModal" title="Crear Nueva Opcion">
        <x-slot name="content">

            {{-- Nombres --}}
            <div class="grid grid-cols-2 gap-6 mb-4">

                <div>

                    <x-label class="mb-1" value="{{ __('Nombre') }}" />
                    <x-input class="w-full" wire:model.defer="newOption.name" placeholder="Tamano , color" />
                </div>

                <div>
                    <x-label class="mb-1" value="{{ __('Tipo') }}" />
                    <x-select wire:model.defer="newOption.type" class="w-full">
                        <option value="" disabled>Seleccione un tipo</option>
                        {{-- 1 = Texto, 2 = Color --}}
                        <option value="1">Texto</option>
                        <option value="2">Color</option>
                    </x-select>
                </div>

            </div>
            {{-- Fin Nombres --}}

            {{-- Valores --}}
            <div class="flex items-center mb-4">

                <hr class="flex-1 border-gray-300">

                <span class="mx-4">Valores</span>

                <hr class="flex-1 border-gray-300">

            </div>

            {{-- input de valores --}}
            <div class="mb-4 space-y-4">

                @foreach ($newOption['features'] as $index => $feature)
                    <div class="p-6 border border-gray-200 rounded-lg">

                        <div class="grid grid-cols-2 gap-6 mb-4">

                            <div>
                                <x-label class="mb-1" value="{{ __('Valor') }}" />
                                <x-input class="w-full" placeholder="Ingrese el valor de la opcion"
                                    wire:model.defer="newOption.features.{{ $index }}.value" />
                            </div>

                            <div>
                                <x-label class="mb-1" value="{{ __('Descripción') }}" />
                                <x-input class="w-full" placeholder="Ingrese una descripcion"
                                    wire:model.defer="newOption.features.{{ $index }}.description" />
                            </div>

                        </div>

                        <div class="flex justify-end">
                            <x-button wire:click="addFeature" name="Agregar Valor" />
                        </div>
                    </div>
                @endforeach

            </div>

            {{-- Button feature --}}
            <div class="flex justify-end">
                <x-button wire:click="addFeature" name="Agregar Valor" />
            </div>

        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    {{-- Fin Modal Section --}}

</div>
