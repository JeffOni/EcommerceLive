<div class="inline-flex items-center">
    {{-- Botón + que aparecerá junto al nombre de la opción, con mejor alineación --}}
    <button type="button" class="ml-2 px-1.5 py-0.5 h-5 w-5 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300" wire:click="toggleInParent">
        +
    </button>
</div>

{{-- El formulario se muestra en el contenedor designado en product-variants.blade.php --}}
@if($visible)
    <div class="pt-3 border-t">
        <form wire:submit.prevent="addSelectedFeatures">
            <div class="mb-2 font-semibold text-gray-700">Selecciona las características a agregar:</div>

            @forelse($this->availableFeatures as $feature)
                <label class="flex items-center mb-2 space-x-2">
                    <input type="checkbox" wire:model.defer="selectedFeatures.{{ $feature->id }}" value="1" class="form-checkbox">
                    <span>{{ $feature->description }}</span>
                </label>
            @empty
                <div class="text-sm text-gray-400">No hay características disponibles para agregar.</div>
            @endforelse

            @error('selectedFeatures')
                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
            @enderror

            <div class="flex gap-2 mt-3">
                <x-button type="submit" name="Agregar seleccionados" positive />
                <x-danger-button type="button" name="Cancelar" flat wire:click="hideBlock" />
            </div>
        </form>
    </div>
@endif
