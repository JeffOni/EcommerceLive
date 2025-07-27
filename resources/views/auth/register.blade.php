<x-guest-layout>
    <x-authentication-card :key="'register'" maxWidth="2xl">
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        {{-- Título de bienvenida --}}
        <div class="mb-5 text-center">
            <h2 class="mb-2 text-3xl font-bold text-white">¡Únete a nosotros!</h2>
            <p class="text-sm text-white/80">Crea tu cuenta y comienza a disfrutar de nuestros productos</p>
        </div>

        {{-- Mensajes de validación --}}
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" class="space-y-3">
            @csrf

            {{-- Grid de campos del formulario --}}
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                {{-- Nombre --}}
                <div class="space-y-1">
                    <x-label for="name" value="{{ __('Nombre') }}" class="text-sm font-medium text-white/90" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <x-input id="name"
                            class="block w-full py-2 text-sm text-white rounded-lg pl-9 bg-white/10 border-white/20 placeholder-white/60 focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                            type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                            placeholder="Tu nombre" />
                    </div>
                </div>

                {{-- Apellido --}}
                <div class="space-y-1">
                    <x-label for="last_name" value="{{ __('Apellido') }}" class="text-sm font-medium text-white/90" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <x-input id="last_name"
                            class="block w-full py-2 text-sm text-white rounded-lg pl-9 bg-white/10 border-white/20 placeholder-white/60 focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                            type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name"
                            placeholder="Tu apellido" />
                    </div>
                </div>

                {{-- Tipo de documento --}}
                <div class="space-y-1">
                    <x-label for="document_type" value="{{ __('Tipo de Documento') }}"
                        class="text-sm font-medium text-white/90" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <x-select
                            class="w-full py-2 text-sm text-white rounded-lg pl-9 bg-white/10 border-white/20 focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                            id="document_type" name="document_type" required>
                            <option value="" disabled selected class="text-gray-400">{{ __('Selecciona tipo de
                                documento') }}</option>
                            @foreach (App\Enums\TypeOfDocuments::cases() as $type)
                            <option value="{{ $type->value }}" class="text-gray-900" {{ old('document_type')==$type->
                                value ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </x-select>
                    </div>
                </div>

                {{-- Número de documento --}}
                <div class="space-y-1">
                    <x-label for="document_number" value="{{ __('Número de Documento') }}"
                        class="text-sm font-medium text-white/90" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v2a1 1 0 01-1 1h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8H3a1 1 0 01-1-1V5a1 1 0 011-1h4z" />
                            </svg>
                        </div>
                        <x-input id="document_number"
                            class="block w-full py-2 text-sm text-white rounded-lg pl-9 bg-white/10 border-white/20 placeholder-white/60 focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                            type="text" name="document_number" :value="old('document_number')" required
                            placeholder="12345678" />
                    </div>
                </div>

                {{-- Email --}}
                <div class="space-y-1">
                    <x-label for="email" value="{{ __('Correo Electrónico') }}"
                        class="text-sm font-medium text-white/90" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <x-input id="email"
                            class="block w-full py-2 text-sm text-white rounded-lg pl-9 bg-white/10 border-white/20 placeholder-white/60 focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                            type="email" name="email" :value="old('email')" required autocomplete="username"
                            placeholder="tu@ejemplo.com" />
                    </div>
                </div>

                {{-- Teléfono --}}
                <div class="space-y-1">
                    <x-label for="phone" value="{{ __('Teléfono') }}" class="text-sm font-medium text-white/90" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <x-input id="phone"
                            class="block w-full py-2 text-sm text-white rounded-lg pl-9 bg-white/10 border-white/20 placeholder-white/60 focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                            type="text" name="phone" :value="old('phone')" required placeholder="+1 234 567 8900" />
                    </div>
                </div>

                {{-- Contraseña --}}
                <div class="space-y-1">
                    <x-label for="password" value="{{ __('Contraseña') }}" class="text-sm font-medium text-white/90" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <x-input id="password"
                            class="block w-full py-2 text-sm text-white rounded-lg pl-9 bg-white/10 border-white/20 placeholder-white/60 focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                            type="password" name="password" required autocomplete="new-password"
                            placeholder="••••••••" />
                    </div>
                </div>

                {{-- Confirmar Contraseña --}}
                <div class="space-y-1">
                    <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}"
                        class="text-sm font-medium text-white/90" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <x-input id="password_confirmation"
                            class="block w-full py-2 text-sm text-white rounded-lg pl-9 bg-white/10 border-white/20 placeholder-white/60 focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                            type="password" name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••" />
                    </div>
                </div>
            </div>

            {{-- Términos y condiciones --}}
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="space-y-2">
                <div class="flex items-start space-x-3">
                    <x-checkbox name="terms" id="terms" required
                        class="mt-1 text-white rounded bg-white/10 border-white/20 focus:ring-white/30" />
                    <div class="text-sm leading-relaxed text-white/80">
                        {!! __('Acepto los :terms_of_service y la :privacy_policy', [
                        'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '"
                            class="font-semibold text-white underline transition-colors duration-200 hover:text-white/80 decoration-white/40 hover:decoration-white">'
                            . __('Términos de Servicio') . '</a>',
                        'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '"
                            class="font-semibold text-white underline transition-colors duration-200 hover:text-white/80 decoration-white/40 hover:decoration-white">'
                            . __('Política de Privacidad') . '</a>',
                        ]) !!}
                    </div>
                </div>
            </div>
            @endif

            {{-- Botones de acción --}}
            <div class="space-y-3">
                <button type="submit"
                    class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-6 rounded-xl backdrop-blur-sm border border-white/30 transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-white/50 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>{{ __('Crear Cuenta') }}</span>
                </button>

                {{-- Enlace de login --}}
                <div class="text-center">
                    <span class="text-sm text-white/60">¿Ya tienes una cuenta? </span>
                    <a href="{{ route('login') }}"
                        class="font-semibold text-white underline transition-colors duration-200 hover:text-white/80 decoration-white/40 hover:decoration-white">
                        Inicia sesión aquí
                    </a>
                </div>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>