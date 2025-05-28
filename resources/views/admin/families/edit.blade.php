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
        'name' => $family->name,
    ],
]">

    <x-slot name="action">
        <x-link href="{{ route('admin.families.index') }}" type="secondary" name="Regresar" />
    </x-slot>

    <form action="{{ route('admin.families.update', $family) }}" method="POST">
        @csrf
        <div class="card">
            @method('PUT')
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Nombre') }}" />
                <x-input class="w-full" placeholder="ingrese el nombre de la Familia" name="name"
                    value="{{ old('name', $family->name) }}" />
            </div>
            <div class="flex justify-end gap-x-3">
                <x-danger-button name="Eliminar
                {{ $family->name }}" onclick="confirmDelete()" />
                <x-button name="Actualizar Familia" />
            </div>
        </div>
    </form>
    {{-- formulario para eliminar  --}}
    <form action="{{ route('admin.families.destroy', $family) }}" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>

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
