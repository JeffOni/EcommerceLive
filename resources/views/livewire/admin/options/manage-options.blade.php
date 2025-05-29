<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <!-- Decorative background elements -->
    <div
        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-200/20 to-purple-200/20 rounded-full -translate-y-16 translate-x-16">
    </div>
    <div
        class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-200/20 to-cyan-200/20 rounded-full translate-y-12 -translate-x-12">
    </div>

    <!-- Header -->
    <div class="text-center mb-8 pt-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
            Gestión de Opciones
        </h1>
        <p class="text-gray-600 text-lg">Administra las opciones y características de tus productos</p>
    </div>

    <section
        class="max-w-6xl mx-auto bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 p-8 relative overflow-hidden">
        <!-- Decorative gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

        <header class="relative mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-cogs text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Opciones Disponibles</h2>
                        <p class="text-gray-600">Configura las opciones para tus productos</p>
                    </div>
                </div>
                <button wire:click="$set('newOption.openModal', true)" type="button"
                    class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                    <i class="fas fa-plus mr-2 text-white"></i>
                    <span class="text-white">Nueva Opción</span>
                </button>
            </div>
        </header>

        <div class="relative">

            <div class="grid gap-6">
                @foreach ($options as $option)
                    {{-- Tarjeta de Opcion Mejorada --}}
                    <div class="relative group bg-gradient-to-r from-white to-gray-50/50 border border-gray-200/60 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1 overflow-hidden"
                        wire:key="option-{{ $option->id }}">
                        <!-- Borde decorativo superior -->
                        <div
                            class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                        </div>

                        <!-- Efecto de hover -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-indigo-50/0 to-purple-50/0 group-hover:from-indigo-50/30 group-hover:to-purple-50/30 transition-all duration-500">
                        </div>

                        <div class="relative p-8">
                            {{-- Header de la Opción --}}
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        @if ($option->type == 1)
                                            <i class="fas fa-font text-white text-lg"></i>
                                        @else
                                            <i class="fas fa-palette text-white text-lg"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3
                                            class="text-xl font-bold text-gray-800 group-hover:text-indigo-600 transition-colors duration-300">
                                            {{ $option->name }}
                                        </h3>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r 
                                            @if ($option->type == 1) from-blue-100 to-indigo-100 text-indigo-700 @else from-purple-100 to-pink-100 text-purple-700 @endif">
                                            <i
                                                class="mr-1 @if ($option->type == 1) fas fa-text-width @else fas fa-palette @endif"></i>
                                            {{ $option->type == 1 ? 'Texto' : 'Color' }}
                                        </span>
                                    </div>
                                </div>

                                <button type="button" onclick="confirmDelete({{ $option->id }}, 'option')"
                                    class="group/btn p-3 bg-red-50 hover:bg-red-100 rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-6 shadow-md hover:shadow-lg"
                                    title="Eliminar opción">
                                    <i
                                        class="fas fa-trash-can text-red-500 group-hover/btn:text-red-600 transition-colors duration-300"></i>
                                </button>
                            </div>

                            {{-- Valores/Características --}}
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 mb-4">
                                    <div
                                        class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent flex-1">
                                    </div>
                                    <span
                                        class="text-sm font-semibold text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm">
                                        <i class="fas fa-list mr-1"></i>Características Disponibles
                                    </span>
                                    <div
                                        class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent flex-1">
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    @foreach ($option->features as $feature)
                                        @switch($option->type)
                                            @case(1)
                                                <div
                                                    class="group/feature relative inline-flex items-center bg-gradient-to-r from-slate-100 to-gray-100 hover:from-indigo-100 hover:to-purple-100 border border-gray-200 rounded-xl px-4 py-2 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                                                    <span
                                                        class="text-sm font-medium text-gray-700 group-hover/feature:text-indigo-700 mr-2">
                                                        {{ $feature->description }}
                                                    </span>
                                                    <button onclick="confirmDelete({{ $feature->id }}, 'feature')"
                                                        class="p-1 rounded-full bg-red-100 hover:bg-red-200 transition-all duration-200 transform hover:scale-110 opacity-70 group-hover/feature:opacity-100">
                                                        <i class="fas fa-xmark text-red-500 text-xs"></i>
                                                    </button>
                                                </div>
                                            @break

                                            @case(2)
                                                <div class="group/feature relative">
                                                    <div
                                                        class="flex items-center space-x-2 bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-xl px-3 py-2 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-105">
                                                        <div class="w-8 h-8 rounded-lg shadow-lg border-2 border-white ring-2 ring-gray-200 group-hover/feature:ring-indigo-300 transition-all duration-300"
                                                            style="background-color: {{ $feature->value }};"
                                                            title="{{ $feature->description }}"></div>
                                                        <span
                                                            class="text-sm font-medium text-gray-700">{{ $feature->description }}</span>
                                                        <button onclick="confirmDelete({{ $feature->id }}, 'feature')"
                                                            class="p-1 rounded-full bg-red-100 hover:bg-red-200 transition-all duration-200 transform hover:scale-110 opacity-70 group-hover/feature:opacity-100">
                                                            <i class="fas fa-xmark text-red-500 text-xs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @break

                                            @default
                                        @endswitch
                                    @endforeach
                                </div>

                                {{-- Componente de agregar nueva característica --}}
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    @livewire('admin.options.add-new-feature', ['option' => $option], key('add-new-feature-' . $option->id))
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Fin Tarjeta de Opcion --}}
                @endforeach
            </div>

        </div>
    </section>

    {{-- Modal Section Mejorado --}}
    <x-dialog-modal wire:model="newOption.openModal" title="">
        <x-slot name="content">
            <!-- Header del Modal -->
            <div class="text-center mb-8 pt-4">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-plus text-white text-2xl"></i>
                </div>
                <h2
                    class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    Nueva Opción
                </h2>
                <p class="text-gray-600">Configura una nueva opción para tus productos</p>
            </div>

            <x-validation-errors class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl" />

            {{-- Información Básica --}}
            <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-200">
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-info-circle text-indigo-500"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Información Básica</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <x-label class="text-slate-700 font-semibold flex items-center"
                            value="{{ __('Nombre de la Opción') }}">
                            <i class="fas fa-tag mr-2 text-indigo-500"></i>
                        </x-label>
                        <x-input
                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                            wire:model.defer="newOption.name" placeholder="Ej: Tamaño, Color, Material" />
                    </div>

                    <div class="space-y-3">
                        <x-label class="text-slate-700 font-semibold flex items-center"
                            value="{{ __('Tipo de Opción') }}">
                            <i class="fas fa-list mr-2 text-purple-500"></i>
                        </x-label>
                        <x-select wire:model.live="newOption.type"
                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3">
                            <option value="" disabled>Seleccione un tipo</option>
                            <option value="1">📝 Texto</option>
                            <option value="2">🎨 Color</option>
                        </x-select>
                    </div>
                </div>
            </div>

            {{-- Valores/Características --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center justify-center mb-6">
                    <div class="flex items-center space-x-2">
                        <div class="h-px bg-gradient-to-r from-transparent via-indigo-300 to-transparent flex-1 w-16">
                        </div>
                        <div
                            class="flex items-center space-x-2 bg-white px-4 py-2 rounded-full shadow-sm border border-indigo-200">
                            <i class="fas fa-list-ul text-indigo-500"></i>
                            <span class="font-semibold text-indigo-700">Valores de la Opción</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-indigo-300 to-transparent flex-1 w-16">
                        </div>
                    </div>
                </div>

                <div class="space-y-4 max-h-96 overflow-y-auto custom-scrollbar">
                    @foreach ($newOption->features as $index => $feature)
                        <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 group"
                            wire:key="feature-{{ $index }}">
                            <!-- Badge de número -->
                            <div
                                class="absolute -top-0 -left-0 w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                            </div>

                            <!-- Botón eliminar -->
                            <button type="button" wire:click="removeFeature({{ $index }})"
                                class="absolute -top-0 -right-0 w-6 h-6 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 transform hover:scale-110 opacity-0 group-hover:opacity-100">
                                <i class="fas fa-trash text-white text-sm"></i>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                                <div class="space-y-3">
                                    <x-label class="text-slate-700 font-semibold flex items-center"
                                        value="{{ __('Valor') }}">
                                        @if ($newOption->type == 1)
                                            <i class="fas fa-font mr-2 text-blue-500"></i>
                                        @else
                                            <i class="fas fa-palette mr-2 text-purple-500"></i>
                                        @endif
                                    </x-label>

                                    @switch($newOption->type)
                                        @case(1)
                                            <x-input
                                                class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                                placeholder="Ej: Grande, Mediano, Pequeño"
                                                wire:model.defer="newOption.features.{{ $index }}.value" />
                                        @break

                                        @case(2)
                                            <div class="relative">
                                                <div
                                                    class="border border-gray-300 rounded-xl h-12 px-4 flex items-center justify-between bg-white hover:shadow-md transition-all duration-300 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-200">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-8 h-8 rounded-lg border-2 border-gray-200 shadow-sm"
                                                            style="background-color: {{ $newOption->features[$index]['value'] ?? '#ffffff' }};">
                                                        </div>
                                                        <span class="text-gray-700">
                                                            {{ $newOption->features[$index]['value'] ?: 'Seleccione un color' }}
                                                        </span>
                                                    </div>
                                                    <x-input type="color"
                                                        wire:model.live="newOption.features.{{ $index }}.value"
                                                        class="w-8 h-8 border-none rounded-lg cursor-pointer" />
                                                </div>
                                            </div>
                                        @break

                                        @default
                                    @endswitch
                                </div>

                                <div class="space-y-3">
                                    <x-label class="text-slate-700 font-semibold flex items-center"
                                        value="{{ __('Descripción') }}">
                                        <i class="fas fa-align-left mr-2 text-green-500"></i>
                                    </x-label>
                                    <x-input
                                        class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                        placeholder="Descripción del valor"
                                        wire:model.defer="newOption.features.{{ $index }}.description" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-center mt-6">
                    <button wire:click="addFeature" type="button"
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                        <i class="fas fa-plus mr-2 text-white"></i>
                        <span class="text-white">Agregar Valor</span>
                    </button>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <button wire:click="$set('newOption.openModal', false)" type="button"
                    class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold transform hover:scale-105 flex items-center space-x-2">
                    <i class="fas fa-times mr-2 text-white"></i>
                    <span class="text-white">Cancelar</span>
                </button>
                <button wire:click="addOption" type="button"
                    class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                    <i class="fas fa-plus mr-2 text-white"></i>
                    <span class="text-white">Crear Opción</span>
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
        <script>
            let confirmDelete = (id, type) => {
                // Sweet Alert 2 con diseño mejorado
                Swal.fire({
                    title: "¿Estás Seguro?",
                    text: "Esta acción no se puede deshacer",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc2626",
                    cancelButtonColor: "#6b7280",
                    confirmButtonText: "Sí, Eliminar",
                    cancelButtonText: "Cancelar",
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl',
                        title: 'text-gray-800 font-bold',
                        confirmButton: 'rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300',
                        cancelButton: 'rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        switch (type) {
                            case 'feature':
                                @this.call('deleteFeature', id);
                                break;
                            case 'option':
                                @this.call('deleteOption', id);
                                break;
                            default:
                                break;
                        }

                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'El elemento ha sido eliminado exitosamente.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl'
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

    <!-- Estilos personalizados para el scrollbar -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #6366f1, #8b5cf6);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #4f46e5, #7c3aed);
        }
    </style>
</div>
