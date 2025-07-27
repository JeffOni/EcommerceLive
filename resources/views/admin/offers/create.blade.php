<x-admin-layout>
    <x-slot name="title">Crear Nueva Oferta</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">🎯 Crear Nueva Oferta</h1>
                    <p class="text-gray-600">Configura una nueva oferta para uno o múltiples productos</p>
                </div>
                <a href="{{ route('admin.offers.index') }}"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    ← Volver
                </a>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.offers.store') }}" method="POST" id="offerForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Configuración de la oferta --}}
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">📋 Configuración de la Oferta</h3>

                        {{-- Nombre de la oferta --}}
                        <div>
                            <label for="offer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de la Oferta *
                            </label>
                            <input type="text" id="offer_name" name="offer_name" value="{{ old('offer_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('offer_name') border-red-500 @enderror"
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
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('offer_type') border-red-500 @enderror"
                                onchange="updateDiscountLabel()" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="percentage" {{ old('offer_type')=='percentage' ? 'selected' : '' }}>
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
                                <span id="discount_label">Valor del Descuento *</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="offer_value" name="offer_value"
                                    value="{{ old('offer_value') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-12 focus:ring-blue-500 focus:border-blue-500 @error('offer_value') border-red-500 @enderror"
                                    placeholder="0" min="0" step="0.01" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span id="discount_symbol" class="text-gray-500">%</span>
                                </div>
                            </div>
                            @error('offer_value')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fechas --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Inicio *
                                </label>
                                <input type="datetime-local" id="starts_at" name="starts_at"
                                    value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('starts_at') border-red-500 @enderror"
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
                                    value="{{ old('ends_at', now()->addDays(7)->format('Y-m-d\TH:i')) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('ends_at') border-red-500 @enderror"
                                    required>
                                @error('ends_at')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Selección de productos --}}
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">🛍️ Seleccionar Productos</h3>

                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Productos Disponibles ({{ $products->count() }})
                                </label>
                                <div class="flex gap-2">
                                    <button type="button" onclick="selectAllProducts()"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                        Seleccionar Todos
                                    </button>
                                    <button type="button" onclick="deselectAllProducts()"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        Deseleccionar Todos
                                    </button>
                                </div>
                            </div>

                            <div class="border border-gray-300 rounded-lg max-h-96 overflow-y-auto">
                                @if($products->isEmpty())
                                <div class="p-6 text-center text-gray-500">
                                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                                    <p>No hay productos disponibles para ofertas.</p>
                                    <p class="text-sm">Todos los productos ya tienen ofertas activas.</p>
                                </div>
                                @else
                                @foreach($products as $product)
                                <div class="border-b border-gray-200 last:border-b-0">
                                    <label class="flex items-center p-4 hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="products[]" value="{{ $product->id }}"
                                            class="product-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}>
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                            class="w-12 h-12 rounded-lg object-cover mx-4"
                                            onerror="this.src='https://via.placeholder.com/48x48/e5e7eb/9ca3af?text=IMG'">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{
                                                $product->subcategory->category->family->name ?? 'Sin categoría' }}
                                            </div>
                                            <div class="text-sm font-bold text-green-600">${{
                                                number_format($product->price, 2) }}</div>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Stock: {{ $product->stock }}
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                                @endif
                            </div>

                            @error('products')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <div id="selected-count" class="mt-2 text-sm text-gray-600">
                                Productos seleccionados: <span id="count">0</span>
                            </div>
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
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                        id="submit-btn">
                        🎯 Crear Oferta
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
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
    } else {
        label.textContent = 'Valor del Descuento *';
        symbol.textContent = '';
    }
}

function selectAllProducts() {
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = true);
    updateSelectedCount();
}

function deselectAllProducts() {
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = false);
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.product-checkbox:checked').length;
    document.getElementById('count').textContent = count;
}

document.addEventListener('DOMContentLoaded', function() {
    // Actualizar contador inicial
    updateSelectedCount();
    
    // Escuchar cambios en checkboxes
    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });
    
    // Validación del formulario
    document.getElementById('offerForm').addEventListener('submit', function(e) {
        const checkedProducts = document.querySelectorAll('.product-checkbox:checked').length;
        if (checkedProducts === 0) {
            e.preventDefault();
            alert('Debes seleccionar al menos un producto para la oferta.');
            return false;
        }
    });
});
    </script>
    @endpush
</x-admin-layout>