<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Oficinas',
        'route' => route('admin.office-addresses.index'),
    ],
    [
        'name' => $officeAddress->name,
    ],
]">

    <x-slot name="action">
        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
            <x-link href="{{ route('admin.office-addresses.edit', $officeAddress) }}" type="primary" name="Editar" />
            <x-link href="{{ route('admin.office-addresses.index') }}" type="secondary" name="Volver" />
        </div>
    </x-slot>

    <div class="min-h-screen overflow-x-hidden bg-gray-50">
        <div class="w-full max-w-sm px-3 py-4 mx-auto sm:max-w-2xl sm:px-6 lg:max-w-4xl lg:px-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2 sm:text-3xl lg:text-4xl">
                    {{ $officeAddress->name }}
                </h1>
                <p class="text-sm text-gray-600 sm:text-base lg:text-lg">Información detallada de la oficina</p>
                <div class="flex justify-center space-x-2 mt-3">
                    @if($officeAddress->is_main)
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 sm:px-3 sm:text-sm">
                        <i class="fas fa-star mr-1"></i>
                        Oficina Principal
                    </span>
                    @endif
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $officeAddress->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} sm:px-3 sm:text-sm">
                        <i class="fas fa-circle mr-1 text-xs"></i>
                        {{ $officeAddress->is_active ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-2 sm:text-sm">
                    Creada: {{ $officeAddress->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Information Sections -->
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Información Básica -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center sm:text-xl">
                                <i class="fas fa-info-circle mr-2 text-blue-500 sm:mr-3"></i>
                                Información Básica
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Nombre</label>
                                    <p class="text-gray-800 font-medium break-words">{{ $officeAddress->name }}</p>
                                </div>
                                @if($officeAddress->phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Teléfono</label>
                                    <p class="text-gray-800 flex items-center">
                                        <i class="fas fa-phone mr-2 text-blue-500 flex-shrink-0"></i>
                                        <a href="tel:{{ $officeAddress->phone }}"
                                            class="hover:text-blue-600 break-all">{{ $officeAddress->phone }}</a>
                                    </p>
                                </div>
                                @endif
                                @if($officeAddress->email)
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                    <p class="text-gray-800 flex items-center">
                                        <i class="fas fa-envelope mr-2 text-blue-500 flex-shrink-0"></i>
                                        <a href="mailto:{{ $officeAddress->email }}"
                                            class="hover:text-blue-600 break-all">{{ $officeAddress->email }}</a>
                                    </p>
                                </div>
                                @endif
                                @if($officeAddress->working_hours)
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Horarios de
                                        Atención</label>
                                    <p class="text-gray-800 flex items-start">
                                        <i class="fas fa-clock mr-2 text-blue-500 mt-0.5 flex-shrink-0"></i>
                                        <span class="break-words">{{ $officeAddress->working_hours }}</span>
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center sm:text-xl">
                                <i class="fas fa-map-marker-alt mr-2 text-green-500 sm:mr-3"></i>
                                Dirección
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Dirección
                                        Completa</label>
                                    <p class="text-gray-800 break-words">{{ $officeAddress->full_address }}</p>
                                </div>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Provincia</label>
                                        <p class="text-gray-800 break-words">{{ $officeAddress->province }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Cantón</label>
                                        <p class="text-gray-800 break-words">{{ $officeAddress->canton }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Parroquia</label>
                                        <p class="text-gray-800 break-words">{{ $officeAddress->parish }}</p>
                                    </div>
                                </div>
                                @if($officeAddress->reference)
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Referencia</label>
                                    <p class="text-gray-800 break-words">{{ $officeAddress->reference }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Coordenadas y Notas -->
                    <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
                        @if($officeAddress->coordinates)
                        <!-- Coordenadas -->
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center sm:text-xl">
                                <i class="fas fa-crosshairs mr-2 text-purple-500 sm:mr-3"></i>
                                Coordenadas GPS
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Latitud</label>
                                        <p class="text-gray-800 font-mono text-sm break-all">{{
                                            $officeAddress->coordinates['lat'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Longitud</label>
                                        <p class="text-gray-800 font-mono text-sm break-all">{{
                                            $officeAddress->coordinates['lng'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <a href="https://www.google.com/maps?q={{ $officeAddress->coordinates['lat'] }},{{ $officeAddress->coordinates['lng'] }}"
                                        target="_blank"
                                        class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors sm:px-4">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        <span class="hidden sm:inline">Ver en Google Maps</span>
                                        <span class="sm:hidden">Google Maps</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($officeAddress->notes)
                        <!-- Notas -->
                        <div
                            class="bg-amber-50 rounded-lg p-4 border border-amber-200 sm:p-6 {{ !$officeAddress->coordinates ? 'lg:col-span-2' : '' }}">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center sm:text-xl">
                                <i class="fas fa-sticky-note mr-2 text-amber-500 sm:mr-3"></i>
                                Notas Adicionales
                            </h3>
                            <p class="text-gray-700 leading-relaxed break-words">{{ $officeAddress->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex flex-col space-y-3 pt-6 mt-6 border-t border-gray-200 sm:flex-row sm:justify-end sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.office-addresses.edit', $officeAddress) }}"
                            class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors sm:px-6 sm:py-3">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Oficina
                        </a>

                        @if(!$officeAddress->is_main || \App\Models\OfficeAddress::count() > 1)
                        <form method="POST" action="{{ route('admin.office-addresses.destroy', $officeAddress) }}"
                            class="w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta oficina?')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors sm:px-6 sm:py-3">
                                <i class="fas fa-trash mr-2"></i>
                                Eliminar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>