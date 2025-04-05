<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Subategorías',
        'route' => route('admin.subcategories.index'),
    ],
    [
        'name' => 'Nueva Subcategoría',
    ],
]">

<x-slot name="action">
    <x-link href="{{ route('admin.subcategories.index') }}" type="secondary" name="Regresar" />
</x-slot>
    {{-- Formulario para crear una nueva SubCategoria con livewire--}}
    @livewire('admin.subcategories.subcategory-create')
</x-admin-layout>
