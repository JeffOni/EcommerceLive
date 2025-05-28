<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
        'route' => route('admin.categories.index'),
    ],
    [
        'name' => 'Crear Categoría',
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.categories.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    {{-- Formulario para crear una nueva Categoría --}}

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="card">

            {{-- muestra el error de validación --}}

            <x-validation-errors class="mb-4" />

            {{-- Lista de Familia --}}

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Familia') }}" />
                <x-select name="family_id" id="family_id" class="w-full">
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}" @selected(old('family_id') == $family->id)>
                            {{ $family->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>
            {{-- input de Nombre --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Nombre') }}" />
                <x-input class="w-full" placeholder="ingrese el nombre de la Categoría" name="name"
                    value="{{ old('name') }}" />
            </div>
            <div class="flex justify-end">
                <x-button name="Crear Categoría" />
            </div>
        </div>
    </form>

</x-admin-layout>
