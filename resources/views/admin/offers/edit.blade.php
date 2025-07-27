<x-admin-layout>
    <x-slot name="title">Editar Oferta</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">✏️ Editar Oferta</h1>
                    <p class="text-gray-600">Modificar la oferta de {{ $product->name }}</p>
                </div>
                <a href="{{ route('admin.offers.index') }}"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    ← Volver
                </a>
            </div>
        </div>

        {{-- Información del producto --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🛍️ Información del Producto</h3>
            <div class="flex items-center">
                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                    class="w-16 h-16 rounded-lg object-cover mr-4"
                    onerror="this.src='https://via.placeholder.com/64x64/e5e7eb/9ca3af?text=IMG'">
                <div>
                    <h4 class="text-lg font-medium text-gray-900">{{ $product->name }}</h4>
                    <p class="text-gray-600">{{ $product->subcategory->category->family->name ?? 'Sin categoría' }}</p>
                    <p class="text-lg font-bold text-green-600">Precio original: ${{ number_format($product->price, 2)
                        }}</p>
                </div>
            </div>
        </div>

        {{-- Formulario de edición --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.offers.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">📋 Configuración de la Oferta</h3>

                    {{-- Nombre de la oferta --}}
                    <div>
                        <label for="offer_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Oferta *
                        </label>
                        <input type="text" id="offer_name" name="offer_name"
                            value="{{ old('offer_name', $product->offer_name) }}"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('offer_name') border-red-500 @else border-gray-300 @enderror"
                            placeholder="Ej: Black Friday 2025, Liquidación de Temporada..." required>
                        @error('offer_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipo de descuento --}}
                    <div>
                        <label for="offer_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Descuento *
                        </label>
                        <select id="offer_type" name="offer_type"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('offer_type') border-red-500 @else border-gray-300 @enderror"
                            onchange="updateDiscountLabel()" required>
                            <option value="percentage" {{ old('offer_type', 'percentage' )=='percentage' ? 'selected'
                                : '' }}>
                                Porcentaje (%)
                            </option>
                            <option value="fixed_amount" {{ old('offer_type')=='fixed_amount' ? 'selected' : '' }}>
                                Monto Fijo ($)
                            </option>
                        </select>
                        @error('offer_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Valor del descuento --}}
                    <div>
                        <label for="offer_value" class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="discount_label">Porcentaje de Descuento *</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="offer_value" name="offer_value"
                                value="{{ old('offer_value', $product->offer_percentage) }}"
                                class="w-full border rounded-lg px-3 py-2 pr-12 focus:ring-blue-500 focus:border-blue-500 @error('offer_value') border-red-500 @else border-gray-300 @enderror"
                                placeholder="0" min="0" step="0.01" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span id="discount_symbol" class="text-gray-500">%</span>
                            </div>
                        </div>
                        @error('offer_value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Vista previa del precio --}}
                        <div id="price_preview" class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600">Vista previa:</div>
                            <div class="flex items-center gap-3">
                                <span class="text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                <span id="new_price" class="text-red-600 font-bold text-lg">${{
                                    number_format($product->offer_price, 2) }}</span>
                                <span id="savings" class="text-green-600 text-sm">(Ahorro: ${{
                                    number_format($product->price - $product->offer_price, 2) }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Fechas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Inicio *
                            </label>
                            <input type="datetime-local" id="starts_at" name="starts_at"
                                value="{{ old('starts_at', $product->offer_starts_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('starts_at') border-red-500 @else border-gray-300 @enderror"
                                required>
                            @error('starts_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Fin *
                            </label>
                            <input type="datetime-local" id="ends_at" name="ends_at"
                                value="{{ old('ends_at', $product->offer_ends_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('ends_at') border-red-500 @else border-gray-300 @enderror"
                                required>
                            @error('ends_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.offers.index') }}"
                        class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        💾 Actualizar Oferta
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const originalPrice = {{ $product->price }};

function updateDiscountLabel() {
    const type = document.getElementById('offer_type').value;
    const label = document.getElementById('discount_label');
    const symbol = document.getElementById('discount_symbol');
    
    if (type === 'percentage') {
        label.textContent = 'Porcentaje de Descuento *';
        symbol.textContent = '%';
    } else if (type === 'fixed_amount') {
        label.textContent = 'Monto de Descuento *';
        symbol.textContent = '$';
    }
    
    updatePricePreview();
}

function updatePricePreview() {
    const type = document.getElementById('offer_type').value;
    const value = parseFloat(document.getElementById('offer_value').value) || 0;
    
    let newPrice = originalPrice;
    
    if (type === 'percentage') {
        newPrice = originalPrice - (originalPrice * (value / 100));
    } else if (type === 'fixed_amount') {
        newPrice = Math.max(0, originalPrice - value);
    }
    
    const savings = originalPrice - newPrice;
    
    document.getElementById('new_price').textContent = '$' + newPrice.toFixed(2);
    document.getElementById('savings').textContent = '(Ahorro: $' + savings.toFixed(2) + ')';
}

document.addEventListener('DOMContentLoaded', function() {
    updateDiscountLabel();
    
    document.getElementById('offer_value').addEventListener('input', updatePricePreview);
    document.getElementById('offer_type').addEventListener('change', updatePricePreview);
});
    </script>
    @endpush
</x-admin-layout>