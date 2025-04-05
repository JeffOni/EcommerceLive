<div>

    <form wire:submit.prevent="save">

        <div class="p-6 bg-white rounded-lg shadow-lg">

            {{-- muestra el error de validación --}}

            <x-validation-errors class="mb-4" />

            {{-- Lista de Familia --}}

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Familia') }}" />
                <x-select name="family_id" id="family_id" class="w-full" wire:model.live="subcategory.family_id">
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
                <x-select name="category_id" id="category_id" class="w-full" wire:model.live="subcategory.category_id">
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
                    wire:model="subcategory.name" />
            </div>
            <div class="flex justify-end">
                <x-button name="Crear Subcategoría" />
            </div>
        </div>
    </form>
    {{-- @dump sirve para imprimir en pantalla los datos de la variable qu tenemos en el momento almacenados  --}}
    {{-- @dump($subcategory) --}}
</div>
