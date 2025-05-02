<div
    {{-- Este componente representa un contenedor que puede ser utilizado para agrupar otros elementos --}}
    {{-- Se utiliza para mantener la consistencia de diseño y facilitar el uso de clases comunes --}}
    {{ $attributes->merge(['class' => 'max-w-7xl mx-auto sm:px-6 lg:px-8']) }}>
    {{ $slot }}
</div>
