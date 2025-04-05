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

    @livewire('admin.subcategories.subcategory-edit', ['subcategory' => $subcategory])
</x-admin-layout>
