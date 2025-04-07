<div>
    <form wire:submit.prvent="store">

        <figure class="relative mb-4">

            <div class="absolute top-8 right-8">
                <x-label class="flex items-center px-4 py-2 bg-white rounded-lg shadow-lg cursor-pointer">
                    <i class="mr-2 fas fa-camera"></i>
                    Actualizar Imagen
                    <input type="file" wire:model="image" class="hidden" accept = "image/*" />
                </x-label>
            </div>

            <img class="aspect-[16/9] object-cover w-full  object-center"
                src="{{ $image ? $image->temporaryUrl() : Storage::url($productEdit['image_path']) }}" alt="Imagen de Productos">
            <figcaption class="font-semibold text-center text-gray-600">Iamgen de Producto</figcaption>

        </figure>

        <x-validation-errors class="mb-4" />

        {{-- muestra el error de validación --}}

        {{-- Lista de Familias --}}

        <div class="p-6 bg-white rounded-lg shadow-lg">
            {{-- In work, do what you enjoy. --}}

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Código de Producto') }}" />
                <x-input class="w-full" type="text" wire:model.defer="productEdit.sku"
                    placeholder="Código del Producto" />
            </div>
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Nombre') }}" />
                <x-input class="w-full" type="text" wire:model.defer="productEdit.name"
                    placeholder="Nombre del Producto" />
            </div>
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Descripción') }}" />
                <x-textarea class="w-full" wire:model.defer="productEdit.description" placeholder="Descripción del Producto"
                    rows="5" />
            </div>
            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Precio') }}" />
                <x-input class="w-full" type="number" step="0.01" wire:model.defer="productEdit.price"
                    placeholder="Precio del Producto" />
            </div>
            {{-- familias --}}

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Familia') }}" />
                <x-select wire:model.live="family_id" class="w-full" name="family_id" id="family_id">
                    {{-- wire:model.live el .live sirve para que se vaya acutalizando el estdo del family_id conforme se vaya escribiendo --}}
                    <option value="" disabled>Seleccione una familia</option>
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}">{{ $family->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- categorías --}}

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Categoría') }}" />
                <x-select wire:model.live="category_id" class="w-full" name="category_id" id="category_id">
                    {{-- wire:model.live el .live sirve para que se vaya acutalizando el estdo del category_id conforme se vaya escribiendo --}}
                    <option value="" disabled>Seleccione una categoría</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- subcategorías --}}

            <div class="mb-4">
                <x-label class="mb-2" value="{{ __('Subcategoría') }}" />
                <x-select wire:model.live="productEdit.subcategory_id" class="w-full" name="subcategory_id"
                    id="subcategory_id">
                    {{-- wire:model.live el .live sirve para que se vaya acutalizando el estdo del category_id conforme se vaya escribiendo --}}
                    <option value="" disabled>Seleccione una subcategoría</option>
                    @foreach ($this->subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>
        {{-- Buttons --}}

        <div class="flex justify-end ">
            <x-danger-button class="mr-2" name="Cancelar" />
            <x-button name="Actualizar Producto" />

        </div>
    </form>

</div>
