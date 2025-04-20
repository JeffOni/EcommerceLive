<div>
    {{-- Do your work, then step back. --}}
    <form wire:submit="addFeature" class="flex flex-col space-y-2 lg:space-x-4 lg:flex-row lg:space-y-0">

        <div class="flex-1">

            <x-label class="mb-1" value="{{ __('Valor') }}" />


            @switch($option->type)
                @case(1)
                    <x-input class="w-full" placeholder="Ingrese el valor de la opcion" wire:model.defer="newFeature.value" />
                @break

                @case(2)
                    {{-- para obtener colores se obtiene el color guardado en value --}}
                    {{-- el valor ternario ?: verifica si el valor extiste y si no esta null sino manda el mensaje si el valor es null --}}

                    <div class="border border-gray-300 h-[42px] px-3 rounded-md flex items-center justify-between">
                        {{ $newFeature['value'] ?: 'Seleccione Un Color' }}
                        <x-input type="color" wire:model.live="newFeature.value" />

                    </div>
                @break

                @default
            @endswitch

        </div>
        <div class="flex-1">

            <x-label class="mb-1" value="{{ __('Descripción') }}" />
            <x-input class="w-full" placeholder="Ingrese una descripcion" wire:model.defer="newFeature.description" />

        </div>

        <div class="flex items-end md:flex-none">

            <x-button name="Agregar" positive wire:loading.attr="disabled"
                wire:target="addFeature" wire:loading.class="opacity-50 cursor-not-allowed" />

        </div>


    </form>

</div>
