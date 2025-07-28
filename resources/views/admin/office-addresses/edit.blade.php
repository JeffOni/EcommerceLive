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

    <div class="min-h-screen overflow-x-hidden bg-gray-50">
        <div class="w-full max-w-sm px-3 py-4 mx-auto sm:max-w-2xl sm:px-6 lg:max-w-3xl lg:px-8">
            <!-- Header -->
            <div class="mb-6 text-center">
                <h1 class="mb-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                    Editar Oficina
                </h1>
                <p class="text-sm text-gray-600 sm:text-base">
                    Modifica la información de {{ $officeAddress->name }}
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
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
                    <div class="px-4 py-5 sm:p-6 space-y-6">
                        <x-validation-errors class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg" />

                        <!-- Información básica -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center sm:text-xl">
                                <i class="fas fa-info-circle mr-2 text-blue-500 sm:mr-3"></i>
                                Información Básica
                            </h3>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <x-label for="name" class="block text-sm font-medium text-gray-700 mb-1"
                                        value="Nombre de la oficina *" />
                                    <x-input type="text" name="name" id="name"
                                        value="{{ old('name', $officeAddress->name) }}" required
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                        placeholder="Ej: Oficina Quito Centro" />
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <x-label for="phone" class="block text-sm font-medium text-gray-700 mb-1"
                                        value="Teléfono" />
                                    <x-input type="text" name="phone" id="phone"
                                        value="{{ old('phone', $officeAddress->phone) }}"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                        placeholder="Ej: 098-123-4567" />
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <x-label for="email" class="block text-sm font-medium text-gray-700 mb-1"
                                        value="Email" />
                                    <x-input type="email" name="email" id="email"
                                        value="{{ old('email', $officeAddress->email) }}"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                        placeholder="oficina@empresa.com" />
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <x-label for="working_hours" class="block text-sm font-medium text-gray-700 mb-1"
                                        value="Horarios de atención" />
                                    <x-input type="text" name="working_hours" id="working_hours"
                                        value="{{ old('working_hours', $officeAddress->working_hours) }}"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                        placeholder="Lun-Vie 9:00-18:00" />
                                    @error('working_hours')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center sm:text-xl">
                                <i class="fas fa-map-marker-alt mr-2 text-green-500 sm:mr-3"></i>
                                Ubicación
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <x-label for="address" class="block text-sm font-medium text-gray-700 mb-1"
                                        value="Dirección *" />
                                    <x-input type="text" name="address" id="address"
                                        value="{{ old('address', $officeAddress->address) }}" required
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                        placeholder="Ej: Av. Amazonas N24-03 y Colón" />
                                    @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div>
                                        <x-label for="province" class="block text-sm font-medium text-gray-700 mb-1"
                                            value="Provincia *" />
                                        <select name="province" id="province" required
                                            class="w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                            <option value="">Seleccionar provincia</option>
                                            <option value="Pichincha" {{ old('province', $officeAddress->province) ==
                                                'Pichincha' ? 'selected' : '' }}>Pichincha</option>
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
                                        <x-label for="canton" class="block text-sm font-medium text-gray-700 mb-1"
                                            value="Cantón *" />
                                        <x-input type="text" name="canton" id="canton"
                                            value="{{ old('canton', $officeAddress->canton) }}" required
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                            placeholder="Ej: Quito" />
                                        @error('canton')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-label for="parish" class="block text-sm font-medium text-gray-700 mb-1"
                                            value="Parroquia *" />
                                        <x-input type="text" name="parish" id="parish"
                                            value="{{ old('parish', $officeAddress->parish) }}" required
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                            placeholder="Ej: Centro Histórico" />
                                        @error('parish')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <x-label for="reference" class="block text-sm font-medium text-gray-700 mb-1"
                                        value="Referencia" />
                                    <x-input type="text" name="reference" id="reference"
                                        value="{{ old('reference', $officeAddress->reference) }}"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                        placeholder="Ej: Edificio Torre Amazonas, 3er piso" />
                                    @error('reference')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center sm:text-xl">
                                <i class="fas fa-cog mr-2 text-purple-500 sm:mr-3"></i>
                                Información Adicional
                            </h3>
                            <div class="space-y-4">
                                <!-- Coordenadas GPS -->
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <x-label for="latitude" class="block text-sm font-medium text-gray-700 mb-1"
                                            value="Latitud" />
                                        <x-input type="number" name="latitude" id="latitude"
                                            value="{{ old('latitude', $officeAddress->coordinates['lat'] ?? '') }}"
                                            step="any"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                            placeholder="Ej: -0.1865938" />
                                        @error('latitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-label for="longitude" class="block text-sm font-medium text-gray-700 mb-1"
                                            value="Longitud" />
                                        <x-input type="number" name="longitude" id="longitude"
                                            value="{{ old('longitude', $officeAddress->coordinates['lng'] ?? '') }}"
                                            step="any"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                            placeholder="Ej: -78.4305382" />
                                        @error('longitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Notas -->
                                <div>
                                    <x-label for="notes" class="block text-sm font-medium text-gray-700 mb-1"
                                        value="Notas adicionales" />
                                    <textarea name="notes" id="notes" rows="3"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md text-sm"
                                        placeholder="Información adicional sobre la oficina...">{{ old('notes', $officeAddress->notes) }}</textarea>
                                    @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Opciones -->
                                <div class="flex flex-col space-y-3 pt-2 sm:flex-row sm:space-y-0 sm:space-x-6">
                                    <div class="flex items-center">
                                        <input id="is_main" name="is_main" type="checkbox" value="1" {{ old('is_main',
                                            $officeAddress->is_main) ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_main" class="ml-2 block text-sm text-gray-900">
                                            <i class="fas fa-star mr-1 text-yellow-500"></i>
                                            Oficina principal
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="is_active" name="is_active" type="checkbox" value="1" {{
                                            old('is_active', $officeAddress->is_active) ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                            <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                            Activa
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 px-4 py-3 bg-gray-50 rounded-b-lg border-t border-gray-200">
                        <a href="{{ route('admin.office-addresses.show', $officeAddress) }}"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>