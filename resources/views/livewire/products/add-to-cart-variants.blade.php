<x-container class="py-8">
    <div class="mx-auto max-w-7xl">
        <div class="grid items-start grid-cols-1 gap-12 lg:grid-cols-2">

            {{-- Sección de Imagen del Producto --}}
            <div class="space-y-6">
                <div x-data="{ 
                    currentImage: '{{ $this->availableImages[0]['url'] ?? '' }}'
                }" x-init="
                    // Buscar imagen de variante al inicializar
                    const availableImages = @js($this->availableImages);
                    const variantImage = availableImages.find(img => img.type === 'variant');
                    if (variantImage) {
                        currentImage = variantImage.url;
                    }
                " wire:key="image-gallery-{{ md5(json_encode($this->selectedFeatures)) }}">
                    {{-- Imagen Principal --}}
                    <div class="relative group">
                        <div
                            class="overflow-hidden bg-white border border-gray-100 shadow-2xl aspect-square rounded-3xl dark:border-gray-700 dark:bg-gray-800">
                            <img :src="currentImage" alt="{{ $product->name }}"
                                class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105">

                            {{-- Badge de Variant Info --}}
                            <div class="absolute space-y-2 top-4 left-4">
                                @if ($this->variant)
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Variante Seleccionada
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-orange-800 bg-orange-100 rounded-full dark:bg-orange-900 dark:text-orange-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Selecciona opciones
                                </span>
                                @endif
                            </div>

                            {{-- SKU Badge --}}
                            @if ($this->variantInfo['sku'])
                            <div class="absolute top-4 right-4">
                                <span
                                    class="inline-flex items-center px-2 py-1 font-mono text-xs text-gray-600 rounded-lg shadow-sm glass-effect">
                                    SKU: {{ $this->variantInfo['sku'] }}
                                </span>
                            </div>
                            @endif

                            {{-- Botón de Zoom --}}
                            <div
                                class="absolute transition-opacity duration-300 opacity-0 bottom-4 right-4 group-hover:opacity-100">
                                <button
                                    class="p-2 transition-colors duration-200 rounded-full shadow-lg glass-effect hover:bg-white">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Galería de Imágenes Mejorada - TRES IMÁGENES --}}
                    <div class="space-y-3 mt-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Imágenes disponibles</h4>
                        <div class="flex pb-2 space-x-3 overflow-x-auto">
                            @foreach ($this->availableImages as $index => $image)
                            <div class="flex-shrink-0">
                                <button type="button" @click="currentImage = '{{ $image['url'] }}'"
                                    :class="currentImage === '{{ $image['url'] }}' ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200 opacity-70 hover:opacity-100 hover:border-gray-300'"
                                    class="relative w-20 h-20 overflow-hidden border-2 rounded-xl transition-all duration-200">
                                    <img src="{{ $image['url'] }}" alt="Vista {{ $index + 1 }}"
                                        class="object-cover w-full h-full">

                                    {{-- Badge de tipo de imagen --}}
                                    <div class="absolute bottom-1 left-1">
                                        @if ($image['type'] === 'variant')
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        @elseif($image['type'] === 'product')
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        @endif
                                    </div>

                                    {{-- Label de imagen --}}
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white text-xs px-1 py-0.5 text-center">
                                        @if ($image['type'] === 'variant')
                                        Variante
                                        @elseif($image['label'])
                                        {{ $image['label'] }}
                                        @else
                                        Vista {{ $index + 1 }}
                                        @endif
                                    </div>
                                </button>
                            </div>
                            @endforeach
                        </div>

                        {{-- Leyenda --}}
                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                            <div class="flex items-center space-x-1">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span>Variante</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span>Producto</span>
                            </div>
                            <span>{{ count($this->availableImages) }} imagen{{ count($this->availableImages) > 1 ? 's' :
                                '' }} disponible{{ count($this->availableImages) > 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sección de Información del Producto --}}
            <div class="space-y-8">
                {{-- Header del Producto --}}
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <span
                            class="px-3 py-1 text-sm font-medium text-blue-600 rounded-full dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30">
                            {{ $product->subcategory->category->name }}
                        </span>
                        <span class="text-sm text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</span>
                    </div>

                    <h1 class="text-3xl font-bold leading-tight text-gray-900 lg:text-4xl dark:text-white">
                        {{ $product->name }}
                    </h1>

                    {{-- Calificaciones --}}
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-1">
                            @for ($i = 1; $i <= 5; $i++) <svg
                                class="w-5 h-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @endfor
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">4.7</span>
                        <span class="text-sm text-gray-500">(127 reseñas)</span>
                        <button class="text-sm font-medium text-blue-600 hover:text-blue-700">Ver reseñas</button>
                    </div>
                </div>

                {{-- Precio Dinámico --}}
                <div class="space-y-3">
                    <div class="flex items-baseline space-x-3">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($this->variantInfo['price'], 2) }}
                        </span>
                        @if ($this->variant && $this->variantInfo['price'] != $product->price)
                        {{-- Mostrar precio base cuando hay diferencia --}}
                        <span class="text-xl text-gray-500 line-through">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        @php
                        $discount = (($product->price - $this->variantInfo['price']) / $product->price) * 100;
                        @endphp
                        @if ($discount > 0)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            -{{ number_format($discount, 0) }}%
                        </span>
                        @endif
                        @endif
                    </div>

                    {{-- Estado del variant --}}
                    <div class="flex items-center space-x-3">
                        @if ($this->variantInfo['available'] && $this->variantInfo['hasStock'])
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-green-700 dark:text-green-400">
                                Variante disponible
                            </span>
                        </div>
                        <span class="text-sm text-gray-500">
                            {{ $this->variantInfo['stock'] }} en stock
                        </span>
                        @elseif ($this->variantInfo['available'] && !$this->variantInfo['hasStock'])
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-medium text-red-700 dark:text-red-400">
                                Variante agotada
                            </span>
                        </div>
                        @else
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                            <span class="text-sm font-medium text-orange-700 dark:text-orange-400">
                                Selecciona todas las opciones
                            </span>
                        </div>
                        @endif
                    </div>

                    @if ($this->variant && $this->variantInfo['hasStock'])
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        <i class="mr-1 fas fa-check-circle"></i>
                        Configuración válida - Listo para agregar al carrito
                    </p>
                    @elseif ($this->variant && !$this->variantInfo['hasStock'])
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">
                        <i class="mr-1 fas fa-exclamation-triangle"></i>
                        Esta variante no tiene stock disponible
                    </p>
                    @endif
                </div>

                {{--
                INFORMACIÓN DETALLADA DEL PRODUCTO - CON VARIANTES
                ================================================
                Pestañas para organizar la información:
                - Descripción general
                - Características especiales
                - Preparación recomendada
                --}}
                <div class="space-y-6" x-data="{ activeTab: 'description' }">
                    <div class="flex border-b border-gray-200 dark:border-gray-700">
                        <button @click="activeTab = 'description'"
                            :class="activeTab === 'description' ? 
                                'border-blue-500 text-blue-600 dark:text-blue-400' : 
                                'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2">
                            Descripción
                        </button>

                        @if($product->general_features)
                        <button @click="activeTab = 'features'"
                            :class="activeTab === 'features' ? 
                                'border-green-500 text-green-600 dark:text-green-400' : 
                                'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2">
                            <i class="mr-1 fas fa-star"></i>
                            Características Especiales
                        </button>
                        @endif

                        @if($product->recommended_preparation)
                        <button @click="activeTab = 'preparation'"
                            :class="activeTab === 'preparation' ? 
                                'border-purple-500 text-purple-600 dark:text-purple-400' : 
                                'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2">
                            <i class="mr-1 fas fa-utensils"></i>
                            Preparación Recomendada
                        </button>
                        @endif
                    </div>

                    <div class="min-h-[120px]">
                        {{-- Tab: Descripción --}}
                        <div x-show="activeTab === 'description'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-3">
                            <div class="flex items-center mb-3 space-x-2">
                                <div class="w-1 h-6 bg-blue-500 rounded-full"></div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Descripción</h3>
                            </div>
                            <p class="leading-relaxed text-gray-600 dark:text-gray-300">
                                {{ $product->description ?: 'Sin descripción disponible.' }}
                            </p>
                        </div>

                        {{-- Tab: Características Especiales --}}
                        @if($product->general_features)
                        <div x-show="activeTab === 'features'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-3">
                            <div class="flex items-center mb-3 space-x-2">
                                <div class="w-1 h-6 bg-green-500 rounded-full"></div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <i class="mr-2 text-green-500 fas fa-star"></i>
                                    Características Especiales
                                </h3>
                            </div>
                            <div
                                class="p-4 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-700">
                                <p class="leading-relaxed text-green-800 dark:text-green-200">
                                    {{ $product->general_features }}
                                </p>
                            </div>
                        </div>
                        @endif

                        {{-- Tab: Preparación Recomendada --}}
                        @if($product->recommended_preparation)
                        <div x-show="activeTab === 'preparation'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-3">
                            <div class="flex items-center mb-3 space-x-2">
                                <div class="w-1 h-6 bg-purple-500 rounded-full"></div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <i class="mr-2 text-purple-500 fas fa-utensils"></i>
                                    Preparación Recomendada
                                </h3>
                            </div>
                            <div
                                class="p-4 border border-purple-200 rounded-lg bg-purple-50 dark:bg-purple-900/20 dark:border-purple-700">
                                <p class="leading-relaxed text-purple-800 dark:text-purple-200">
                                    {{ $product->recommended_preparation }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Selector de Cantidad y Compra --}}
                <div
                    class="p-6 space-y-6 border border-gray-200 bg-gray-50 dark:bg-gray-800/50 rounded-2xl dark:border-gray-700">
                    {{-- Selector de Cantidad --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cantidad
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center bg-white border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-800"
                                x-data="{
                                    quantity: @entangle('quantity'),
                                    maxStock: {{ $this->variantInfo['stock'] }},
                                    hasStock: {{ $this->variantInfo['hasStock'] ? 'true' : 'false' }},
                                    variantAvailable: {{ $this->variantInfo['available'] ? 'true' : 'false' }},
                                    increment() {
                                        if (this.variantAvailable && this.hasStock && this.quantity < this.maxStock) {
                                            this.quantity++;
                                        }
                                    },
                                    decrement() {
                                        if (this.quantity > 1) {
                                            this.quantity--;
                                        }
                                    }
                                }"
                                wire:key="quantity-controls-{{ $this->variant ? $this->variant->id : 'no-variant' }}">
                                <button @click="decrement()" :disabled="quantity <= 1"
                                    class="flex items-center justify-center w-12 h-12 text-gray-600 transition-colors duration-200 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 disabled:opacity-50 disabled:cursor-not-allowed rounded-l-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                </button>

                                <div
                                    class="flex items-center justify-center w-16 h-12 text-lg font-semibold text-gray-900 bg-white border-gray-300 dark:text-white dark:bg-gray-800 border-x dark:border-gray-600">
                                    <span x-text="quantity"></span>
                                </div>

                                <button @click="increment()"
                                    :disabled="!variantAvailable || !hasStock || quantity >= maxStock"
                                    class="flex items-center justify-center w-12 h-12 text-gray-600 transition-colors duration-200 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 disabled:opacity-50 disabled:cursor-not-allowed rounded-r-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>

                            <span class="text-sm text-gray-500">
                                Total: <span class="font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($this->variantInfo['price'] * $quantity, 2) }}
                                </span>
                            </span>
                        </div>
                    </div>

                    {{-- Selector de Variantes Mejorado --}}
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configurar Producto</h3>
                        @foreach ($product->options as $option)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $option->name }}
                                </label>
                                @if (isset($selectedFeatures[$option->id]))
                                @php
                                $selectedFeature = collect($option->pivot->features)->firstWhere(
                                'id',
                                $selectedFeatures[$option->id],
                                );
                                @endphp
                                @if ($selectedFeature)
                                <span class="text-xs font-medium text-blue-600">
                                    {{ $selectedFeature['description'] }}
                                </span>
                                @endif
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach ($option->pivot->features as $feature)
                                <div class="relative">
                                    @switch($option->type)
                                    @case(1)
                                    {{-- Selector de Texto/Talla --}}
                                    <button
                                        wire:click="$set('selectedFeatures.{{ $option->id }}', '{{ $feature['id'] }}')"
                                        class="w-full px-3 py-2 text-sm font-medium transition-all duration-200 border rounded-lg {{ $selectedFeatures[$option->id] === $feature['id']
                                                            ? 'bg-blue-600 text-white border-blue-600 shadow-lg scale-105'
                                                            : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:bg-blue-50' }} dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                                        {{ $feature['value'] }}
                                    </button>
                                    @break

                                    @case(2)
                                    {{-- Selector de Color Mejorado --}}
                                    <button
                                        wire:click="$set('selectedFeatures.{{ $option->id }}', '{{ $feature['id'] }}')"
                                        class="relative w-full h-12 rounded-lg border-2 transition-all duration-200 {{ $selectedFeatures[$option->id] === $feature['id']
                                                            ? 'border-blue-600 shadow-lg scale-105'
                                                            : 'border-gray-300 hover:border-blue-400' }}"
                                        style="background: linear-gradient(135deg, {{ $feature['value'] }}, {{ $feature['value'] }}dd);"
                                        title="{{ $feature['description'] }}">

                                        {{-- Checkmark cuando está seleccionado --}}
                                        @if ($selectedFeatures[$option->id] === $feature['id'])
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white drop-shadow-lg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        @endif

                                        {{-- Nombre del color --}}
                                        <div class="absolute transform -translate-x-1/2 -bottom-6 left-1/2">
                                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $feature['description'] }}
                                            </span>
                                        </div>
                                    </button>
                                    @break

                                    @default
                                    {{-- Selector genérico --}}
                                    <button
                                        wire:click="$set('selectedFeatures.{{ $option->id }}', '{{ $feature['id'] }}')"
                                        class="w-full px-3 py-2 text-sm font-medium transition-all duration-200 border rounded-lg {{ $selectedFeatures[$option->id] === $feature['id']
                                                            ? 'bg-blue-600 text-white border-blue-600 shadow-lg'
                                                            : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400' }}">
                                        {{ $feature['description'] }}
                                    </button>
                                    @endswitch
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div> {{-- Botones de Acción Mejorados --}}
                    <div class="space-y-3">
                        {{-- Botón Principal: Agregar al Carrito --}}
                        @php
                        $isDisabled = !$this->variantInfo['available'] || !$this->variantInfo['hasStock'];
                        $buttonClass = $this->variantInfo['available'] && $this->variantInfo['hasStock']
                        ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
                        hover:scale-[1.02] active:scale-[0.98]'
                        : 'bg-gray-400 cursor-not-allowed opacity-60';
                        @endphp
                        <button wire:click="addToCart" wire:loading.attr="disabled" {{ $isDisabled ? 'disabled' : '' }}
                            class="w-full flex items-center justify-center px-8 py-4 text-lg font-semibold text-white
                            rounded-xl transition-all duration-300 transform shadow-lg hover:shadow-xl focus:ring-4
                            focus:ring-blue-300 dark:focus:ring-blue-800 {{ $buttonClass }}">

                            <span wire:loading.remove class="flex items-center">
                                @if ($this->variantInfo['available'] && $this->variantInfo['hasStock'])
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h9.5" />
                                </svg>
                                Agregar al Carrito
                                @elseif ($this->variantInfo['available'] && !$this->variantInfo['hasStock'])
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.734-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Producto Agotado
                                @else
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.734-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Selecciona todas las opciones
                                @endif
                            </span>

                            <span wire:loading class="flex items-center">
                                <svg class="w-5 h-5 mr-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Agregando...
                            </span>
                        </button>

                        {{-- Información del variant seleccionado --}}
                        @if ($this->variant)
                        @if ($this->variantInfo['hasStock'])
                        <div
                            class="p-3 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-700">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Variante seleccionada: {{ $this->variantInfo['sku'] }}
                                </span>
                            </div>
                            <div class="mt-1 text-xs text-blue-600 dark:text-blue-300">
                                Precio: ${{ number_format($this->variantInfo['price'], 2) }} |
                                Stock: {{ $this->variantInfo['stock'] }} unidades
                            </div>
                        </div>
                        @else
                        <div
                            class="p-3 border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:border-red-700">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Variante agotada: {{ $this->variantInfo['sku'] }}
                                </span>
                            </div>
                            <div class="mt-1 text-xs text-red-600 dark:text-red-300">
                                Precio: ${{ number_format($this->variantInfo['price'], 2) }} |
                                Sin stock disponible
                            </div>
                        </div>
                        @endif
                        @endif

                        {{-- Botones Secundarios --}}
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                class="flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Favoritos
                            </button>
                            <button
                                class="flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                </svg>
                                Compartir
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Información de Entrega y Garantías --}}
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Entrega --}}
                    <div
                        class="p-6 space-y-4 border border-green-200 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl dark:border-green-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-green-100 dark:bg-green-800 rounded-xl">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-green-800 dark:text-green-200">Entrega Rápida</h4>
                                <p class="text-sm text-green-700 dark:text-green-300">2-3 días hábiles</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-green-700 dark:text-green-300">Envío estándar</span>
                            <span class="font-semibold text-green-800 dark:text-green-200">GRATIS</span>
                        </div>
                    </div>

                    {{-- Garantía --}}
                    <div
                        class="p-6 space-y-4 border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl dark:border-blue-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-800 rounded-xl">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-blue-800 dark:text-blue-200">Garantía Extendida</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300">30 días de devolución</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-blue-700 dark:text-blue-300">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Protección contra defectos
                            </div>
                            <div class="flex items-center text-sm text-blue-700 dark:text-blue-300">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Soporte técnico gratuito
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Métodos de Pago --}}
                <div
                    class="p-6 bg-white border border-gray-200 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                    <h4 class="mb-4 font-semibold text-gray-900 dark:text-white">Métodos de Pago Aceptados</h4>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center px-3 py-2 space-x-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <svg class="w-8 h-5" viewBox="0 0 38 24" fill="none">
                                <rect width="38" height="24" rx="4" fill="#1A1F71" />
                                <path d="M10 8h4v8h-4V8zm6 0h4v8h-4V8zm6 0h4v8h-4V8z" fill="white" />
                            </svg>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Visa</span>
                        </div>
                        <div class="flex items-center px-3 py-2 space-x-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <svg class="w-8 h-5" viewBox="0 0 38 24" fill="none">
                                <rect width="38" height="24" rx="4" fill="#EB001B" />
                                <circle cx="15" cy="12" r="7" fill="#FF5F00" />
                                <circle cx="23" cy="12" r="7" fill="#F79E1B" />
                            </svg>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Mastercard</span>
                        </div>
                        <div class="flex items-center px-3 py-2 space-x-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <svg class="w-8 h-5" viewBox="0 0 38 24" fill="none">
                                <rect width="38" height="24" rx="4" fill="#003087" />
                                <path d="M8 8h8v8H8V8z" fill="#0070BA" />
                                <path d="M22 8h8v8h-8V8z" fill="#FFC439" />
                            </svg>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">PayPal</span>
                        </div>
                        <span class="text-sm text-gray-500">y más...</span>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">
                        <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Transacciones 100% seguras con encriptación SSL
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-container>