@props([
'href' => '#', // URL del enlace, por defecto es '#'
'type' => 'primary', // Tipo de enlace para estilos
'target' => '_self', // Controla si el enlace se abre en una nueva pestaña
'id' => null, // ID opcional
'name' => '', // Texto del enlace
])

@php
$baseClasses = 'px-4 py-2 font-semibold text-white rounded-md inline-block transition-all duration-300 transform
hover:scale-105 focus:outline-none focus:ring-2 focus:ring-opacity-50';
$typeClasses = match ($type) {
'primary' => 'bg-primary-700 hover:bg-primary-800 focus:ring-primary-400 shadow-md hover:shadow-lg',
'secondary' => 'bg-coral-500 hover:bg-coral-600 focus:ring-coral-400 shadow-md hover:shadow-lg',
'success' => 'bg-secondary-500 hover:bg-secondary-600 focus:ring-secondary-400 shadow-md hover:shadow-lg',
default => 'bg-slate-500 hover:bg-slate-600 focus:ring-slate-400 shadow-md hover:shadow-lg',
};

// Si el target es "_blank", agregamos 'noopener noreferrer' por seguridad
$relAttribute = $target === '_blank' ? 'noopener noreferrer' : null;
@endphp

<a href="{{ $href }}" target="{{ $target }}" {{ $id ? "id=$id" : '' }} {{ $relAttribute ? "rel=$relAttribute" : '' }} {{
    $attributes->merge(['class' => "$baseClasses $typeClasses"]) }}>
    {{ $name }}
</a>