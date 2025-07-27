@props(['name' => ''])
<!-- Definir el parámetro con un valor predeterminado vacío -->

<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2
    bg-coral-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest
    hover:bg-coral-700 active:bg-coral-800 focus:outline-none focus:ring-2 focus:ring-coral-500 focus:ring-offset-2
    dark:focus:ring-offset-primary-900 transition-all duration-300 transform hover:scale-105 shadow-md
    hover:shadow-lg']) }}>
    {{ $name }}
</button>