<div>
    <section class="overflow-hidden bg-white rounded-lg shadow">
        <header class="px-4 py-2 bg-gray-900">
            <h2 class="text-lg font-semibold text-white">Direcciones de Envío</h2>
            <p class="mt-1 text-sm text-gray-100">Administra tus direcciones de envío.</p>
        </header>

        <div class="p-4">
            {{-- Mensajes de éxito y error --}}
            @if (session()->has('message'))
                <div class="p-4 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 mb-4 text-red-700 bg-red-100 border border-red-300 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if ($newAddress)
                {{-- Formulario para nueva dirección --}}
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Nueva Dirección</h3>

                    <form wire:submit.prevent="saveAddress" class="space-y-4">
                        {{-- Primera fila: Tipo de dirección y Dirección específica --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo de dirección</label>
                                <select wire:model="createAddress.type"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecciona un tipo</option>
                                    <option value="1">Casa</option>
                                    <option value="2">Trabajo</option>
                                    <option value="3">Otro</option>
                                </select>
                                @error('createAddress.type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Dirección específica</label>
                                <input wire:model="createAddress.address" type="text"
                                    placeholder="Calle, número, edificio, etc."
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('createAddress.address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Segunda fila: Selects geográficos en cascada --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Provincia
                                    </span>
                                </label>
                                <select wire:model.live="createAddress.province_id"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                    <option value="">Selecciona una provincia</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('createAddress.province_id')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Cantón
                                    </span>
                                </label>
                                <select wire:model.live="createAddress.canton_id"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 {{ empty($cantons) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    {{ empty($cantons) ? 'disabled' : '' }}>
                                    <option value="">
                                        {{ empty($cantons) ? 'Primero selecciona una provincia' : 'Selecciona un cantón' }}
                                    </option>
                                    @foreach ($cantons as $canton)
                                        <option value="{{ $canton->id }}">{{ $canton->name }}</option>
                                    @endforeach
                                </select>
                                @error('createAddress.canton_id')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Parroquia
                                    </span>
                                </label>
                                <select wire:model.live="createAddress.parish_id"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 {{ empty($parishes) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    {{ empty($parishes) ? 'disabled' : '' }}>
                                    <option value="">
                                        {{ empty($parishes) ? 'Primero selecciona un cantón' : 'Selecciona una parroquia' }}
                                    </option>
                                    @foreach ($parishes as $parish)
                                        <option value="{{ $parish->id }}">{{ $parish->name }}</option>
                                    @endforeach
                                </select>
                                @error('createAddress.parish_id')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Código Postal
                                    </span>
                                </label>
                                <div class="relative">
                                    <input wire:model="createAddress.postal_code" type="text"
                                        placeholder="{{ $suggestedPostalCode ? $suggestedPostalCode : 'Ej: 170101' }}"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 {{ $suggestedPostalCode ? 'pr-10' : '' }}"
                                        maxlength="6">

                                    @if ($suggestedPostalCode)
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" wire:click="useDefaultPostalCode"
                                                class="text-blue-500 hover:text-blue-700 focus:outline-none"
                                                title="Usar código postal sugerido">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                @if ($suggestedPostalCode && !$createAddress->postal_code)
                                    <p class="mt-1 text-xs text-blue-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Código sugerido: {{ $suggestedPostalCode }}
                                    </p>
                                @endif

                                @error('createAddress.postal_code')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tercera fila: Referencia --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Referencia (opcional)</label>
                            <input wire:model="createAddress.reference" type="text"
                                placeholder="Cerca de..., frente a..., etc."
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('createAddress.reference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cuarta fila: Tipo de receptor --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">¿Quién recibirá el
                                    pedido?</label>
                                <select wire:model.live="createAddress.receiver"
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="1">Yo mismo</option>
                                    <option value="2">Otra persona</option>
                                </select>
                                @error('createAddress.receiver')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Información del receptor si es tercero --}}
                            @if ($createAddress->receiver === 2)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre del receptor</label>
                                    <input wire:model="createAddress.receiver_info.name" type="text"
                                        placeholder="Nombre completo"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('createAddress.receiver_info.name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        {{-- Información adicional del receptor tercero --}}
                        @if ($createAddress->receiver === 'tercero')
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Teléfono del
                                        receptor</label>
                                    <input wire:model="createAddress.receiver_info.phone" type="text"
                                        placeholder="0999999999"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('createAddress.receiver_info.phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cédula del receptor
                                        (opcional)</label>
                                    <input wire:model="createAddress.receiver_info.identification" type="text"
                                        placeholder="1234567890"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('createAddress.receiver_info.identification')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        {{-- Checkbox para dirección predeterminada --}}
                        <div class="flex items-center">
                            <input wire:model="createAddress.default" type="checkbox" id="default"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="default" class="ml-2 text-sm text-gray-700">
                                Establecer como dirección predeterminada
                            </label>
                        </div>

                        {{-- Botones --}}
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="cancelNewAddress"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Guardar Dirección
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- Lista de direcciones existentes --}}
                @if ($addresses->count())
                    <div class="space-y-4">
                        @foreach ($addresses as $address)
                            <div
                                class="p-4 border border-gray-200 rounded-lg {{ $address->default ? 'bg-blue-50 border-blue-300' : 'bg-white' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <h3 class="text-lg font-medium text-gray-900">
                                                {{ ucfirst($address->type) }}
                                                @if ($address->default)
                                                    <span
                                                        class="ml-2 px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                                        Predeterminada
                                                    </span>
                                                @endif
                                            </h3>
                                        </div>

                                        <div class="mt-2 text-sm text-gray-600">
                                            <p class="font-medium">{{ $address->address }}</p>
                                            <p>{{ $address->full_address }}</p>
                                            @if ($address->reference)
                                                <p class="text-gray-500">Ref: {{ $address->reference }}</p>
                                            @endif
                                            @if ($address->receiver === 'tercero' && $address->receiver_info)
                                                @php
                                                    $receiverInfo = json_decode($address->receiver_info, true);
                                                @endphp
                                                <p class="text-gray-500">
                                                    Receptor: {{ $receiverInfo['name'] ?? 'N/A' }}
                                                    @if (isset($receiverInfo['phone']))
                                                        - Tel: {{ $receiverInfo['phone'] }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex space-x-2 ml-4">
                                        @if (!$address->default)
                                            <button wire:click="setAsDefault({{ $address->id }})"
                                                class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                Predeterminada
                                            </button>
                                        @endif

                                        <button wire:click="deleteAddress({{ $address->id }})"
                                            wire:confirm="¿Estás seguro de que quieres eliminar esta dirección?"
                                            class="px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes direcciones registradas</h3>
                        <p class="mt-1 text-sm text-gray-500">Agrega tu primera dirección de envío para continuar.</p>
                    </div>
                @endif

                {{-- Botón para agregar nueva dirección --}}
                <div class="mt-6">
                    <button wire:click="openNewAddress"
                        class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Agregar Nueva Dirección
                    </button>
                </div>
            @endif
        </div>
    </section>
</div>
