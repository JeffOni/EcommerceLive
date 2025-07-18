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
        <x-link href="{{ route('admin.delivery-drivers.show', $deliveryDriver) }}" type="secondary" name="Volver" />
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <!-- Decorative background elements -->
        <div
            class="absolute top-0 right-0 w-32 h-32 translate-x-16 -translate-y-16 rounded-full bg-gradient-to-br from-indigo-200/20 to-purple-200/20">
        </div>
        <div
            class="absolute bottom-0 left-0 w-24 h-24 -translate-x-12 translate-y-12 rounded-full bg-gradient-to-tr from-blue-200/20 to-cyan-200/20">
        </div>

        <!-- Header -->
        <div class="pt-8 mb-8 text-center">
            <h1
                class="mb-2 text-4xl font-bold text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text">
                Editar Repartidor
            </h1>
            <p class="text-lg text-gray-600">Modifica la información de {{ $deliveryDriver->name }}</p>
        </div>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto">
            <div class="relative p-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Decorative gradient overlay -->
                <div class="absolute inset-0 pointer-events-none bg-gradient-to-br from-white/5 to-transparent"></div>

                <div class="relative">
                    <form method="POST" action="{{ route('admin.delivery-drivers.update', $deliveryDriver) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Icon Header -->
                        <div class="mb-8 text-center">
                            <div
                                class="flex items-center justify-center w-20 h-20 mx-auto mb-4 shadow-lg bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl">
                                <i class="text-3xl text-white fas fa-user-tie"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Información del Repartidor</h2>
                            <p class="text-gray-600">Actualiza los datos del repartidor</p>
                        </div>

                        <!-- Validation Errors -->
                        <x-validation-errors class="p-4 mb-6 border border-red-200 bg-red-50 rounded-xl" />

                        <!-- Profile Photo Section -->
                        <div class="p-6 mb-8 text-center bg-gray-50 rounded-2xl">
                            <h3 class="mb-4 text-lg font-semibold text-gray-800">Foto de Perfil</h3>
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4">
                                    @if($deliveryDriver->profile_photo)
                                    <img src="{{ $deliveryDriver->profile_photo_url }}"
                                        alt="{{ $deliveryDriver->name }}" id="preview-image"
                                        class="object-cover w-24 h-24 border-4 border-white rounded-full shadow-lg">
                                    @else
                                    <div id="preview-image"
                                        class="flex items-center justify-center w-24 h-24 border-4 border-white rounded-full shadow-lg bg-gradient-to-r from-indigo-500 to-purple-600">
                                        <span class="text-2xl font-bold text-white">{{ substr($deliveryDriver->name, 0,
                                            1) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="w-64">
                                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        onchange="previewImage(this)">
                                    @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-8">
                            <!-- Información Personal -->
                            <div
                                class="p-6 border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-800">
                                    <i class="mr-3 text-blue-500 fas fa-user"></i>
                                    Información Personal
                                </h3>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nombre
                                            completo *</label>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name', $deliveryDriver->name) }}" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
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
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('identification_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email
                                            *</label>
                                        <input type="email" name="email" id="email"
                                            value="{{ old('email', $deliveryDriver->email) }}" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">Teléfono
                                            *</label>
                                        <input type="text" name="phone" id="phone"
                                            value="{{ old('phone', $deliveryDriver->phone) }}" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="address"
                                            class="block mb-2 text-sm font-medium text-gray-700">Dirección</label>
                                        <textarea name="address" id="address" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                            placeholder="Dirección completa del repartidor">{{ old('address', $deliveryDriver->address) }}</textarea>
                                        @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Vehículo -->
                            <div
                                class="p-6 border border-green-200 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-800">
                                    <i class="mr-3 text-green-500 fas fa-car"></i>
                                    Información del Vehículo
                                </h3>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <div>
                                        <label for="vehicle_type"
                                            class="block mb-2 text-sm font-medium text-gray-700">Tipo de vehículo
                                            *</label>
                                        <select name="vehicle_type" id="vehicle_type" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            <option value="">Seleccionar tipo</option>
                                            <option value="moto" {{ old('vehicle_type', $deliveryDriver->vehicle_type)
                                                == 'moto' ? 'selected' : '' }}>Motocicleta</option>
                                            <option value="auto" {{ old('vehicle_type', $deliveryDriver->vehicle_type)
                                                == 'auto' ? 'selected' : '' }}>Automóvil</option>
                                            <option value="bicicleta" {{ old('vehicle_type', $deliveryDriver->
                                                vehicle_type) == 'bicicleta' ? 'selected' : '' }}>Bicicleta</option>
                                            <option value="camion" {{ old('vehicle_type', $deliveryDriver->vehicle_type)
                                                == 'camion' ? 'selected' : '' }}>Camión</option>
                                            <option value="furgoneta" {{ old('vehicle_type', $deliveryDriver->
                                                vehicle_type) == 'furgoneta' ? 'selected' : '' }}>Furgoneta</option>
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
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
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
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('license_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Información de Trabajo -->
                            <div
                                class="p-6 border border-purple-200 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-800">
                                    <i class="mr-3 text-purple-500 fas fa-briefcase"></i>
                                    Información de Trabajo
                                </h3>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <div>
                                        <label for="delivery_fee" class="block mb-2 text-sm font-medium text-gray-700">
                                            Tarifa General (USD) *
                                            <span class="text-xs text-gray-500">(Deprecated - usar tarifas
                                                específicas)</span>
                                        </label>
                                        <input type="number" name="delivery_fee" id="delivery_fee"
                                            value="{{ old('delivery_fee', $deliveryDriver->delivery_fee) }}" step="0.01"
                                            min="0" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
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
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
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
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('inter_province_rate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contacto de Emergencia -->
                            <div
                                class="p-6 border border-red-200 bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-800">
                                    <i class="mr-3 text-red-500 fas fa-phone-alt"></i>
                                    Contacto de Emergencia
                                </h3>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="emergency_contact_name"
                                            class="block mb-2 text-sm font-medium text-gray-700">Nombre completo</label>
                                        <input type="text" name="emergency_contact[name]" id="emergency_contact_name"
                                            value="{{ old('emergency_contact.name', $deliveryDriver->emergency_contact['name'] ?? '') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('emergency_contact.name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="emergency_contact_phone"
                                            class="block mb-2 text-sm font-medium text-gray-700">Teléfono</label>
                                        <input type="text" name="emergency_contact[phone]" id="emergency_contact_phone"
                                            value="{{ old('emergency_contact.phone', $deliveryDriver->emergency_contact['phone'] ?? '') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        @error('emergency_contact.phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div
                                class="p-6 border bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl border-amber-200">
                                <h3 class="flex items-center mb-6 text-xl font-bold text-gray-800">
                                    <i class="mr-3 fas fa-cogs text-amber-500"></i>
                                    Estado
                                </h3>
                                <div class="flex items-center">
                                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active',
                                        $deliveryDriver->is_active) ? 'checked' : '' }}
                                    class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="is_active" class="block ml-3 text-base font-medium text-gray-700">
                                        Repartidor activo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-200">
                            <a href="{{ route('admin.delivery-drivers.show', $deliveryDriver) }}"
                                class="px-8 py-3 font-semibold text-white transition-all duration-300 transform shadow-lg rounded-xl hover:shadow-xl bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 hover:scale-105">
                                <i class="mr-2 fas fa-times"></i>Cancelar
                            </a>
                            <button type="submit"
                                class="px-8 py-3 font-semibold text-white transition-all duration-300 transform shadow-lg rounded-xl hover:shadow-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 hover:scale-105">
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
                    preview.innerHTML = `<img src="${e.target.result}" class="object-cover w-24 h-24 border-4 border-white rounded-full shadow-lg">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</x-admin-layout>