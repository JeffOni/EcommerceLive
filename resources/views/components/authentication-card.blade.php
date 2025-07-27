{{--
COMPONENTE DE TARJETA DE AUTENTICACIÓN REDISEÑADO
================================================

Diseño moderno y elegante para las páginas de autenticación con:
- Gradientes y efectos visuales modernos
- Diseño glassmorphism
- Animaciones suaves
- Layout responsive mejorado

@author GitHub Copilot
@version 2.0
--}}

@props(['maxWidth' => null])

@php
$maxWidth = [
'sm' => 'sm:max-w-sm',
'md' => 'sm:max-w-md',
'lg' => 'sm:max-w-lg',
'xl' => 'sm:max-w-xl',
'2xl' => 'sm:max-w-2xl',
'4xl' => 'sm:max-w-4xl',
][$maxWidth ?? 'md'];
@endphp

{{-- Contenedor principal con fondo gradiente --}}
<div class="relative flex flex-col items-center min-h-screen pt-6 overflow-hidden sm:justify-center sm:pt-0">
    {{-- Fondo gradiente usando colores del logo --}}
    <div class="absolute inset-0 bg-gradient-ocean"></div>

    {{-- Elementos decorativos animados con colores del logo --}}
    <div class="absolute inset-0">
        <div class="absolute rounded-full top-1/4 left-1/4 w-72 h-72 bg-secondary-300/20 blur-3xl animate-pulse"></div>
        <div
            class="absolute delay-1000 rounded-full bottom-1/4 right-1/4 w-96 h-96 bg-coral-400/15 blur-3xl animate-pulse">
        </div>
        <div
            class="absolute delay-500 transform -translate-x-1/2 -translate-y-1/2 rounded-full top-1/2 left-1/2 w-80 h-80 bg-brand-steel/20 blur-3xl animate-pulse">
        </div>
    </div>

    {{-- Contenido principal --}}
    <div class="relative z-10 w-full {{ $maxWidth }} px-6">
        {{-- Logo Section con efecto de elevación --}}
        <div class="flex justify-center mb-8">
            <div
                class="p-4 transition-all duration-300 transform border shadow-2xl bg-cream-50/20 backdrop-blur-lg rounded-2xl border-secondary-200/30 hover:scale-105">
                {{ $logo }}
            </div>
        </div>

        {{-- Tarjeta principal con glassmorphism usando colores del logo --}}
        <div
            class="bg-cream-50/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-secondary-200/20 p-8 transform hover:scale-[1.01] transition-all duration-300">
            {{-- Contenido del formulario --}}
            <div class="space-y-6">
                {{ $slot }}
            </div>
        </div>

        {{-- Elementos decorativos inferiores con colores del logo --}}
        <div class="flex justify-center mt-8">
            <div class="flex space-x-2">
                <div class="w-2 h-2 rounded-full bg-secondary-300/60 animate-pulse"></div>
                <div class="w-2 h-2 delay-200 rounded-full bg-coral-400/80 animate-pulse"></div>
                <div class="w-2 h-2 rounded-full bg-secondary-300/60 animate-pulse delay-400"></div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animación personalizada para el fondo */
    @keyframes gradient-shift {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .auth-gradient {
        background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 400% 400%;
        animation: gradient-shift 15s ease infinite;
    }
</style>