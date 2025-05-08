<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
    ],
]">

<x-slot name="action">
    <x-link href="{{ route('admin.covers.create') }}" type="primary" name="Nueva Portada"/>
</x-slot>


</x-admin-layout>
