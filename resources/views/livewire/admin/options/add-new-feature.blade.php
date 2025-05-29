<div
    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-dashed border-indigo-200 hover:border-indigo-300 transition-all duration-300">
    <form wire:submit="addFeature" class="space-y-4">
        <!-- Header del formulario -->
        <div class="flex items-center space-x-2 mb-4">
            <div
                class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-sm">
                <i class="fas fa-plus text-white text-sm"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-800">Agregar Nueva Característica</h4>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
            <!-- Campo Valor -->
            <div class="space-y-2">
                <x-label class="text-slate-700 font-medium flex items-center" value="{{ __('Valor') }}">
                    @if ($option->type == 1)
                        <i class="fas fa-font mr-2 text-blue-500 text-sm"></i>
                    @else
                        <i class="fas fa-palette mr-2 text-purple-500 text-sm"></i>
                    @endif
                </x-label>

                @switch($option->type)
                    @case(1)
                        <x-input
                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-2.5"
                            placeholder="Ej: Grande, Rojo, Algodón" wire:model.defer="newFeature.value" />
                    @break

                    @case(2)
                        <div class="relative">
                            <div
                                class="border border-gray-300 rounded-xl h-11 px-3 flex items-center justify-between bg-white hover:shadow-md transition-all duration-300 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-200">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-lg border border-gray-200 shadow-sm"
                                        style="background-color: {{ $newFeature['value'] ?? '#ffffff' }};"></div>
                                    <span class="text-gray-700 text-sm">
                                        {{ $newFeature['value'] ?: 'Seleccione color' }}
                                    </span>
                                </div>
                                <x-input type="color" wire:model.live="newFeature.value"
                                    class="w-6 h-6 border-none rounded-lg cursor-pointer" />
                            </div>
                        </div>
                    @break

                    @default
                @endswitch
            </div>

            <!-- Campo Descripción -->
            <div class="space-y-2">
                <x-label class="text-slate-700 font-medium flex items-center" value="{{ __('Descripción') }}">
                    <i class="fas fa-align-left mr-2 text-green-500 text-sm"></i>
                </x-label>
                <x-input
                    class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-2.5"
                    placeholder="Descripción clara del valor" wire:model.defer="newFeature.description" />
            </div>

            <!-- Botón Agregar -->
            <div class="flex items-end">
                <x-button name="Agregar"
                    class="w-full lg:w-auto px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    wire:loading.attr="disabled" wire:target="addFeature"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <span wire:loading.remove wire:target="addFeature">
                        <i class="fas fa-plus mr-2"></i>Agregar
                    </span>
                    <span wire:loading wire:target="addFeature">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Agregando...
                    </span>
                </x-button>
            </div>
        </div>
    </form>
</div>
