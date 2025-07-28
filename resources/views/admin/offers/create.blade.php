<x-admin-layout>
    <x-slot name="title">Crear Nueva Oferta</x-slot>

    <div class="max-w-4xl mx-2 sm:mx-4 lg:mx-auto space-y-4 sm:space-y-6">
        {{-- Header responsive --}}
        <div class="bg-gradient-to-r from-primary-900 to-secondary-500 rounded-lg shadow-sm p-3 sm:p-4 lg:p-6">
            <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div class="min-w-0 flex-1">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">🎯 Crear Nueva Oferta
                    </h1>
                    <p class="text-xs sm:text-sm lg:text-base text-secondary-100 truncate">Configura una nueva oferta
                        para
                        uno o múltiples productos</p>
                </div>
                <a href="{{ route('admin.offers.index') }}"
                    class="w-full sm:w-auto bg-white/20 backdrop-blur text-white px-3 py-2 sm:px-4 rounded-lg hover:bg-white/30 transition-colors text-center text-sm sm:text-base border border-white/30">
                    <i class="fas fa-arrow-left mr-1 sm:mr-2"></i><span class="hidden sm:inline">Volver a</span> Ofertas
                </a>
            </div>
        </div>

        {{-- Formulario responsive --}}
        <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 lg:p-6">
            <form action="{{ route('admin.offers.store') }}" method="POST" id="offerForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Configuración de la oferta --}}
                    <div class="space-y-4 sm:space-y-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">📋 Configuración de
                            la Oferta</h3>

                        {{-- Nombre de la oferta --}}
                        <div>
                            <label for="offer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag text-purple-500 mr-1"></i>Nombre de la Oferta *
                            </label>
                            <input type="text" id="offer_name" name="offer_name" value="{{ old('offer_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('offer_name') border-red-500 @enderror"
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
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('offer_type') border-red-500 @enderror"
                                onchange="updateDiscountLabel()" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="percentage" {{ old('offer_type')=='percentage' ? 'selected' : '' }}>
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
                                <span id="discount_label">Valor del Descuento *</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="offer_value" name="offer_value"
                                    value="{{ old('offer_value') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-12 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('offer_value') border-red-500 @enderror"
                                    placeholder="0" min="0" step="0.01" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span id="discount_symbol" class="text-gray-500 font-medium">%</span>
                                </div>
                            </div>
                            @error('offer_value')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fechas --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-plus text-green-500 mr-1"></i>Fecha de Inicio *
                                </label>
                                <input type="datetime-local" id="starts_at" name="starts_at"
                                    value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('starts_at') border-red-500 @enderror"
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
                                    value="{{ old('ends_at', now()->addDays(7)->format('Y-m-d\TH:i')) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500 @error('ends_at') border-red-500 @enderror"
                                    required>
                                @error('ends_at')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Selección de productos --}}
                    <div class="space-y-4 sm:space-y-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">🛍️ Seleccionar
                            Productos</h3>

                        <div>
                            <div
                                class="flex flex-col space-y-2 sm:flex-row sm:justify-between sm:items-center sm:space-y-0 mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Productos Disponibles ({{ $products->count() }})
                                </label>
                                <div class="flex gap-2">
                                    <button type="button" onclick="selectAllProducts()"
                                        class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm px-2 py-1 rounded border border-blue-200 hover:bg-blue-50">
                                        <i class="fas fa-check-circle mr-1"></i><span
                                            class="hidden sm:inline">Seleccionar </span>Todos
                                    </button>
                                    <button type="button" onclick="deselectAllProducts()"
                                        class="text-red-600 hover:text-red-800 text-xs sm:text-sm px-2 py-1 rounded border border-red-200 hover:bg-red-50">
                                        <i class="fas fa-times-circle mr-1"></i><span
                                            class="hidden sm:inline">Deseleccionar </span>Todos
                                    </button>
                                </div>
                            </div>

                            <div class="border border-gray-300 rounded-lg max-h-80 sm:max-h-96 overflow-y-auto">
                                @if($products->isEmpty())
                                <div class="p-4 sm:p-6 text-center text-gray-500">
                                    <i class="fas fa-exclamation-triangle text-xl sm:text-2xl mb-2 text-yellow-500"></i>
                                    <p class="text-sm sm:text-base">No hay productos disponibles para ofertas.</p>
                                    <p class="text-xs sm:text-sm">Todos los productos ya tienen ofertas activas.</p>
                                </div>
                                @else
                                @foreach($products as $product)
                                <div class="border-b border-gray-200 last:border-b-0">
                                    <label class="flex items-center p-3 sm:p-4 hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="products[]" value="{{ $product->id }}"
                                            class="product-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 flex-shrink-0"
                                            {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}>
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg object-cover mx-2 sm:mx-4 flex-shrink-0"
                                            onerror="this.src='https://via.placeholder.com/48x48/e5e7eb/9ca3af?text=IMG'">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{
                                                $product->name }}</div>
                                            <div class="text-xs sm:text-sm text-gray-500 truncate">{{
                                                $product->subcategory->category->family->name ?? 'Sin categoría' }}
                                            </div>
                                            <div class="text-xs sm:text-sm font-bold text-green-600">${{
                                                number_format($product->price, 2) }}</div>
                                        </div>
                                        <div class="text-xs sm:text-sm text-gray-500 flex-shrink-0 ml-2">
                                            <div class="text-center">
                                                <div class="text-xs text-gray-400">Stock</div>
                                                <div class="font-medium">{{ $product->stock }}</div>
                                            </div>
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
                <div
                    class="flex flex-col space-y-3 sm:flex-row sm:justify-end sm:space-y-0 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.offers.index') }}"
                        class="w-full sm:w-auto bg-gray-300 text-gray-700 px-4 sm:px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors text-center text-sm sm:text-base">
                        <i class="fas fa-times mr-1 sm:mr-2"></i>Cancelar
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base"
                        id="submit-btn">
                        <i class="fas fa-bullseye mr-1 sm:mr-2"></i>Crear Oferta
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
                label.innerHTML = '<i class="fas fa-percentage text-blue-500 mr-1"></i>Porcentaje de Descuento *';
                symbol.textContent = '%';
            } else if (type === 'fixed_amount') {
                label.innerHTML = '<i class="fas fa-dollar-sign text-green-500 mr-1"></i>Monto de Descuento *';
                symbol.textContent = '$';
            } else {
                label.innerHTML = '<i class="fas fa-calculator text-orange-500 mr-1"></i>Valor del Descuento *';
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
            
            // Cambiar color basado en la cantidad
            const countElement = document.getElementById('count');
            if (count === 0) {
                countElement.className = 'text-red-600 font-bold';
            } else if (count <= 5) {
                countElement.className = 'text-yellow-600 font-bold';
            } else {
                countElement.className = 'text-green-600 font-bold';
            }
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
                    alert('⚠️ Debes seleccionar al menos un producto para la oferta.');
                    return false;
                }
                
                // Mostrar mensaje de confirmación
                const submitBtn = document.getElementById('submit-btn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creando oferta...';
                submitBtn.disabled = true;
            });
        });
    </script>
    @endpush
</x-admin-layout>