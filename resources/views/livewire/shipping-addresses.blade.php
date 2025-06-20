{{-- 
    VISTA DE DIRECCIONES DE ENVÍO - COMPONENTE LIVEWIRE
    ===================================================
    
    Esta vista maneja la gestión completa de direcciones de envío con:
    
    CARACTERÍSTICAS PRINCIPALES:
    - Diseño moderno con gradientes y efectos glassmorphism (patrón del carrito)
    - Formularios para crear y editar direcciones
    - Lista de direcciones existentes con acciones
    - Selects en cascada para geografía ecuatoriana
    - Manejo de receptor alternativo
    - Estados de loading y validación
    
    ESTRUCTURA:
    1. Fondo decorativo con elementos glassmorphism
    2. Header principal con título e información
    3. Estados: Lista, Creación, Edición
    4. Formularios dinámicos con validación en tiempo real
    
    @version 2.0 - Mejorado con patrón visual consistente
--}}

<!-- Fondo moderno con gradiente y elementos decorativos -->
<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100">
    {{-- Elementos decorativos de fondo --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-indigo-400/20 to-purple-500/10 blur-3xl animate-pulse">
        </div>
        <div
            class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-400/20 to-pink-500/10 blur-3xl animate-pulse">
        </div>
        <div
            class="absolute w-32 h-32 rounded-full top-1/3 right-1/4 bg-gradient-to-r from-blue-300/30 to-indigo-400/20 blur-2xl">
        </div>
        <div
            class="absolute w-24 h-24 rounded-full bottom-1/3 left-1/4 bg-gradient-to-r from-emerald-300/30 to-blue-400/20 blur-xl">
        </div>
    </div>

    <div class="relative px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{-- Header principal --}}
        <div class="mb-12 text-center">
            <div
                class="inline-flex items-center justify-center w-16 h-16 mb-4 shadow-lg bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl">
                <i class="text-2xl text-white fas fa-map-marker-alt"></i>
            </div>
            <h1
                class="mb-3 text-5xl font-bold text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text">
                Direcciones de Envío
            </h1>
            <div
                class="inline-flex items-center px-4 py-2 border rounded-full shadow-md backdrop-blur-sm bg-white/70 border-white/40">
                <i class="mr-2 text-indigo-500 fas fa-home"></i>
                <span class="font-medium text-gray-700">
                    {{ $addresses->count() }} {{ $addresses->count() === 1 ? 'dirección' : 'direcciones' }} configuradas
                </span>
            </div>
        </div>

        {{-- Contenedor principal con glassmorphism --}}
        {{-- clases de glassmorphism echas con tailwind overflow-hidden border shadow-2xl backdrop-blur-sm bg-white/70 rounded-3xl border-white/20 --}}
        <div class="overflow-hidden border shadow-2xl glass-effect rounded-3xl border-white/20">
            {{-- Header con gradiente --}}
            <div class="px-8 py-6 bg-gradient-to-r from-indigo-600 to-purple-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <i class="text-xl text-white fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Gestión de Direcciones</h2>
                            <p class="text-sm text-indigo-100">Administra tus direcciones de envío de forma sencilla</p>
                        </div>
                    </div>
                    @if (!$newAddress && !$editingAddress)
                        <button wire:click="openNewAddress"
                            class="inline-flex items-center px-4 py-2 font-medium text-white transition-all duration-200 border bg-white/20 border-white/30 rounded-xl hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 backdrop-blur-sm">
                            <i class="mr-2 fas fa-plus"></i>
                            Nueva Dirección
                        </button>
                    @endif
                </div>
            </div>

            <div class="p-8">
                {{-- Mensajes de estado con diseño mejorado --}}
                @if (session()->has('message'))
                    <div
                        class="p-4 mb-6 border shadow-lg backdrop-blur-sm bg-emerald-50/80 border-emerald-200/50 rounded-2xl">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100">
                                    <i class="text-sm fas fa-check text-emerald-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium text-emerald-800">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="p-4 mb-6 border shadow-lg backdrop-blur-sm bg-red-50/80 border-red-200/50 rounded-2xl">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
                                    <i class="text-sm text-red-600 fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($newAddress)
                    {{-- Formulario para nueva dirección con diseño mejorado --}}
                    <div
                        class="overflow-hidden border shadow-xl backdrop-blur-sm bg-gradient-to-br from-emerald-50/50 to-blue-50/50 border-emerald-200/50 rounded-2xl">
                        <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-blue-500">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 rounded-lg bg-white/20 backdrop-blur-sm">
                                        <i class="text-lg text-white fas fa-plus"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">Nueva Dirección de Envío</h3>
                                        <p class="text-sm text-emerald-100">Completa la información para agregar una
                                            nueva dirección</p>
                                    </div>
                                </div>
                                <button wire:click="cancelNewAddress"
                                    class="p-2 transition-colors rounded-lg bg-white/20 hover:bg-white/30">
                                    <i class="text-white fas fa-times"></i>
                                </button>
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
                                        class="w-full mt-1 transition-colors duration-200 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Selecciona una provincia</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('createAddress.province_id')
                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
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
                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
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
                                        <p class="flex items-center mt-1 text-xs text-blue-600">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Código sugerido: {{ $suggestedPostalCode }}
                                        </p>
                                    @endif

                                    @error('createAddress.postal_code')
                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm resize-none focus:border-blue-500 focus:ring-blue-500"></textarea>
                                @error('createAddress.notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Cuarta fila: Tipo de receptor --}}
                            <div>
                                <label class="block mb-3 text-sm font-medium text-gray-700">
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
                                            <input wire:model.live="createAddress.receiver" type="radio"
                                                value="1"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <div class="ml-3">
                                                <div class="flex items-center">
                                                    <span class="mr-2 text-2xl">🙋‍♂️</span>
                                                    <div>
                                                        <div class="font-medium text-gray-900">Yo mismo</div>
                                                        <div class="text-sm text-gray-500">Recibiré personalmente el
                                                            pedido
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-50 {{ $createAddress->receiver == 2 ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-300' }}">
                                        <div class="flex items-center">
                                            <input wire:model.live="createAddress.receiver" type="radio"
                                                value="2"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <div class="ml-3">
                                                <div class="flex items-center">
                                                    <span class="mr-2 text-2xl">👥</span>
                                                    <div>
                                                        <div class="font-medium text-gray-900">Otra persona</div>
                                                        <div class="text-sm text-gray-500">Un tercero recibirá el
                                                            pedido
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                @error('createAddress.receiver')
                                    <p class="flex items-center mt-2 text-sm text-red-600">
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
                                        class="p-6 border shadow-sm bg-gradient-to-br from-amber-50 to-orange-50 border-amber-200 rounded-xl">
                                        <div class="flex items-center mb-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-amber-100">
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
                                                <p class="text-sm text-amber-700">Proporciona la información de la
                                                    persona
                                                    que recibirá tu pedido</p>
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            {{-- Primera fila: Nombre y Apellido --}}
                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">
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
                                                        class="w-full transition-colors duration-200 border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('createAddress.receiver_name')
                                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">
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
                                                    <input wire:model="createAddress.receiver_last_name"
                                                        type="text" placeholder="Ej: Pérez González"
                                                        class="w-full transition-colors duration-200 border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('createAddress.receiver_last_name')
                                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">
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
                                                        class="w-full transition-colors duration-200 border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                        <option value="">Seleccionar</option>
                                                        @foreach ($documentTypes as $type)
                                                            <option value="{{ $type->value }}">{{ $type->label() }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('createAddress.receiver_document_type')
                                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">
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
                                                        class="w-full transition-colors duration-200 border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('createAddress.receiver_document_number')
                                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">
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
                                                        class="w-full transition-colors duration-200 border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('createAddress.receiver_email')
                                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">
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
                                                        class="w-full transition-colors duration-200 border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('createAddress.receiver_phone')
                                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                            <div class="p-3 border rounded-lg bg-amber-100 border-amber-300">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="w-5 h-5 text-amber-400" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm text-amber-800">
                                                            <strong>Importante:</strong> Asegúrate de que la persona que
                                                            recibirá el pedido esté disponible en la dirección indicada
                                                            y
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

                            {{-- Botones con diseño mejorado --}}
                            <div class="flex flex-col gap-4 pt-8 border-t sm:flex-row border-white/20">
                                <button type="button" wire:click="cancelNewAddress"
                                    class="flex-1 order-2 px-6 py-3 text-sm font-medium text-gray-600 transition-all duration-200 border border-gray-200 shadow-lg sm:flex-none sm:order-1 bg-white/80 backdrop-blur-sm rounded-xl hover:bg-white hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    <span class="flex items-center justify-center">
                                        <i class="mr-2 fas fa-times"></i>
                                        Cancelar
                                    </span>
                                </button>
                                <button type="submit"
                                    class="flex-1 order-1 px-6 py-3 text-sm font-medium text-white transition-all duration-200 transform border border-transparent shadow-lg sm:flex-none sm:order-2 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-xl hover:from-emerald-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-emerald-400 hover:scale-105 hover:shadow-xl">
                                    <span class="flex items-center justify-center">
                                        <i class="mr-2 fas fa-save"></i>
                                        Guardar Dirección
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif ($editingAddress)
                    {{-- 
                =================================================================
                FORMULARIO DE EDICIÓN DE DIRECCIONES
                =================================================================
                
                Este formulario se muestra cuando $editingAddress es true.
                
                CARACTERÍSTICAS:
                ├── Usa wire:model="editAddress.*" (formulario EditAddressForm)
                ├── Colores amarillo/naranja para diferenciarlo del de creación
                ├── Misma estructura y campos que el formulario de creación
                ├── Botón "Actualizar Dirección" en lugar de "Guardar"
                └── Método wire:submit.prevent="updateAddress"
                
                DIFERENCIAS VISUALES CON EL FORMULARIO DE CREACIÓN:
                ├── Gradiente amarillo en lugar de azul
                ├── Ícono de edición (cruz) en lugar de ubicación
                ├── Título "Editar Dirección" 
                ├── Colores de focus yellow-500 en lugar de blue-500
                └── Botón gradient amarillo/naranja
                --}}
                    <div
                        class="overflow-hidden border border-yellow-200 shadow-sm bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl">
                        <div class="px-6 py-4 bg-white border-b border-yellow-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-yellow-100 rounded-full">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Editar Dirección de Envío</h3>
                                    <p class="text-sm text-gray-600">Modifica la información de tu dirección</p>
                                </div>
                            </div>
                        </div>

                        <form wire:submit.prevent="updateAddress" class="p-6 space-y-6">
                            {{-- Primera fila: Tipo de dirección y Dirección específica --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipo de dirección</label>
                                    <select wire:model="editAddress.type"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                        <option value="">Selecciona un tipo</option>
                                        <option value="1">Casa</option>
                                        <option value="2">Trabajo</option>
                                        <option value="3">Otro</option>
                                    </select>
                                    @error('editAddress.type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Dirección específica</label>
                                    <input wire:model="editAddress.address" type="text"
                                        placeholder="Calle, número, edificio, etc."
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                    @error('editAddress.address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Segunda fila: Selects geográficos en cascada --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Provincia
                                        </span>
                                    </label>
                                    <select wire:model.live="editAddress.province_id"
                                        class="w-full mt-1 transition-colors duration-200 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                        <option value="">Selecciona una provincia</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editAddress.province_id')
                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                            Cantón
                                        </span>
                                    </label>
                                    <select wire:model.live="editAddress.canton_id"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 transition-colors duration-200 {{ empty($cantons) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ empty($cantons) ? 'disabled' : '' }}>
                                        <option value="">
                                            {{ empty($cantons) ? 'Primero selecciona una provincia' : 'Selecciona un cantón' }}
                                        </option>
                                        @foreach ($cantons as $canton)
                                            <option value="{{ $canton->id }}">{{ $canton->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editAddress.canton_id')
                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Parroquia
                                        </span>
                                    </label>
                                    <select wire:model.live="editAddress.parish_id"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 transition-colors duration-200 {{ empty($parishes) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ empty($parishes) ? 'disabled' : '' }}>
                                        <option value="">
                                            {{ empty($parishes) ? 'Primero selecciona un cantón' : 'Selecciona una parroquia' }}
                                        </option>
                                        @foreach ($parishes as $parish)
                                            <option value="{{ $parish->id }}">{{ $parish->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editAddress.parish_id')
                                        <p class="flex items-center mt-1 text-sm text-red-600">
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
                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Código Postal
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input wire:model="editAddress.postal_code" type="text"
                                            placeholder="{{ $suggestedPostalCode ? $suggestedPostalCode : 'Ej: 170101' }}"
                                            class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 transition-colors duration-200 {{ $suggestedPostalCode ? 'pr-10' : '' }}"
                                            maxlength="6">

                                        @if ($suggestedPostalCode)
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <button type="button" wire:click="useDefaultPostalCodeEdit"
                                                    class="text-amber-500 hover:text-amber-700 focus:outline-none"
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

                                    @if ($suggestedPostalCode && !$editAddress->postal_code)
                                        <p class="flex items-center mt-1 text-xs text-amber-600">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Código sugerido: {{ $suggestedPostalCode }}
                                        </p>
                                    @endif

                                    @error('editAddress.postal_code')
                                        <p class="flex items-center mt-1 text-sm text-red-600">
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

                            {{-- Referencia y Notas --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Referencia
                                        (opcional)</label>
                                    <input wire:model="editAddress.reference" type="text"
                                        placeholder="Cerca de..., frente a..., etc."
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                    @error('editAddress.reference')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Notas especiales
                                        (opcional)</label>
                                    <input wire:model="editAddress.notes" type="text"
                                        placeholder="Ej: Timbre roto, llamar por teléfono..."
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                    @error('editAddress.notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tipo de receptor --}}
                            <div>
                                <label class="block mb-3 text-sm font-medium text-gray-700">
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

                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <label
                                        class="relative flex items-center p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-50 {{ $editAddress->receiver == 1 ? 'border-amber-500 bg-amber-50 ring-1 ring-amber-500' : 'border-gray-300' }}">
                                        <div class="flex items-center">
                                            <input wire:model.live="editAddress.receiver" type="radio"
                                                value="1"
                                                class="w-4 h-4 border-gray-300 text-amber-600 focus:ring-amber-500">
                                            <div class="ml-3">
                                                <div class="flex items-center">
                                                    <span class="mr-2 text-2xl">🙋‍♂️</span>
                                                    <div>
                                                        <div class="font-medium text-gray-900">Yo mismo</div>
                                                        <div class="text-sm text-gray-500">Recibiré personalmente el
                                                            pedido
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-50 {{ $editAddress->receiver == 2 ? 'border-amber-500 bg-amber-50 ring-1 ring-amber-500' : 'border-gray-300' }}">
                                        <div class="flex items-center">
                                            <input wire:model.live="editAddress.receiver" type="radio"
                                                value="2"
                                                class="w-4 h-4 border-gray-300 text-amber-600 focus:ring-amber-500">
                                            <div class="ml-3">
                                                <div class="flex items-center">
                                                    <span class="mr-2 text-2xl">👥</span>
                                                    <div>
                                                        <div class="font-medium text-gray-900">Otra persona</div>
                                                        <div class="text-sm text-gray-500">Un tercero recibirá el
                                                            pedido
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                @error('editAddress.receiver')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Información del receptor alternativo --}}
                            @if ($editAddress->receiver == 2)
                                <div class="transition-all duration-300 ease-in-out">
                                    <div
                                        class="p-6 border shadow-sm bg-gradient-to-br from-amber-50 to-orange-50 border-amber-200 rounded-xl">
                                        <div class="flex items-center mb-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-amber-100">
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
                                                <p class="text-sm text-amber-700">Información de la persona que
                                                    recibirá tu
                                                    pedido</p>
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            {{-- Nombre y Apellido --}}
                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">Nombres
                                                        *</label>
                                                    <input wire:model="editAddress.receiver_name" type="text"
                                                        placeholder="Ej: Juan Carlos"
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('editAddress.receiver_name')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label
                                                        class="block mb-1 text-sm font-medium text-gray-700">Apellidos</label>
                                                    <input wire:model="editAddress.receiver_last_name" type="text"
                                                        placeholder="Ej: Pérez González"
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('editAddress.receiver_last_name')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Documento y Teléfono --}}
                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">Tipo de
                                                        documento</label>
                                                    <select wire:model="editAddress.receiver_document_type"
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                        <option value="">Seleccionar</option>
                                                        @foreach ($documentTypes as $type)
                                                            <option value="{{ $type->value }}">{{ $type->label() }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('editAddress.receiver_document_type')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 text-sm font-medium text-gray-700">Número
                                                        de
                                                        documento</label>
                                                    <input wire:model="editAddress.receiver_document_number"
                                                        type="text" placeholder="Ej: 1234567890"
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('editAddress.receiver_document_number')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label
                                                        class="block mb-1 text-sm font-medium text-gray-700">Teléfono
                                                        *</label>
                                                    <input wire:model="editAddress.receiver_phone" type="tel"
                                                        placeholder="Ej: 0999999999"
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                    @error('editAddress.receiver_phone')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Email --}}
                                            <div>
                                                <label
                                                    class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                                                <input wire:model="editAddress.receiver_email" type="email"
                                                    placeholder="Ej: juan@example.com"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                @error('editAddress.receiver_email')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Checkbox para dirección predeterminada --}}
                            <div class="flex items-center">
                                <input wire:model="editAddress.default" type="checkbox" id="editDefault"
                                    class="w-4 h-4 border-gray-300 rounded text-amber-600 focus:ring-amber-500">
                                <label for="editDefault" class="ml-2 text-sm text-gray-700">
                                    Establecer como dirección predeterminada
                                </label>
                            </div>

                            {{-- Botones --}}
                            <div class="flex flex-col gap-3 pt-6 border-t border-gray-200 sm:flex-row">
                                <button type="button" wire:click="cancelEditAddress"
                                    class="flex-1 order-2 px-6 py-3 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg shadow-sm sm:flex-none sm:order-1 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
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
                                    class="flex-1 order-1 px-6 py-3 text-sm font-medium text-white transition-all duration-200 transform border border-transparent rounded-lg shadow-sm sm:flex-none sm:order-2 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 hover:scale-105">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Actualizar Dirección
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    {{-- Lista de direcciones existentes con diseño ultra compacto --}}
                    @if ($addresses->count())
                        <div class="grid grid-cols-1 gap-2 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($addresses as $address)
                                <div
                                    class="backdrop-blur-sm bg-white/80 border border-white/40 rounded-lg shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-[1.02] {{ $address->default ? 'ring-2 ring-indigo-300 bg-gradient-to-br from-indigo-50/80 to-purple-50/80' : '' }}">
                                    {{-- Header ultra compacto de la tarjeta con botones de acción --}}
                                    <div
                                        class="bg-gradient-to-r {{ $address->default ? 'from-indigo-500 to-purple-500' : 'from-gray-600 to-gray-700' }} px-3 py-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <div class="p-1 rounded bg-white/20 backdrop-blur-sm">
                                                    @if ($address->type == 1)
                                                        <i class="text-xs text-white fas fa-home"></i>
                                                    @elseif($address->type == 2)
                                                        <i class="text-xs text-white fas fa-building"></i>
                                                    @else
                                                        <i class="text-xs text-white fas fa-map-marker-alt"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h3 class="text-sm font-semibold text-white">
                                                        @if ($address->type == 1)
                                                            Casa
                                                        @elseif($address->type == 2)
                                                            Trabajo
                                                        @else
                                                            Otro
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>

                                            {{-- Botones de acción en el header con posición fija --}}
                                            <div class="flex items-center gap-1">
                                                {{-- Botón de predeterminado como estrella --}}
                                                <button wire:click="setAsDefault({{ $address->id }})"
                                                    title="{{ $address->default ? 'Dirección predeterminada' : 'Establecer como predeterminada' }}"
                                                    class="inline-flex items-center p-1 rounded transition-all duration-200 {{ $address->default ? 'text-yellow-300 hover:text-yellow-200' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                                                    <i
                                                        class="fas fa-star text-sm {{ $address->default ? 'drop-shadow-lg' : '' }}"></i>
                                                </button>

                                                {{-- Botón de editar --}}
                                                <button wire:click="startEditingAddress({{ $address->id }})"
                                                    title="Editar dirección"
                                                    class="inline-flex items-center p-1 transition-colors duration-200 rounded text-white/80 hover:text-white hover:bg-white/10">
                                                    <i class="text-sm fas fa-edit"></i>
                                                </button>

                                                {{-- Botón de eliminar --}}
                                                <button onclick="confirmDelete({{ $address->id }})"
                                                    title="Eliminar dirección"
                                                    class="inline-flex items-center p-1 transition-colors duration-200 rounded text-white/80 hover:text-red-300 hover:bg-red-500/20">
                                                    <i class="text-sm fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Contenido ultra compacto de la tarjeta --}}
                                    <div class="p-3">
                                        <div class="space-y-1.5">
                                            <div class="flex items-start space-x-2">
                                                <i class="fas fa-map-marker-alt text-gray-400 mt-0.5 text-xs"></i>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $address->address }}</p>
                                                    <p class="text-xs text-gray-600 truncate">
                                                        {{ $address->full_address }}</p>
                                                </div>
                                            </div>

                                            @if ($address->postal_code)
                                                <div class="flex items-center space-x-2">
                                                    <i class="text-xs text-gray-400 fas fa-mail-bulk"></i>
                                                    <span
                                                        class="text-xs text-gray-600">{{ $address->postal_code }}</span>
                                                </div>
                                            @endif

                                            @if ($address->reference)
                                                <div class="flex items-start space-x-2">
                                                    <i class="fas fa-info-circle text-gray-400 mt-0.5 text-xs"></i>
                                                    <span
                                                        class="text-xs text-gray-600 truncate">{{ $address->reference }}</span>
                                                </div>
                                            @endif

                                            {{-- Información del receptor alternativo ultra compacta --}}
                                            @if ($address->receiver === 2 && $address->receiver_info)
                                                @php
                                                    $receiverInfo = $address->receiver_info;
                                                @endphp
                                                <div
                                                    class="p-2 mt-2 border rounded-md bg-gradient-to-r from-amber-50/80 to-orange-50/80 border-amber-200/50 backdrop-blur-sm">
                                                    <div class="flex items-center mb-1 space-x-1">
                                                        <i class="text-xs fas fa-user text-amber-600"></i>
                                                        <span
                                                            class="text-xs font-medium text-amber-900">Receptor:</span>
                                                        <span
                                                            class="text-xs truncate text-amber-800">{{ trim(($receiverInfo['name'] ?? '') . ' ' . ($receiverInfo['last_name'] ?? '')) ?: 'N/A' }}</span>
                                                    </div>
                                                    @if (!empty($receiverInfo['phone']))
                                                        <div class="flex items-center space-x-1">
                                                            <i class="text-xs fas fa-phone text-amber-500"></i>
                                                            <span
                                                                class="text-xs truncate text-amber-800">{{ $receiverInfo['phone'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- =================================================================
                    FORMULARIOS OCULTOS PARA ELIMINACIÓN - PATRÓN ADMIN
                    =================================================================
                    
                    Cada dirección tiene su propio formulario oculto con:
                    - ID único: delete-form-{addressId}
                    - Método DELETE con @method('DELETE')
                    - Token CSRF con @csrf
                    - Ruta hacia ShippingController@destroy
                    
                    Estos formularios son enviados por JavaScript cuando el usuario
                    confirma la eliminación en el SweetAlert
                    --}}
                        @foreach ($addresses as $address)
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                                id="delete-form-{{ $address->id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                    @else
                        {{-- Estado vacío mejorado --}}
                        <div class="py-16 text-center">
                            <div class="max-w-md mx-auto">
                                <div
                                    class="flex items-center justify-center w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100">
                                    <i class="text-3xl text-indigo-500 fas fa-map-marker-alt"></i>
                                </div>
                                <h3 class="mb-2 text-xl font-semibold text-gray-900">No tienes direcciones registradas
                                </h3>
                                <p class="mb-8 text-gray-600">Agrega tu primera dirección de envío para continuar con
                                    tus compras de forma más rápida.</p>
                                <button wire:click="openNewAddress"
                                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white transition-all duration-200 transform shadow-lg bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl hover:from-indigo-600 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-indigo-400 hover:scale-105">
                                    <i class="mr-2 fas fa-plus"></i>
                                    Agregar Primera Dirección
                                </button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- =================================================================
JAVASCRIPT - SISTEMA DE ELIMINACIÓN CON SWEETALERT (PATRÓN ADMIN)
=================================================================

Este script implementa exactamente el mismo patrón usado en ProductController del admin:

1. window.addEventListener('swal') - Escucha eventos SweetAlert de Livewire
2. confirmDelete(addressId) - Función que maneja la confirmación de eliminación

FLUJO COMPLETO:
├── Usuario hace clic en "Eliminar"
├── Se ejecuta confirmDelete(addressId)
├── SweetAlert muestra confirmación con botones "Sí, Eliminar!" y "Cancelar"
├── Si usuario acepta: document.getElementById('delete-form-' + addressId).submit()
├── Formulario se envía a ShippingController@destroy
├── Controlador elimina dirección y redirige con swal
└── Laravel flash session muestra SweetAlert de éxito automáticamente

CARACTERÍSTICAS:
- Sin validaciones typeof innecesarias (SweetAlert garantizado)
- Sin console.log de debug (código limpio para producción)
- Patrón idéntico al admin para consistencia
- Usa @push('js') que es el stack correcto del layout
--}}
    @push('js')
        <script>
            // Escuchar eventos SweetAlert emitidos por Livewire
            window.addEventListener('swal', event => {
                const params = event.detail;
                Swal.fire(params);
            });

            // Función de confirmación de eliminación - Patrón idéntico al admin
            function confirmDelete(addressId) {
                // Sweet Alert 2 - Patrón idéntico al admin
                Swal.fire({
                    title: "¿Estás Seguro?",
                    text: "No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, Eliminar!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar formulario oculto correspondiente
                        document.getElementById('delete-form-' + addressId).submit();
                    }
                });
            }
        </script>
    @endpush
