<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Familias',
        'route' => route('admin.families.index'),
    ],
    [
        'name' => 'Crear Familia',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.families.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    {{-- Formulario para crear una nueva Familia --}}

    <form action="{{ route('admin.families.store') }}" method="POST">
        @csrf

        {{-- div con clase card para todos --}}
        <div class="card">

            {{-- Muestra el error de validación --}}

            <x-validation-errors class="mb-4" />

            {{-- Input de Nombre --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Nombre') }}" />
                <x-input class="w-full" placeholder="ingrese el nombre de la Familia" name="name"
                    value="{{ old('name') }}" />
            </div>
            <div class="flex justify-end">
                <x-button name="Crear Familia" />
            </div>
        </div>
    </form>

</x-admin-layout>
