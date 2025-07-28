<x-admin-layout>
    <x-slot name="title">Editar Oferta</x-slot>

    <div class="max-w-2xl mx-2 sm:mx-4 lg:mx-auto space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary-900 to-secondary-500 rounded-lg shadow-sm p-3 sm:p-4 lg:p-6">
            <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div class="min-w-0 flex-1">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">✏️ Editar Oferta</h1>
                    <p class="text-xs sm:text-sm lg:text-base text-secondary-100 truncate">Modificar la oferta de {{
                        $product->name }}</p>
                </div>
                <a href="{{ route('admin.offers.index') }}"
                    class="w-full sm:w-auto bg-white/20 backdrop-blur text-white px-3 py-2 sm:px-4 rounded-lg hover:bg-white/30 transition-colors text-center text-sm sm:text-base border border-white/30">
                    <i class="fas fa-arrow-left mr-1 sm:mr-2"></i><span class="hidden sm:inline">Volver a</span> Ofertas
                </a>
            </div>
        </div>

        {{-- Información del producto --}}
        <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 lg:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">🛍️ Información del Producto</h3>
            <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover mx-auto sm:mx-0 flex-shrink-0"
                    onerror="this.src='https://via.placeholder.com/64x64/e5e7eb/9ca3af?text=IMG'">
                <div class="text-center sm:text-left flex-1 min-w-0">
                    <h4 class="text-base sm:text-lg font-medium text-gray-900 truncate">{{ $product->name }}</h4>
                    <p class="text-xs sm:text-sm text-gray-600 truncate">{{
                        $product->subcategory->category->family->name ?? 'Sin categoría' }}</p>
                    <p class="text-sm sm:text-lg font-bold text-green-600 mt-1">Precio original: ${{
                        number_format($product->price, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Formulario de edición --}}
        <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 lg:p-6">
            <form action="{{ route('admin.offers.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4 sm:space-y-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">📋 Configuración de la
                        Oferta</h3>

                    {{-- Nombre de la oferta --}}
                    <div>
                        <label for="offer_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag text-purple-500 mr-1"></i>Nombre de la Oferta *
                        </label>
                        <input type="text" id="offer_name" name="offer_name"
                            value="{{ old('offer_name', $product->offer_name) }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('offer_name') border-red-500 @else border-gray-300 @enderror"
                            placeholder="Ej: Black Friday 2025, Liquidación de Temporada..." required>
                        @error('offer_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipo de descuento --}}
                    <div>
                        <label for="offer_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-percentage text-blue-500 mr-1"></i>Tipo de Descuento *
                        </label>
                        <select id="offer_type" name="offer_type"
                            class="w-full border rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('offer_type') border-red-500 @else border-gray-300 @enderror"
                            onchange="updateDiscountLabel()" required>
                            <option value="percentage" {{ old('offer_type', 'percentage' )=='percentage' ? 'selected'
                                : '' }}>
                                💯 Porcentaje (%)
                            </option>
                            <option value="fixed_amount" {{ old('offer_type')=='fixed_amount' ? 'selected' : '' }}>
                                💰 Monto Fijo ($)
                            </option>
                        </select>
                        @error('offer_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Valor del descuento --}}
                    <div>
                        <label for="offer_value" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calculator text-orange-500 mr-1"></i>
                            <span id="discount_label">Porcentaje de Descuento *</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="offer_value" name="offer_value"
                                value="{{ old('offer_value', $product->offer_percentage) }}"
                                class="w-full border rounded-lg px-3 py-2 pr-12 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('offer_value') border-red-500 @else border-gray-300 @enderror"
                                placeholder="0" min="0" step="0.01" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span id="discount_symbol" class="text-gray-500 font-medium">%</span>
                            </div>
                        </div>
                        @error('offer_value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Vista previa del precio --}}
                        <div id="price_preview"
                            class="mt-3 p-3 sm:p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg border border-green-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-eye text-green-600 mr-2"></i>
                                <span class="text-xs sm:text-sm text-gray-600 font-medium">Vista previa:</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                                <span class="text-sm sm:text-base text-gray-500 line-through">${{
                                    number_format($product->price, 2) }}</span>
                                <span id="new_price" class="text-red-600 font-bold text-lg sm:text-xl">${{
                                    number_format($product->offer_price, 2) }}</span>
                                <span id="savings"
                                    class="text-green-600 text-xs sm:text-sm font-medium bg-green-100 px-2 py-1 rounded-full">(Ahorro:
                                    ${{
                                    number_format($product->price - $product->offer_price, 2) }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Fechas --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-plus text-green-500 mr-1"></i>Fecha de Inicio *
                            </label>
                            <input type="datetime-local" id="starts_at" name="starts_at"
                                value="{{ old('starts_at', $product->offer_starts_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full border rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('starts_at') border-red-500 @else border-gray-300 @enderror"
                                required>
                            @error('starts_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-times text-red-500 mr-1"></i>Fecha de Fin *
                            </label>
                            <input type="datetime-local" id="ends_at" name="ends_at"
                                value="{{ old('ends_at', $product->offer_ends_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full border rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('ends_at') border-red-500 @else border-gray-300 @enderror"
                                required>
                            @error('ends_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div
                    class="flex flex-col space-y-3 sm:flex-row sm:justify-end sm:space-y-0 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.offers.index') }}"
                        class="w-full sm:w-auto bg-gray-300 text-gray-700 px-4 sm:px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors text-center text-sm sm:text-base">
                        <i class="fas fa-times mr-1 sm:mr-2"></i>Cancelar
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
                        <i class="fas fa-save mr-1 sm:mr-2"></i>Actualizar Oferta
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
                label.innerHTML = '<i class="fas fa-percentage text-blue-500 mr-1"></i>Porcentaje de Descuento *';
                symbol.textContent = '%';
            } else if (type === 'fixed_amount') {
                label.innerHTML = '<i class="fas fa-dollar-sign text-green-500 mr-1"></i>Monto de Descuento *';
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
            
            // Actualizar color del precio según el descuento
            const newPriceElement = document.getElementById('new_price');
            const discountPercentage = (savings / originalPrice) * 100;
            
            if (discountPercentage >= 50) {
                newPriceElement.className = 'text-red-600 font-bold text-lg sm:text-xl';
            } else if (discountPercentage >= 25) {
                newPriceElement.className = 'text-orange-600 font-bold text-lg sm:text-xl';
            } else {
                newPriceElement.className = 'text-green-600 font-bold text-lg sm:text-xl';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateDiscountLabel();
            
            document.getElementById('offer_value').addEventListener('input', updatePricePreview);
            document.getElementById('offer_type').addEventListener('change', updatePricePreview);
            
            // Mejorar experiencia del formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualizando...';
                submitBtn.disabled = true;
            });
        });
    </script>
    @endpush
</x-admin-layout>