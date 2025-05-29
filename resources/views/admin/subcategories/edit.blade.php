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
        'name' => 'Editar  ' . $subcategory->name,
    ],
]">
    <x-slot name="action">
        <x-link href="{{ route('admin.subcategories.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    @livewire('admin.subcategories.subcategory-edit', ['subcategory' => $subcategory])
</x-admin-layout>
