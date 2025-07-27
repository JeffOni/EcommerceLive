@props(['name' => ''])
<!-- Definir el parámetro con un valor predeterminado vacío -->

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-primary-800
    dark:bg-primary-700 border border-transparent rounded-md font-semibold text-xs text-cream-50 dark:text-cream-100
    uppercase tracking-widest hover:bg-primary-900 dark:hover:bg-primary-800 focus:bg-primary-900
    dark:focus:bg-primary-800 active:bg-primary-950 dark:active:bg-primary-900 focus:outline-none focus:ring-2
    focus:ring-coral-400 focus:ring-offset-2 dark:focus:ring-offset-primary-900 disabled:opacity-50 transition-all
    duration-300 transform hover:scale-105 shadow-md hover:shadow-lg disabled:hover:scale-100']) }}>
    {{ $name }}
    <!-- Aquí mostramos el valor de 'name' pasado al componente -->
</button>