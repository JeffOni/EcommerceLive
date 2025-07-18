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
        'name' => 'Nueva Oficina',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.office-addresses.index') }}" type="secondary" name="Volver" />
    </x-slot>
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <form method="POST" action="{{ route('admin.office-addresses.store') }}">
                @csrf
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    <!-- Información básica -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre de la oficina
                                *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Ej: Oficina Quito Centro">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Ej: 098-123-4567">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Dirección *</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Ej: Av. Amazonas N24-03 y Colón">
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ubicación geográfica -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">Provincia *</label>
                            <select name="province" id="province" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Seleccionar provincia</option>
                                <option value="Pichincha" {{ old('province')=='Pichincha' ? 'selected' : '' }}>Pichincha
                                </option>
                                <option value="Manabí" {{ old('province')=='Manabí' ? 'selected' : '' }}>Manabí</option>
                                <option value="Guayas" {{ old('province')=='Guayas' ? 'selected' : '' }}>Guayas</option>
                                <option value="Azuay" {{ old('province')=='Azuay' ? 'selected' : '' }}>Azuay</option>
                            </select>
                            @error('province')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="canton" class="block text-sm font-medium text-gray-700">Cantón *</label>
                            <input type="text" name="canton" id="canton" value="{{ old('canton') }}" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Ej: Quito">
                            @error('canton')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="parish" class="block text-sm font-medium text-gray-700">Parroquia *</label>
                            <input type="text" name="parish" id="parish" value="{{ old('parish') }}" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Ej: Centro Histórico">
                            @error('parish')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Referencia y contacto -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="reference" class="block text-sm font-medium text-gray-700">Referencia</label>
                            <input type="text" name="reference" id="reference" value="{{ old('reference') }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Ej: Edificio Torre Amazonas, 3er piso">
                            @error('reference')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="oficina@empresa.com">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Horarios y coordenadas -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div>
                            <label for="working_hours" class="block text-sm font-medium text-gray-700">Horarios de
                                atención</label>
                            <input type="text" name="working_hours" id="working_hours"
                                value="{{ old('working_hours') }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Lun-Vie 9:00-18:00">
                            @error('working_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700">Latitud</label>
                            <input type="number" name="latitude" id="latitude" value="{{ old('latitude') }}" step="any"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Ej: -0.1865938">
                            @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700">Longitud</label>
                            <input type="number" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                step="any"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Ej: -78.4305382">
                            @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notas -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notas adicionales</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Información adicional sobre la oficina...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opciones -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input id="is_main" name="is_main" type="checkbox" value="1" {{ old('is_main') ? 'checked'
                                : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_main" class="ml-2 block text-sm text-gray-900">
                                Oficina principal
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true)
                                ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Activa
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Crear Oficina
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>