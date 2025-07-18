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
        <div class="flex gap-2">
            <x-link href="{{ route('admin.office-addresses.edit', $officeAddress) }}" type="primary" name="Editar" />
            <x-link href="{{ route('admin.office-addresses.index') }}" type="secondary" name="Volver" />
        </div>
    </x-slot>

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
            <h1
                class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                {{ $officeAddress->name }}
            </h1>
            <p class="text-gray-600 text-lg">Información detallada de la oficina</p>
            @if($officeAddress->is_main)
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 mt-2">
                <i class="fas fa-star mr-1"></i>
                Oficina Principal
            </span>
            @endif
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <div class="glass-effect rounded-3xl shadow-2xl p-8 relative overflow-hidden">
                <!-- Decorative gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

                <div class="relative">
                    <!-- Status Badge -->
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $officeAddress->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                {{ $officeAddress->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Creada: {{ $officeAddress->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <!-- Information Sections -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Información Básica -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                                Información Básica
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Nombre</label>
                                    <p class="text-gray-800 font-medium">{{ $officeAddress->name }}</p>
                                </div>
                                @if($officeAddress->phone)
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Teléfono</label>
                                    <p class="text-gray-800">
                                        <i class="fas fa-phone mr-2 text-blue-500"></i>
                                        <a href="tel:{{ $officeAddress->phone }}" class="hover:text-blue-600">{{
                                            $officeAddress->phone }}</a>
                                    </p>
                                </div>
                                @endif
                                @if($officeAddress->email)
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Email</label>
                                    <p class="text-gray-800">
                                        <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                        <a href="mailto:{{ $officeAddress->email }}" class="hover:text-blue-600">{{
                                            $officeAddress->email }}</a>
                                    </p>
                                </div>
                                @endif
                                @if($officeAddress->working_hours)
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Horarios de Atención</label>
                                    <p class="text-gray-800">
                                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                                        {{ $officeAddress->working_hours }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-map-marker-alt mr-3 text-green-500"></i>
                                Dirección
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Dirección Completa</label>
                                    <p class="text-gray-800">{{ $officeAddress->full_address }}</p>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-600">Provincia</label>
                                        <p class="text-gray-800">{{ $officeAddress->province }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-600">Cantón</label>
                                        <p class="text-gray-800">{{ $officeAddress->canton }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-600">Parroquia</label>
                                        <p class="text-gray-800">{{ $officeAddress->parish }}</p>
                                    </div>
                                </div>
                                @if($officeAddress->reference)
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Referencia</label>
                                    <p class="text-gray-800">{{ $officeAddress->reference }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Coordenadas y Notas -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                        @if($officeAddress->coordinates)
                        <!-- Coordenadas -->
                        <div
                            class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-crosshairs mr-3 text-purple-500"></i>
                                Coordenadas GPS
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Latitud</label>
                                    <p class="text-gray-800 font-mono">{{ $officeAddress->coordinates['lat'] ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Longitud</label>
                                    <p class="text-gray-800 font-mono">{{ $officeAddress->coordinates['lng'] ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="pt-4">
                                    <a href="https://www.google.com/maps?q={{ $officeAddress->coordinates['lat'] }},{{ $officeAddress->coordinates['lng'] }}"
                                        target="_blank"
                                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Ver en Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($officeAddress->notes)
                        <!-- Notas -->
                        <div
                            class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-sticky-note mr-3 text-amber-500"></i>
                                Notas Adicionales
                            </h3>
                            <p class="text-gray-700 leading-relaxed">{{ $officeAddress->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-200">
                        <a href="{{ route('admin.office-addresses.edit', $officeAddress) }}"
                            class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Oficina
                        </a>

                        @if(!$officeAddress->is_main || \App\Models\OfficeAddress::count() > 1)
                        <form method="POST" action="{{ route('admin.office-addresses.destroy', $officeAddress) }}"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta oficina?')"
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold transition-all duration-300 transform hover:scale-105">
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