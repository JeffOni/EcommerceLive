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
        'name' => 'Editar  ' . $category->name,
    ],
]">
    <x-slot name="action">
        <x-link href="{{ route('admin.categories.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    {{-- Formulario para crear una nueva Categoría --}}

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf

        @method('PUT')

        <div class="p-6 bg-white rounded-lg shadow-lg">

            {{-- muestra el error de validación --}}

            <x-validation-errors class="mb-4" />

            {{-- Lista de Familia --}}

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Familia') }}" />
                <x-select name="family_id" id="family_id" class="w-full">
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}" {{-- @selected sirve para obtener los datos que se selcciono previamente en le campo en este caso familia --}} {{-- pasamos el metodo old para validar el que seleccionamos o el valor de category family inicial --}}
                            @selected(old('family_id', $category->family_id) == $family->id)>
                            {{ $family->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>
            {{-- input de Nombre --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Nombre') }}" />
                <x-input class="w-full" placeholder="ingrese el nombre de la Categoría" name="name"
                    value="{{ old('name', $category->name) }}" />
            </div>
            <div class="flex justify-end gap-x-3">
                <x-danger-button name="Eliminar
                {{ $category->name }}" onclick="confirmDelete()" />
                <x-button name="Actualizar Categoría" />
            </div>
        </div>
    </form>

    {{-- formulario para eliminar  --}}
    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>

    {{-- js de sweet alert para confirmar la aelimiancion --}}
    @push('js')
        <script>
            let confirmDelete = () => {
                // Sweet Alert 2
                Swal.fire({
                    title: "Estás Seguro?",
                    text: "No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, Bórralo!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Swal.fire({
                        //     title: "Eliminado!",
                        //     text: "Su archivo ha sido eliminado.",
                        //     icon: "success"
                        // });
                        document.getElementById('delete-form').submit();
                    }
                });
            }
        </script>
    @endpush

</x-admin-layout>
