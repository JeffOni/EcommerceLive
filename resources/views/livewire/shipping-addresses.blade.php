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
                <div
                    class="bg-gradient-to-br from-gray-50 to-blue-50 border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="bg-white border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Nueva Dirección de Envío</h3>
                                <p class="text-sm text-gray-600">Completa la información para agregar una nueva
                                    dirección</p>
                            </div>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveAddress" class="p-6 space-y-6">
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

                        {{-- Notas especiales para entrega --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Notas especiales para entrega (opcional)
                                </span>
                            </label>
                            <textarea wire:model="createAddress.notes" rows="3"
                                placeholder="Ej: Timbre roto, llamar por teléfono, entregar en portería, etc."
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-none"></textarea>
                            @error('createAddress.notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cuarta fila: Tipo de receptor --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    ¿Quién recibirá el pedido?
                                </span>
                            </label>

                            {{-- Radio buttons estilizados --}}
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <label
                                    class="relative flex items-center p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-50 {{ $createAddress->receiver == 1 ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-300' }}">
                                    <div class="flex items-center">
                                        <input wire:model.live="createAddress.receiver" type="radio" value="1"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-2">🙋‍♂️</span>
                                                <div>
                                                    <div class="font-medium text-gray-900">Yo mismo</div>
                                                    <div class="text-sm text-gray-500">Recibiré personalmente el pedido
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="relative flex items-center p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-50 {{ $createAddress->receiver == 2 ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-300' }}">
                                    <div class="flex items-center">
                                        <input wire:model.live="createAddress.receiver" type="radio" value="2"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-2">👥</span>
                                                <div>
                                                    <div class="font-medium text-gray-900">Otra persona</div>
                                                    <div class="text-sm text-gray-500">Un tercero recibirá el pedido
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            @error('createAddress.receiver')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Información del receptor alternativo con transición --}}
                        @if ($createAddress->receiver == 2)
                            <div class="transition-all duration-300 ease-in-out">
                                <div
                                    class="p-6 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl shadow-sm">
                                    <div class="flex items-center mb-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="flex items-center justify-center w-10 h-10 bg-amber-100 rounded-full">
                                                <svg class="w-5 h-5 text-amber-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-lg font-semibold text-amber-900">Datos del receptor
                                                alternativo</h4>
                                            <p class="text-sm text-amber-700">Proporciona la información de la persona
                                                que recibirá tu pedido</p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        {{-- Primera fila: Nombre y Apellido --}}
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                            </path>
                                                        </svg>
                                                        Nombres *
                                                    </span>
                                                </label>
                                                <input wire:model="createAddress.receiver_name" type="text"
                                                    placeholder="Ej: Juan Carlos"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500 transition-colors duration-200">
                                                @error('createAddress.receiver_name')
                                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                            </path>
                                                        </svg>
                                                        Apellidos
                                                    </span>
                                                </label>
                                                <input wire:model="createAddress.receiver_last_name" type="text"
                                                    placeholder="Ej: Pérez González"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500 transition-colors duration-200">
                                                @error('createAddress.receiver_last_name')
                                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Segunda fila: Tipo de documento y número --}}
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                        Tipo de documento
                                                    </span>
                                                </label>
                                                <select wire:model="createAddress.receiver_document_type"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500 transition-colors duration-200">
                                                    <option value="">Seleccionar</option>
                                                    @foreach ($documentTypes as $type)
                                                        <option value="{{ $type->value }}">{{ $type->label() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('createAddress.receiver_document_type')
                                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                            </path>
                                                        </svg>
                                                        Número de documento
                                                    </span>
                                                </label>
                                                <input wire:model="createAddress.receiver_document_number"
                                                    type="text" placeholder="Ej: 1234567890"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500 transition-colors duration-200">
                                                @error('createAddress.receiver_document_number')
                                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Tercera fila: Email y teléfono --}}
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        Email
                                                    </span>
                                                </label>
                                                <input wire:model="createAddress.receiver_email" type="email"
                                                    placeholder="Ej: juan@example.com"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500 transition-colors duration-200">
                                                @error('createAddress.receiver_email')
                                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                            </path>
                                                        </svg>
                                                        Teléfono *
                                                    </span>
                                                </label>
                                                <input wire:model="createAddress.receiver_phone" type="tel"
                                                    placeholder="Ej: 0999999999"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500 transition-colors duration-200">
                                                @error('createAddress.receiver_phone')
                                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Nota informativa --}}
                                        <div class="bg-amber-100 border border-amber-300 rounded-lg p-3">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-5 h-5 text-amber-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-amber-800">
                                                        <strong>Importante:</strong> Asegúrate de que la persona que
                                                        recibirá el pedido esté disponible en la dirección indicada y
                                                        tenga un documento de identificación válido.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <button type="button" wire:click="cancelNewAddress"
                                class="flex-1 sm:flex-none order-2 sm:order-1 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancelar
                                </span>
                            </button>
                            <button type="submit"
                                class="flex-1 sm:flex-none order-1 sm:order-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Guardar Dirección
                                </span>
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
                                                @if ($address->type == 1)
                                                    🏠 Casa
                                                @elseif($address->type == 2)
                                                    🏢 Trabajo
                                                @else
                                                    📍 Otro
                                                @endif
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
                                            @if ($address->postal_code)
                                                <p class="text-gray-500">Código Postal: {{ $address->postal_code }}
                                                </p>
                                            @endif
                                            @if ($address->reference)
                                                <p class="text-gray-500">Ref: {{ $address->reference }}</p>
                                            @endif
                                            @if ($address->notes)
                                                <p class="text-gray-500">📝 Notas: {{ $address->notes }}</p>
                                            @endif

                                            {{-- Información del receptor --}}
                                            @if ($address->receiver === 2 && $address->receiver_info)
                                                @php
                                                    $receiverInfo = $address->receiver_info;
                                                @endphp
                                                <div
                                                    class="mt-3 p-3 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg">
                                                    <div class="flex items-center mb-2">
                                                        <span class="text-lg mr-2">👤</span>
                                                        <p class="text-amber-900 font-semibold text-sm">
                                                            Receptor alternativo
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-amber-800">
                                                        <div>
                                                            <strong>Nombre:</strong>
                                                            {{ trim(($receiverInfo['name'] ?? '') . ' ' . ($receiverInfo['last_name'] ?? '')) ?: 'N/A' }}
                                                        </div>
                                                        @if (!empty($receiverInfo['phone']))
                                                            <div>
                                                                <strong>Teléfono:</strong> {{ $receiverInfo['phone'] }}
                                                            </div>
                                                        @endif
                                                        @if (!empty($receiverInfo['email']))
                                                            <div>
                                                                <strong>Email:</strong> {{ $receiverInfo['email'] }}
                                                            </div>
                                                        @endif
                                                        @if (!empty($receiverInfo['document_type']) && !empty($receiverInfo['document_number']))
                                                            @php
                                                                $docTypeEnum = \App\Enums\TypeOfDocuments::tryFrom(
                                                                    $receiverInfo['document_type'],
                                                                );
                                                            @endphp
                                                            <div>
                                                                <strong>{{ $docTypeEnum ? $docTypeEnum->label() : 'Documento' }}:</strong>
                                                                {{ $receiverInfo['document_number'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
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
                <div class="mt-6 text-center">
                    <button wire:click="openNewAddress"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

@push('scripts')
    <script>
        window.addEventListener('swal', event => {
            const params = event.detail;
            Swal.fire(params);
        });
    </script>
@endpush
