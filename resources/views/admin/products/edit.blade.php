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

    <x-slot name="action">
        <x-link href="{{ route('admin.products.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    <div class="mb-12">

        @livewire('admin.products.product-edit', ['product' => $product], key('product-edit-' . $product->id))

    </div>

    @livewire('admin.products.product-variants', ['product' => $product], key('product-variants-' . $product->id))

</x-admin-layout>
