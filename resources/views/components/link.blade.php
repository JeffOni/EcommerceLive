@props([
    'href' => '#', // URL del enlace, por defecto es '#'
    'type' => 'primary', // Tipo de enlace para estilos
    'target' => '_self', // Controla si el enlace se abre en una nueva pestaña
    'id' => null, // ID opcional
    'name' => '', // Texto del enlace
])

@php
    $baseClasses = 'px-4 py-2 font-semibold text-white rounded-md inline-block';
    $typeClasses = match ($type) {
        'primary' => 'bg-blue-500 hover:bg-blue-700',
        'secondary' => 'bg-red-500 hover:bg-red-700',
        'success' => 'bg-green-500 hover:bg-green-700',
        default => 'bg-gray-500 hover:bg-gray-700',
    };

    // Si el target es "_blank", agregamos 'noopener noreferrer' por seguridad
    $relAttribute = $target === '_blank' ? 'noopener noreferrer' : null;
@endphp

<a href="{{ $href }}" target="{{ $target }}" {{ $id ? "id=$id" : '' }}
    {{ $relAttribute ? "rel=$relAttribute" : '' }} {{ $attributes->merge(['class' => "$baseClasses $typeClasses"]) }}>
    {{ $name }}
</a>
