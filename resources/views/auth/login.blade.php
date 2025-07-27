<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        {{-- Título de bienvenida --}}
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-cream-50 mb-2">¡Bienvenido de vuelta!</h2>
            <p class="text-secondary-200/90 text-sm">Inicia sesión en tu cuenta para continuar</p>
        </div>

        {{-- Mensajes de validación y estado --}}
        <x-validation-errors class="mb-6" />

        @session('status')
        <div class="mb-6 p-4 bg-green-500/20 border border-green-400/30 rounded-xl backdrop-blur-sm">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-green-300 text-sm font-medium">{{ $value }}</span>
            </div>
        </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Campo Email --}}
            <div class="space-y-2">
                <x-label for="email" value="{{ __('Correo Electrónico') }}" class="text-white/90 font-medium" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <x-input id="email"
                        class="block w-full pl-10 bg-white/10 border-white/20 text-white placeholder-white/60 rounded-xl focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        placeholder="tu@ejemplo.com" />
                </div>
            </div>

            {{-- Campo Contraseña --}}
            <div class="space-y-2">
                <x-label for="password" value="{{ __('Contraseña') }}" class="text-white/90 font-medium" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <x-input id="password"
                        class="block w-full pl-10 bg-white/10 border-white/20 text-white placeholder-white/60 rounded-xl focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••" />
                </div>
            </div>

            {{-- Recordar sesión --}}
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center space-x-3 cursor-pointer">
                    <x-checkbox id="remember_me" name="remember"
                        class="bg-white/10 border-white/20 text-white focus:ring-white/30 rounded" />
                    <span class="text-white/80 text-sm select-none">{{ __('Recordarme') }}</span>
                </label>

                @if (Route::has('password.request'))
                <a class="text-white/80 hover:text-white text-sm font-medium transition-colors duration-200 underline decoration-white/40 hover:decoration-white"
                    href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
                @endif
            </div>

            {{-- Botón de inicio de sesión --}}
            <div class="space-y-4">
                <button type="submit"
                    class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-6 rounded-xl backdrop-blur-sm border border-white/30 transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-white/50 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>{{ __('Iniciar Sesión') }}</span>
                </button>

                {{-- Enlace de registro --}}
                <div class="text-center">
                    <span class="text-white/60 text-sm">¿No tienes una cuenta? </span>
                    <a href="{{ route('register') }}"
                        class="text-white font-semibold hover:text-white/80 transition-colors duration-200 underline decoration-white/40 hover:decoration-white">
                        Regístrate aquí
                    </a>
                </div>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>