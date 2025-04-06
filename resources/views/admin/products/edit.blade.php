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
        'name' => 'Editar  ' . $product->name,
    ],
]">

    @livewire('admin.products.edit', ['product' => $product,])
</x-admin-layout>
