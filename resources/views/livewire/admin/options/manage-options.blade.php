<div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50">
    <!-- Header -->
    <div class="text-center mb-4 sm:mb-6 pt-4 sm:pt-6 px-3 sm:px-4">
        <h1
            class="text-lg sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-900 to-secondary-500 bg-clip-text text-transparent mb-2">
            Gestión de Opciones
        </h1>
        <p class="text-xs sm:text-sm text-secondary-600">Administra las opciones y características de tus productos</p>
    </div>

    <section
        class="max-w-5xl mx-3 sm:mx-4 lg:mx-auto glass-effect rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-5 lg:p-6 relative overflow-hidden">
        <!-- Decorative gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

        <header class="relative mb-4 sm:mb-6">
            <div class="flex flex-col space-y-3 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center shadow-lg flex-shrink-0">
                        <i class="fas fa-cogs text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-sm sm:text-lg font-bold text-primary-800 truncate">Opciones Disponibles</h2>
                        <p class="text-xs text-secondary-600 truncate">Configura las opciones para tus productos</p>
                    </div>
                </div>
                <button wire:click="$set('newOption.openModal', true)" type="button"
                    class="w-full sm:w-auto px-3 py-2 sm:px-4 sm:py-3 bg-gradient-to-r from-primary-900 to-secondary-500 hover:from-primary-800 hover:to-secondary-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                    <i class="fas fa-plus text-white"></i>
                    <span class="text-white text-xs sm:text-sm">Nueva Opción</span>
                </button>
            </div>
        </header>

        <div class="relative">
            <div class="space-y-3 sm:space-y-4">
                @foreach ($options as $option)
                {{-- Tarjeta de Opcion Mejorada --}}
                <div class="relative group bg-gradient-to-r from-white to-gray-50/50 border border-gray-200/60 rounded-lg sm:rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden"
                    wire:key="option-{{ $option->id }}">
                    <!-- Borde decorativo superior -->
                    <div
                        class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-secondary-500 to-primary-600">
                    </div>

                    <!-- Contenido de la tarjeta -->
                    <div class="p-3 sm:p-4">
                        <!-- Header de la tarjeta -->
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-primary-500 to-secondary-600 rounded-lg flex items-center justify-center shadow-lg flex-shrink-0">
                                    @if ($option->type == 1)
                                    <i class="fas fa-font text-white text-xs sm:text-sm"></i>
                                    @else
                                    <i class="fas fa-palette text-white text-xs sm:text-sm"></i>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="text-sm sm:text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors duration-300 truncate">
                                        {{ $option->name }}
                                    </h3>
                                    <p class="text-xs sm:text-sm text-gray-600 truncate">
                                        @if ($option->type == 1)
                                        Opción de texto
                                        @else
                                        Opción de color
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Botón eliminar opción -->
                            <button wire:click="deleteOption({{ $option->id }})" type="button"
                                class="p-1.5 sm:p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200 flex-shrink-0"
                                onclick="return confirm('¿Estás seguro de eliminar esta opción?')">
                                <i class="fas fa-trash text-xs sm:text-sm"></i>
                            </button>
                        </div>

                        <!-- Características de la opción -->
                        <div class="space-y-2 sm:space-y-3">
                            @if ($option->features->count() > 0)
                            <div class="flex flex-wrap gap-1.5 sm:gap-2">
                                @foreach ($option->features as $feature)
                                <div
                                    class="relative group/feature bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-md px-2 py-1 sm:px-3 sm:py-1.5 hover:shadow-sm transition-all duration-200">
                                    @if ($option->type == 1)
                                    <!-- Características de texto -->
                                    <div class="flex items-center space-x-1 sm:space-x-2">
                                        <span
                                            class="text-xs sm:text-sm font-medium text-gray-800 truncate max-w-16 sm:max-w-none">
                                            {{ $feature->value }}
                                        </span>
                                        <button wire:click="deleteFeature({{ $feature->id }})" type="button"
                                            class="opacity-0 group-hover/feature:opacity-100 p-0.5 text-red-500 hover:text-red-700 transition-all duration-200"
                                            onclick="return confirm('¿Eliminar esta característica?')">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    @else
                                    <!-- Características de color -->
                                    <div class="flex items-center space-x-1 sm:space-x-2">
                                        <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full border border-gray-300 shadow-sm flex-shrink-0"
                                            style="background-color: {{ $feature->value }};"></div>
                                        <span
                                            class="text-xs sm:text-sm font-medium text-gray-800 truncate max-w-16 sm:max-w-none">
                                            {{ $feature->description }}
                                        </span>
                                        <button wire:click="deleteFeature({{ $feature->id }})" type="button"
                                            class="opacity-0 group-hover/feature:opacity-100 p-0.5 text-red-500 hover:text-red-700 transition-all duration-200"
                                            onclick="return confirm('¿Eliminar esta característica?')">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            <!-- Componente para agregar nuevas características -->
                            <div class="border-t border-gray-200 pt-2 sm:pt-3">
                                <livewire:admin.options.add-new-feature :option="$option"
                                    :key="'add-feature-' . $option->id" />
                            </div>
                            @else
                            <div class="text-center py-4 sm:py-6">
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus-circle text-gray-400 text-sm sm:text-lg"></i>
                                </div>
                                <p class="text-gray-500 text-xs sm:text-sm mb-2 sm:mb-3">No hay características
                                    definidas</p>
                                <livewire:admin.options.add-new-feature :option="$option"
                                    :key="'add-feature-empty-' . $option->id" />
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if ($options->isEmpty())
            <div class="text-center py-8 sm:py-12">
                <div
                    class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 bg-gradient-to-r from-primary-100 to-secondary-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-cogs text-primary-500 text-lg sm:text-2xl"></i>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">No hay opciones disponibles</h3>
                <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">Crea tu primera opción para comenzar a
                    configurar las características de tus productos</p>
                <button wire:click="$set('newOption.openModal', true)" type="button"
                    class="px-4 py-2 sm:px-6 sm:py-3 bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="text-xs sm:text-sm">Crear Primera Opción</span>
                </button>
            </div>
            @endif
        </div>
    </section>

    {{-- Modal DEFINITIVAMENTE ARREGLADO - Sin aria-hidden ni problemas de accesibilidad --}}
    <style>
        @media (max-width: 400px) {
            .modal-mobile-full {
                width: 100vw !important;
                min-width: 0 !important;
                left: 0 !important;
                right: 0 !important;
                border-radius: 0 !important;
                padding: 0 !important;
            }

            .modal-mobile-content {
                padding: 8px !important;
            }

            .modal-mobile-footer {
                flex-direction: column !important;
                gap: 8px !important;
            }
        }
    </style>
    <x-dialog-modal wire:model.defer="newOption.openModal" maxWidth="5xl">
        <x-slot name="title">
            <div class="flex items-center space-x-2 p-2 modal-mobile-content">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-600 rounded-lg flex items-center justify-center">
                    <i class="text-white fas fa-plus"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900">Nueva Opción</h2>
                    <p class="text-xs text-gray-600">Configura las opciones para tus productos</p>
                </div>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="p-2 modal-mobile-content">
                <form wire:submit.prevent="addOption">
                    <x-validation-errors class="mb-4" />

                    <!-- Información básica -->
                    <div class="bg-gray-50 rounded-lg p-2 mb-4">
                        <div class="grid grid-cols-1 gap-2">
                            <!-- Nombre -->
                            <div>
                                <x-label class="flex items-center text-xs font-semibold text-gray-700 mb-1"
                                    value="Nombre de la Opción">
                                    <i class="mr-1 fas fa-tag text-primary-500"></i>
                                </x-label>
                                <x-input
                                    class="w-full py-2 bg-white border-gray-300 rounded-lg focus:border-primary-400 focus:ring-primary-200"
                                    placeholder="Ej: Talla, Color, Material" wire:model.defer="newOption.name" />
                            </div>

                            <!-- Tipo -->
                            <div>
                                <x-label class="flex items-center text-xs font-semibold text-gray-700 mb-1"
                                    value="Tipo de Opción">
                                    <i class="mr-1 fas fa-cog text-secondary-500"></i>
                                </x-label>
                                <x-select wire:model.live="newOption.type"
                                    class="w-full py-2 bg-white border-gray-300 rounded-lg focus:border-primary-400 focus:ring-primary-200">
                                    <option value="1">Texto</option>
                                    <option value="2">Colores</option>
                                </x-select>
                            </div>
                        </div>
                    </div>

                    <!-- Características -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="flex items-center text-base font-semibold text-gray-800">
                                <i class="mr-1 fas fa-list text-primary-500"></i>
                                Características
                            </h3>
                            <span class="px-2 py-1 text-xs text-gray-500 bg-gray-100 rounded-full">
                                {{ count($newOption->features) }}
                            </span>
                        </div>

                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @foreach ($newOption->features as $index => $feature)
                            <div class="bg-white border border-gray-200 rounded-lg p-2">
                                <div class="flex space-x-2">
                                    <div class="flex-1 space-y-2">
                                        <!-- Valor -->
                                        <div>
                                            <x-label class="text-xs font-medium text-gray-700 mb-1" value="Valor" />
                                            @if ($newOption->type == 1)
                                            <x-input
                                                class="w-full border-gray-300 focus:border-primary-400 focus:ring-primary-200 rounded py-1"
                                                placeholder="Ej: Grande, Pequeño, Mediano"
                                                wire:model.defer="newOption.features.{{ $index }}.value" />
                                            @else
                                            <div class="flex items-center space-x-2">
                                                <input type="color"
                                                    wire:model.live="newOption.features.{{ $index }}.value"
                                                    class="w-8 h-8 border border-gray-300 rounded cursor-pointer">
                                                <span class="text-gray-600 text-xs">
                                                    {{ $feature['value'] ?: 'Seleccione color' }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Descripción -->
                                        <div>
                                            <x-label class="text-xs font-medium text-gray-700 mb-1"
                                                value="Descripción" />
                                            <x-input
                                                class="w-full border-gray-300 focus:border-primary-400 focus:ring-primary-200 rounded py-1"
                                                placeholder="Descripción clara del valor"
                                                wire:model.defer="newOption.features.{{ $index }}.description" />
                                        </div>
                                    </div>

                                    <!-- Eliminar -->
                                    @if (count($newOption->features) > 1)
                                    <button type="button" wire:click="removeFeature({{ $index }})"
                                        class="p-1 text-red-500 hover:text-red-700 hover:bg-red-50 rounded">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Agregar característica -->
                        <button type="button" wire:click="addFeature"
                            class="w-full mt-2 py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-primary-400 hover:text-primary-600 flex items-center justify-center space-x-1">
                            <i class="fas fa-plus"></i>
                            <span class="font-medium text-xs">Agregar Característica</span>
                        </button>
                    </div>
                </form>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end space-x-2 p-2 border-t border-gray-200 bg-gray-50 modal-mobile-footer">
                <button type="button" wire:click="$set('newOption.openModal', false)"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-xs">
                    Cancelar
                </button>
                <button type="button" wire:click="addOption"
                    class="px-4 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 text-white font-semibold rounded-lg text-xs">
                    <i class="mr-1 fas fa-save"></i>
                    Crear Opción
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>