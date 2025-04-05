<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'route' => route('admin.products.index'),
    ],
    [
        'name' => 'Nueva Producto',
    ],
]">

<x-slot name="action">
    <x-link href="{{ route('admin.products.index') }}" type="secondary" name="Regresar" />
</x-slot>
    {{-- Formulario para crear una nueva SubCategoria con livewire--}}
    @livewire('admin.products.product-create')
</x-admin-layout>
