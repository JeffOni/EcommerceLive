<div>

    <form wire:submit.prevent="save">

        <div class="p-6 bg-white rounded-lg shadow-lg">

            {{-- muestra el error de validación --}}

            <x-validation-errors class="mb-4" />

            {{-- Lista de Familia --}}

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Familia') }}" />
                <x-select name="family_id" id="family_id" class="w-full" wire:model.live="subcategoryEdit.family_id">
                    <option value="" disabled>Seleccione una familia</option>
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}">
                            {{ $family->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Categoría') }}" />
                {{-- wire:model.live el .live sirve para que se vaya acutalizando el estdo del category_id conforme se vaya escribiendo --}}
                <x-select name="category_id" id="category_id" class="w-full"
                    wire:model.live="subcategoryEdit.category_id">
                    <option value="" disabled>Seleccione una categoría</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">
                            {{-- @selected(old('category_id') == $category->id) recupera el campo anterior si el valor es igual al de category_id --}}
                            {{ $category->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>
            {{-- input de Nombre --}}
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Nombre') }}" />
                <x-input class="w-full" placeholder="ingrese el nombre de la Subcategoría" name="name"
                    wire:model="subcategoryEdit.name" />
            </div>
            <div class="flex justify-end gap-x-3">
                <x-danger-button name="Eliminar {{ $subcategory->name }}" onclick="confirmDelete()" />
                <x-button name="Actualizar Subcategoría" />
            </div>
        </div>
    </form>
    {{-- @dump sirve para imprimir en pantalla los datos de la variable qu tenemos en el momento almacenados  --}}
    {{-- @dump($subcategory) --}}

    {{-- formulario para eliminar  --}}
    <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" id="delete-form">
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
</div>
