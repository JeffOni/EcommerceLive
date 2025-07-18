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
        'name' => 'Nuevo Repartidor',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.delivery-drivers.index') }}" type="secondary" name="Regresar"
            class="px-6 py-3 font-semibold text-white transition-all duration-300 transform bg-gray-500 shadow-lg hover:bg-gray-600 rounded-xl hover:shadow-xl hover:scale-105" />
    </x-slot>
    <!-- Fondo con gradiente y elementos decorativos -->
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-blue-200/30 to-purple-300/20 blur-3xl">
            </div>
            <div
                class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-200/30 to-blue-300/20 blur-3xl">
            </div>
        </div>

        <div class="relative">
            <!-- Contenedor principal -->
            <div class="mx-4 my-8 overflow-hidden shadow-2xl glass-effect rounded-3xl">
                <!-- Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-orange-600 to-red-600">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 glass-effect rounded-xl">
                            <i class="text-xl text-white fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Nuevo Repartidor</h2>
                            <p class="text-sm text-orange-100">Agregar un nuevo miembro al equipo de delivery</p>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="p-8 bg-white">
                    <form action="{{ route('admin.delivery-drivers.store') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Foto de Perfil -->
                        <div class="p-6 rounded-lg bg-gray-50" x-data="{ imagePreview: null }">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-blue-600 fas fa-camera"></i>Foto de Perfil
                            </h3>
                            <div class="flex items-center space-x-6">
                                <div class="relative">
                                    <div
                                        class="w-24 h-24 overflow-hidden bg-gray-200 border-4 border-white rounded-full shadow-lg">
                                        <img x-show="imagePreview" :src="imagePreview"
                                            class="object-cover w-full h-full">
                                        <div x-show="!imagePreview"
                                            class="flex items-center justify-center w-full h-full">
                                            <i class="text-2xl text-gray-400 fas fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Subir foto de perfil
                                    </label>
                                    <input type="file" name="profile_photo" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        @change="
                                               const file = $event.target.files[0];
                                               if (file) {
                                                   const reader = new FileReader();
                                                   reader.onload = (e) => imagePreview = e.target.result;
                                                   reader.readAsDataURL(file);
                                               }
                                           ">
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG o GIF. Tamaño máximo 2MB.</p>
                                    @error('profile_photo')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información Personal -->
                        <div class="p-6 rounded-lg bg-gray-50">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-blue-500 fas fa-user"></i>
                                Información Personal
                            </h3>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                                        Nombre Completo *
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror">
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="identification_number"
                                        class="block mb-2 text-sm font-medium text-gray-700">
                                        Número de Identificación *
                                    </label>
                                    <input type="text" name="identification_number" id="identification_number"
                                        value="{{ old('identification_number') }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('identification_number') border-red-500 @enderror">
                                    @error('identification_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                                        Email *
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror">
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">
                                        Teléfono *
                                    </label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address" class="block mb-2 text-sm font-medium text-gray-700">
                                        Dirección
                                    </label>
                                    <textarea name="address" id="address" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                                    @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información del Vehículo -->
                        <div class="p-6 rounded-lg bg-gray-50">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-green-500 fas fa-truck"></i>
                                Información del Vehículo
                            </h3>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div>
                                    <label for="vehicle_type" class="block mb-2 text-sm font-medium text-gray-700">
                                        Tipo de Vehículo *
                                    </label>
                                    <select name="vehicle_type" id="vehicle_type" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('vehicle_type') border-red-500 @enderror">
                                        <option value="">Seleccionar...</option>
                                        <option value="moto" {{ old('vehicle_type')=='moto' ? 'selected' : '' }}>
                                            Moto</option>
                                        <option value="auto" {{ old('vehicle_type')=='auto' ? 'selected' : '' }}>
                                            Auto</option>
                                        <option value="bicicleta" {{ old('vehicle_type')=='bicicleta' ? 'selected' : ''
                                            }}>Bicicleta
                                        </option>
                                        <option value="camion" {{ old('vehicle_type')=='camion' ? 'selected' : '' }}>
                                            Camión</option>
                                        <option value="furgoneta" {{ old('vehicle_type')=='furgoneta' ? 'selected' : ''
                                            }}>Furgoneta
                                        </option>
                                    </select>
                                    @error('vehicle_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="vehicle_plate" class="block mb-2 text-sm font-medium text-gray-700">
                                        Placa del Vehículo
                                    </label>
                                    <input type="text" name="vehicle_plate" id="vehicle_plate"
                                        value="{{ old('vehicle_plate') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('vehicle_plate') border-red-500 @enderror">
                                    @error('vehicle_plate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="license_number" class="block mb-2 text-sm font-medium text-gray-700">
                                        Número de Licencia *
                                    </label>
                                    <input type="text" name="license_number" id="license_number"
                                        value="{{ old('license_number') }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('license_number') border-red-500 @enderror">
                                    @error('license_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información de Trabajo -->
                        <div class="p-6 rounded-lg bg-gray-50">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-purple-500 fas fa-briefcase"></i>
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
                                        value="{{ old('delivery_fee', '5.00') }}" step="0.01" min="0" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('delivery_fee') border-red-500 @enderror">
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
                                        value="{{ old('intra_province_rate', '3.00') }}" step="0.01" min="0" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('intra_province_rate') border-red-500 @enderror">
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
                                        value="{{ old('inter_province_rate', '5.00') }}" step="0.01" min="0" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('inter_province_rate') border-red-500 @enderror">
                                    @error('inter_province_rate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contacto de Emergencia -->
                        <div class="p-6 rounded-lg bg-gray-50">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-red-500 fas fa-phone-alt"></i>
                                Contacto de Emergencia
                            </h3>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="emergency_contact_name"
                                        class="block mb-2 text-sm font-medium text-gray-700">
                                        Nombre del Contacto
                                    </label>
                                    <input type="text" name="emergency_contact[name]" id="emergency_contact_name"
                                        value="{{ old('emergency_contact.name') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>

                                <div>
                                    <label for="emergency_contact_phone"
                                        class="block mb-2 text-sm font-medium text-gray-700">
                                        Teléfono del Contacto
                                    </label>
                                    <input type="text" name="emergency_contact[phone]" id="emergency_contact_phone"
                                        value="{{ old('emergency_contact.phone') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end pt-6 space-x-4 border-t border-gray-200">
                            <a href="{{ route('admin.delivery-drivers.index') }}"
                                class="px-6 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-6 py-2 font-medium text-white bg-orange-600 border border-transparent rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <i class="mr-2 fas fa-save"></i>
                                Guardar Repartidor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('css')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
    @endpush

</x-admin-layout>