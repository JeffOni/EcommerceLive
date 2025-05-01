<div class="inline-flex items-center">
    {{-- Botón "+" que inicia la interacción para agregar características a una opción específica --}}
    {{-- El botón utiliza clases de Tailwind para estilizarse como un círculo azul con efecto hover verde --}}
    {{-- Cuando se hace clic, llama a toggleInParent() que emite un evento al componente padre --}}
    <button type="button" class="ml-2 px-1.5 py-0.5 h-5 w-5 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300" wire:click="toggleInParent">
        +
    </button>
</div>

{{-- Este bloque solo se renderiza cuando la propiedad $visible es true --}}
{{-- El formulario se muestra en un contenedor designado en product-variants.blade.php mediante eventos --}}
@if($visible)
    <div class="pt-3 border-t">
        {{-- El formulario utiliza wire:submit.prevent para manejar el envío de forma asíncrona sin recargar la página --}}
        {{-- Al enviar, se llama a addSelectedFeatures() que procesa las características seleccionadas --}}
        <form wire:submit.prevent="addSelectedFeatures">
            <div class="mb-2 font-semibold text-gray-700">Selecciona las características a agregar:</div>

            {{-- Iteración sobre las características disponibles usando la propiedad computada availableFeatures --}}
            {{-- Se usa forelse para manejar el caso en que no haya características disponibles --}}
            @forelse($this->availableFeatures as $feature)
                <label class="flex items-center mb-2 space-x-2">
                    {{-- wire:model.defer carga los datos solo cuando se envía el formulario, mejorando el rendimiento --}}
                    {{-- selectedFeatures almacena el estado de selección como array indexado por ID de característica --}}
                    <input type="checkbox" wire:model.defer="selectedFeatures.{{ $feature->id }}" value="1" class="form-checkbox">
                    <span>{{ $feature->description }}</span>
                </label>
            @empty
                <div class="text-sm text-gray-400">No hay características disponibles para agregar.</div>
            @endforelse

            {{-- Muestra mensajes de error de validación si los hay --}}
            @error('selectedFeatures')
                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
            @enderror

            <div class="flex gap-2 mt-3">
                {{-- Botones usando componentes x-button para mantener consistencia de diseño --}}
                {{-- El botón de envío dispara el proceso de guardar las características seleccionadas --}}
                <x-button type="submit" name="Agregar seleccionados" positive />
                {{-- El botón cancelar llama a hideBlock() que limpia la selección y oculta el formulario --}}
                <x-danger-button type="button" name="Cancelar" flat wire:click="hideBlock" />
            </div>
        </form>
    </div>
@endif
