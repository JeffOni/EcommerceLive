<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <section class="overflow-hidden bg-white rounded-lg shadow">
        <header class="px-4 py-2 bg-gray-900">
            <h2 class="text-lg font-semibold text-white">Direcciones de Envío</h2>
            <p class="mt-1 text-sm text-gray-100">Administra tus direcciones de envío.</p>
        </header>

        <div class="p-4">

            @if ($newAddress)
                <div class="grid grid-cols-4 gap-4">
                    {{-- Tipo de dirección --}}
                    <div class="col-span-1">
                        <x-select wire:model="createAddress.type" class="w-full">
                            <option value="" disabled>Selecciona un tipo</option>
                            <option value="1">Casa</option>
                            <option value="2">Trabajo</option>
                            <option value="3">Otro</option>
                        </x-select>
                    </div>

                    {{-- Nombre de la dirección --}}
                    <div class="col-span-3">
                        <x-input class="w-full" type="text" placeholder="Nombre de la dirección"
                            wire:model="createAddress.address" />
                        {{-- Province --}}
                    </div class="col-span-1">

                    {{-- Ciudad --}}

                    <div>

                    </div>
                </div>
            @else
                @if ($addresses->count())
                @else
                    <div class="text-center">
                        <p class="text-gray-500">No tienes direcciones de envío registradas.</p>
                    </div>
                @endif
                <button wire:click="$set('newAddress', true)"
                    class="flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Agregar Dirección <i class="ml-2 fas fa-plus"></i>
                </button>
            @endif
        </div>

    </section>
</div>
