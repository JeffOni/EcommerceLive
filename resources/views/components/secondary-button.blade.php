@props(['name' => ''])
<!-- Definir el parámetro con un valor predeterminado vacío -->

<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white
    dark:bg-slate-800 border border-secondary-300 dark:border-secondary-500 rounded-md font-semibold text-xs
    text-slate-700 dark:text-secondary-300 uppercase tracking-widest shadow-sm hover:bg-secondary-50
    dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-secondary-400 focus:ring-offset-2
    dark:focus:ring-offset-slate-800 disabled:opacity-25 transition-all duration-300 transform hover:scale-105 shadow-md
    hover:shadow-lg disabled:hover:scale-100']) }}>
    {{ $name }}
</button>