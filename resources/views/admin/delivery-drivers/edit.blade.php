<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Repartidores',
        'route' => route('admin.delivery-drivers.index'),
    ],
    [
        'name' => $deliveryDriver->name,
        'route' => route('admin.delivery-drivers.show', $deliveryDriver),
    ],
    [
        'name' => 'Editar',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.delivery-drivers.index', $deliveryDriver) }}" type="secondary" name="Volver" />
    </x-slot>

    <div class="relative min-h-screen overflow-x-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <!-- Decorative background elements (ajustados para móviles) -->
        <div
            class="absolute top-0 right-0 hidden w-16 h-16 max-w-full overflow-x-hidden translate-x-4 -translate-y-4 rounded-full xs:block bg-gradient-to-br from-indigo-200/20 to-purple-200/20 sm:w-32 sm:h-32 sm:translate-x-16 sm:-translate-y-16">
        </div>
        <div
            class="absolute bottom-0 left-0 hidden max-w-full overflow-x-hidden -translate-x-4 translate-y-4 rounded-full xs:block w-14 h-14 bg-gradient-to-tr from-blue-200/20 to-cyan-200/20 sm:w-24 sm:h-24 sm:-translate-x-12 sm:translate-y-12">
        </div>

        <!-- Header responsive -->
        <div class="px-1 pt-4 mb-4 text-center sm:px-4 sm:pt-8 sm:mb-8">
            <h1
                class="mb-2 text-lg font-bold text-transparent sm:text-4xl bg-gradient-to-r from-primary-900 to-secondary-500 bg-clip-text">
                Editar Repartidor
            </h1>
            <p class="text-xs text-gray-600 sm:text-lg">Modifica la información de {{ $deliveryDriver->name }}</p>
        </div>

        <!-- Main Content responsive -->
        <div class="w-full max-w-full px-1 mx-auto overflow-x-hidden sm:max-w-2xl lg:max-w-6xl sm:px-4">
            <div
                class="relative max-w-full p-1 overflow-hidden rounded-lg shadow-lg sm:p-8 sm:shadow-2xl glass-effect sm:rounded-3xl">
                <!-- Decorative gradient overlay -->
                <div class="absolute inset-0 pointer-events-none bg-gradient-to-br from-white/5 to-transparent"></div>

                <div class="relative">
                    <form method="POST" action="{{ route('admin.delivery-drivers.update', $deliveryDriver) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Icon Header responsive -->
                        <div class="mb-4 text-center sm:mb-8">
                            <div
                                class="flex items-center justify-center w-12 h-12 mx-auto mb-2 rounded-lg shadow-lg sm:w-20 sm:h-20 sm:mb-4 bg-gradient-to-r from-indigo-500 to-purple-600 sm:rounded-2xl">
                                <i class="text-lg text-white sm:text-3xl fas fa-user-tie"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800 sm:text-2xl">Información del Repartidor</h2>
                            <p class="text-xs text-gray-600 sm:text-base">Actualiza los datos del repartidor</p>
                        </div>

                        <!-- Validation Errors -->
                        <x-validation-errors
                            class="p-2 mb-3 border border-red-200 rounded-lg sm:p-4 sm:mb-6 bg-red-50 sm:rounded-xl" />

                        <!-- Profile Photo Section responsive -->
                        <div class="p-3 mb-4 text-center rounded-lg sm:p-6 sm:mb-8 bg-gray-50 sm:rounded-2xl">
                            <h3 class="mb-2 text-sm font-semibold text-gray-800 sm:mb-4 sm:text-lg">Foto de Perfil</h3>
                            <div class="flex flex-col items-center">
                                <div class="relative mb-2 sm:mb-4">
                                    @if($deliveryDriver->profile_photo)
                                    <img src="{{ $deliveryDriver->profile_photo_url }}"
                                        alt="{{ $deliveryDriver->name }}" id="preview-image"
                                        class="object-cover w-16 h-16 border-4 border-white rounded-full shadow-lg sm:w-24 sm:h-24">
                                    @else
                                    <div id="preview-image"
                                        class="flex items-center justify-center w-16 h-16 border-4 border-white rounded-full shadow-lg sm:w-24 sm:h-24 bg-gradient-to-r from-indigo-500 to-purple-600">
                                        <span class="text-lg font-bold text-white sm:text-2xl">{{
                                            substr($deliveryDriver->name, 0, 1) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="w-full max-w-xs mx-auto">
                                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                        class="block w-full text-xs text-gray-500 sm:text-sm file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 sm:file:mr-4 sm:file:py-2 sm:file:px-4 sm:file:text-sm"
                                        onchange="previewImage(this)">
                                    @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-4 sm:space-y-8">
                            <!-- Información Personal responsive -->
                            <div
                                class="p-3 border border-blue-200 rounded-lg sm:p-6 bg-gradient-to-r from-blue-50 to-indigo-50 sm:rounded-2xl">
                                <h3 class="flex items-center mb-3 text-base font-bold text-gray-800 sm:mb-6 sm:text-xl">
                                    <i class="mr-2 text-blue-500 sm:mr-3 fas fa-user"></i>
                                    Información Personal
                                </h3>
                                <div class="grid grid-cols-1 gap-3 sm:gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nombre
                                            completo *</label>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name', $deliveryDriver->name) }}" required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="identification_number"
                                            class="block mb-2 text-sm font-medium text-gray-700">Cédula de identidad
                                            *</label>
                                        <input type="text" name="identification_number" id="identification_number"
                                            value="{{ old('identification_number', $deliveryDriver->identification_number) }}"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('identification_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email
                                            *</label>
                                        <input type="email" name="email" id="email"
                                            value="{{ old('email', $deliveryDriver->email) }}" required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">Teléfono
                                            *</label>
                                        <input type="text" name="phone" id="phone"
                                            value="{{ old('phone', $deliveryDriver->phone) }}" required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="address"
                                            class="block mb-2 text-sm font-medium text-gray-700">Dirección</label>
                                        <textarea name="address" id="address" rows="3"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="Dirección completa del repartidor">{{ old('address', $deliveryDriver->address) }}</textarea>
                                        @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Vehículo responsive -->
                            <div
                                class="p-3 border border-green-200 rounded-lg sm:p-6 bg-gradient-to-r from-green-50 to-emerald-50 sm:rounded-2xl">
                                <h3 class="flex items-center mb-3 text-base font-bold text-gray-800 sm:mb-6 sm:text-xl">
                                    <i class="mr-2 text-green-500 sm:mr-3 fas fa-car"></i>
                                    Información del Vehículo
                                </h3>
                                <div class="grid grid-cols-1 gap-3 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label for="vehicle_type"
                                            class="block mb-2 text-sm font-medium text-gray-700">Tipo de vehículo
                                            *</label>
                                        <select name="vehicle_type" id="vehicle_type" required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                            <option value="">Seleccionar tipo</option>
                                            <option value="moto" {{ old('vehicle_type', $deliveryDriver->vehicle_type)
                                                == 'moto' ? 'selected' : '' }}>🏍️ Motocicleta</option>
                                            <option value="auto" {{ old('vehicle_type', $deliveryDriver->vehicle_type)
                                                == 'auto' ? 'selected' : '' }}>🚗 Automóvil</option>
                                            <option value="bicicleta" {{ old('vehicle_type', $deliveryDriver->
                                                vehicle_type) == 'bicicleta' ? 'selected' : '' }}>🚲 Bicicleta</option>
                                            <option value="camion" {{ old('vehicle_type', $deliveryDriver->vehicle_type)
                                                == 'camion' ? 'selected' : '' }}>🚛 Camión</option>
                                            <option value="furgoneta" {{ old('vehicle_type', $deliveryDriver->
                                                vehicle_type) == 'furgoneta' ? 'selected' : '' }}>🚐 Furgoneta</option>
                                        </select>
                                        @error('vehicle_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vehicle_plate"
                                            class="block mb-2 text-sm font-medium text-gray-700">Placa del
                                            vehículo</label>
                                        <input type="text" name="vehicle_plate" id="vehicle_plate"
                                            value="{{ old('vehicle_plate', $deliveryDriver->vehicle_plate) }}"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('vehicle_plate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="license_number"
                                            class="block mb-2 text-sm font-medium text-gray-700">Número de licencia
                                            *</label>
                                        <input type="text" name="license_number" id="license_number"
                                            value="{{ old('license_number', $deliveryDriver->license_number) }}"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('license_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Información de Trabajo responsive -->
                            <div
                                class="p-3 border border-purple-200 rounded-lg sm:p-6 bg-gradient-to-r from-purple-50 to-pink-50 sm:rounded-2xl">
                                <h3 class="flex items-center mb-3 text-base font-bold text-gray-800 sm:mb-6 sm:text-xl">
                                    <i class="mr-2 text-purple-500 sm:mr-3 fas fa-briefcase"></i>
                                    Información de Trabajo
                                </h3>
                                <div class="grid grid-cols-1 gap-3 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label for="delivery_fee" class="block mb-2 text-sm font-medium text-gray-700">
                                            Tarifa General (USD) *
                                            <span class="text-xs text-gray-500">(Deprecated - usar tarifas
                                                específicas)</span>
                                        </label>
                                        <input type="number" name="delivery_fee" id="delivery_fee"
                                            value="{{ old('delivery_fee', $deliveryDriver->delivery_fee) }}" step="0.01"
                                            min="0" required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('delivery_fee')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="intra_province_rate"
                                            class="block mb-2 text-sm font-medium text-gray-700">
                                            Tarifa Intra-Provincial (USD) *
                                            <span class="text-xs text-green-600">(Misma provincia)</span>
                                        </label>
                                        <input type="number" name="intra_province_rate" id="intra_province_rate"
                                            value="{{ old('intra_province_rate', $deliveryDriver->intra_province_rate) }}"
                                            step="0.01" min="0" required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('intra_province_rate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="inter_province_rate"
                                            class="block mb-2 text-sm font-medium text-gray-700">
                                            Tarifa Inter-Provincial (USD) *
                                            <span class="text-xs text-blue-600">(Entre provincias)</span>
                                        </label>
                                        <input type="number" name="inter_province_rate" id="inter_province_rate"
                                            value="{{ old('inter_province_rate', $deliveryDriver->inter_province_rate) }}"
                                            step="0.01" min="0" required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('inter_province_rate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contacto de Emergencia responsive -->
                            <div
                                class="p-3 border border-red-200 rounded-lg sm:p-6 bg-gradient-to-r from-red-50 to-orange-50 sm:rounded-2xl">
                                <h3 class="flex items-center mb-3 text-base font-bold text-gray-800 sm:mb-6 sm:text-xl">
                                    <i class="mr-2 text-red-500 sm:mr-3 fas fa-phone-alt"></i>
                                    Contacto de Emergencia
                                </h3>
                                <div class="grid grid-cols-1 gap-3 sm:gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="emergency_contact_name"
                                            class="block mb-2 text-sm font-medium text-gray-700">Nombre completo</label>
                                        <input type="text" name="emergency_contact[name]" id="emergency_contact_name"
                                            value="{{ old('emergency_contact.name', $deliveryDriver->emergency_contact['name'] ?? '') }}"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('emergency_contact.name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="emergency_contact_phone"
                                            class="block mb-2 text-sm font-medium text-gray-700">Teléfono</label>
                                        <input type="text" name="emergency_contact[phone]" id="emergency_contact_phone"
                                            value="{{ old('emergency_contact.phone', $deliveryDriver->emergency_contact['phone'] ?? '') }}"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('emergency_contact.phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Estado responsive -->
                            <div
                                class="p-3 border rounded-lg sm:p-6 bg-gradient-to-r from-amber-50 to-yellow-50 sm:rounded-2xl border-amber-200">
                                <h3 class="flex items-center mb-3 text-base font-bold text-gray-800 sm:mb-6 sm:text-xl">
                                    <i class="mr-2 sm:mr-3 fas fa-cogs text-amber-500"></i>
                                    Estado
                                </h3>
                                <div class="flex items-center">
                                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active',
                                        $deliveryDriver->is_active) ? 'checked' : '' }}
                                    class="w-5 h-5 border-gray-300 rounded text-primary-600 focus:ring-primary-500">
                                    <label for="is_active"
                                        class="block ml-3 text-sm font-medium text-gray-700 sm:text-base">
                                        Repartidor activo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons responsive -->
                        <div
                            class="flex flex-col gap-2 pt-4 mt-4 border-t border-gray-200 sm:flex-row sm:justify-end sm:gap-4 sm:pt-8 sm:mt-8">
                            <a href="{{ route('admin.delivery-drivers.show', $deliveryDriver) }}"
                                class="w-full px-4 py-2 text-sm font-semibold text-center text-white transition-all duration-300 transform rounded-lg shadow-lg sm:w-auto sm:px-8 sm:py-3 sm:text-base sm:rounded-xl hover:shadow-xl bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 hover:scale-105">
                                <i class="mr-2 fas fa-times"></i>Cancelar
                            </a>
                            <button type="submit"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition-all duration-300 transform rounded-lg shadow-lg sm:w-auto sm:px-8 sm:py-3 sm:text-base sm:rounded-xl hover:shadow-xl bg-gradient-to-r from-primary-600 to-secondary-600 hover:from-primary-700 hover:to-secondary-700 hover:scale-105">
                                <i class="mr-2 fas fa-save"></i>
                                <span>Guardar Cambios</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview-image');
                    preview.innerHTML = `<img src="${e.target.result}" class="object-cover w-16 h-16 border-4 border-white rounded-full shadow-lg sm:w-24 sm:h-24">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</x-admin-layout>