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
        'route' => route('admin.office-addresses.show', $officeAddress),
    ],
    [
        'name' => 'Editar',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.office-addresses.show', $officeAddress) }}" type="secondary" name="Volver" />
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
                Editar Oficina
            </h1>
            <p class="text-gray-600 text-lg">Modifica la información de {{ $officeAddress->name }}</p>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <div class="glass-effect rounded-3xl shadow-2xl p-8 relative overflow-hidden">
                <!-- Decorative gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

                <div class="relative">
                    <form method="POST" action="{{ route('admin.office-addresses.update', $officeAddress) }}">
                        @csrf
                        @method('PUT')

                        <!-- Icon Header -->
                        <div class="text-center mb-8">
                            <div
                                class="w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fas fa-building text-white text-3xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Información de la Oficina</h2>
                            <p class="text-gray-600">Actualiza los datos de la oficina</p>
                        </div>

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl" />

                        <!-- Form Fields -->
                        <div class="space-y-8">
                            <!-- Información básica -->
                            <div
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                                    Información Básica
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-label for="name"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Nombre de la oficina *" />
                                        <x-input type="text" name="name" id="name"
                                            value="{{ old('name', $officeAddress->name) }}" required
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                            placeholder="Ej: Oficina Quito Centro" />
                                        @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-label for="phone"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Teléfono" />
                                        <x-input type="text" name="phone" id="phone"
                                            value="{{ old('phone', $officeAddress->phone) }}"
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                            placeholder="Ej: 098-123-4567" />
                                        @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <x-label for="email"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Email" />
                                        <x-input type="email" name="email" id="email"
                                            value="{{ old('email', $officeAddress->email) }}"
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                            placeholder="oficina@empresa.com" />
                                        @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <x-label for="working_hours"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Horarios de atención" />
                                        <x-input type="text" name="working_hours" id="working_hours"
                                            value="{{ old('working_hours', $officeAddress->working_hours) }}"
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                            placeholder="Lun-Vie 9:00-18:00" />
                                        @error('working_hours')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Dirección -->
                            <div
                                class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-3 text-green-500"></i>
                                    Dirección
                                </h3>
                                <div class="space-y-6">
                                    <div>
                                        <x-label for="address"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Dirección *" />
                                        <x-input type="text" name="address" id="address"
                                            value="{{ old('address', $officeAddress->address) }}" required
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                            placeholder="Ej: Av. Amazonas N24-03 y Colón" />
                                        @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <x-label for="province"
                                                class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                                value="Provincia *" />
                                            <select name="province" id="province" required
                                                class="w-full py-3 px-4 border border-gray-300 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Seleccionar provincia</option>
                                                <option value="Pichincha" {{ old('province', $officeAddress->province)
                                                    == 'Pichincha' ? 'selected' : '' }}>Pichincha</option>
                                                <option value="Manabí" {{ old('province', $officeAddress->province) ==
                                                    'Manabí' ? 'selected' : '' }}>Manabí</option>
                                                <option value="Guayas" {{ old('province', $officeAddress->province) ==
                                                    'Guayas' ? 'selected' : '' }}>Guayas</option>
                                                <option value="Azuay" {{ old('province', $officeAddress->province) ==
                                                    'Azuay' ? 'selected' : '' }}>Azuay</option>
                                            </select>
                                            @error('province')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <x-label for="canton"
                                                class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                                value="Cantón *" />
                                            <x-input type="text" name="canton" id="canton"
                                                value="{{ old('canton', $officeAddress->canton) }}" required
                                                class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                                placeholder="Ej: Quito" />
                                            @error('canton')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <x-label for="parish"
                                                class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                                value="Parroquia *" />
                                            <x-input type="text" name="parish" id="parish"
                                                value="{{ old('parish', $officeAddress->parish) }}" required
                                                class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                                placeholder="Ej: Centro Histórico" />
                                            @error('parish')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <x-label for="reference"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Referencia" />
                                        <x-input type="text" name="reference" id="reference"
                                            value="{{ old('reference', $officeAddress->reference) }}"
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                            placeholder="Ej: Edificio Torre Amazonas, 3er piso" />
                                        @error('reference')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Coordenadas GPS -->
                            <div
                                class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-crosshairs mr-3 text-purple-500"></i>
                                    Coordenadas GPS
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-label for="latitude"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Latitud" />
                                        <x-input type="number" name="latitude" id="latitude"
                                            value="{{ old('latitude', $officeAddress->coordinates['lat'] ?? '') }}"
                                            step="any"
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                            placeholder="Ej: -0.1865938" />
                                        @error('latitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-label for="longitude"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Longitud" />
                                        <x-input type="number" name="longitude" id="longitude"
                                            value="{{ old('longitude', $officeAddress->coordinates['lng'] ?? '') }}"
                                            step="any"
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3"
                                            placeholder="Ej: -78.4305382" />
                                        @error('longitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Notas y Opciones -->
                            <div
                                class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200">
                                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-cogs mr-3 text-amber-500"></i>
                                    Configuración Adicional
                                </h3>
                                <div class="space-y-6">
                                    <div>
                                        <x-label for="notes"
                                            class="text-slate-700 font-semibold flex items-center text-base mb-2"
                                            value="Notas adicionales" />
                                        <textarea name="notes" id="notes" rows="3"
                                            class="w-full border-gray-300 focus:border-indigo-400 focus:ring-indigo-200 rounded-xl bg-white transition-all duration-300 hover:shadow-md focus:shadow-lg py-3 px-4"
                                            placeholder="Información adicional sobre la oficina...">{{ old('notes', $officeAddress->notes) }}</textarea>
                                        @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex flex-wrap gap-6">
                                        <div class="flex items-center">
                                            <input id="is_main" name="is_main" type="checkbox" value="1" {{
                                                old('is_main', $officeAddress->is_main) ? 'checked' : '' }}
                                            class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300
                                            rounded">
                                            <label for="is_main" class="ml-3 block text-base font-medium text-gray-700">
                                                Oficina principal
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input id="is_active" name="is_active" type="checkbox" value="1" {{
                                                old('is_active', $officeAddress->is_active) ? 'checked' : '' }}
                                            class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300
                                            rounded">
                                            <label for="is_active"
                                                class="ml-3 block text-base font-medium text-gray-700">
                                                Activa
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-200">
                            <a href="{{ route('admin.office-addresses.show', $officeAddress) }}"
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 font-semibold transform hover:scale-105 text-white">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                            <button type="submit"
                                class="px-8 py-3 rounded-xl shadow-lg hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i>
                                <span>Guardar Cambios</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>