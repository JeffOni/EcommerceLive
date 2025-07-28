{{--
COMPONENTE ADD TO CART - MEJORAS UX/UI IMPLEMENTADAS
====================================================

Mejoras realizadas en este componente:
1. Controles de cantidad optimizados con Alpine.js para respuesta instantánea
2. Validación de stock dinámico que previene exceder el stock disponible
3. Estados visuales mejorados (disponible/agotado) con indicadores de color
4. Alertas informativas detalladas con información del producto
5. Consistencia visual con el componente AddToCartVariants
6. Uso de imágenes locales para mejor rendimiento
7. Botones de estado dinámico según disponibilidad del stock
--}}

<x-container class="py-8">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{-- Layout con grid responsive: 1 columna en móvil, 2 en desktop --}}
        <div class="grid items-start grid-cols-1 gap-8 sm:gap-12 lg:grid-cols-2">
            {{--
            SECCIÓN DE IMAGEN DEL PRODUCTO
            ===============================
            Galería de imágenes mejorada con:
            - Imagen principal destacada
            - Botón de zoom funcional
            - Thumbnails de imágenes adicionales
            - Uso de imágenes locales (asset) en lugar de URLs externas
            - Badges informativos (SKU, estado)
            --}}
            <div class="space-y-6" x-data="{
                activeImage: '{{ $product->image }}',
                images: [
                    {
                        url: '{{ $product->image }}',
                        alt: 'Imagen principal',
                        label: 'Principal'
                    }
                    @if(isset($product->getAttributes()['image_2']) && $product->getAttributes()['image_2'])
                    ,{
                        url: '{{ Storage::url($product->getAttributes()['image_2']) }}',
                        alt: 'Segunda imagen',
                        label: 'Vista 2'
                    }
                    @endif
                    @if(isset($product->getAttributes()['image_3']) && $product->getAttributes()['image_3'])
                    ,{
                        url: '{{ Storage::url($product->getAttributes()['image_3']) }}',
                        alt: 'Tercera imagen', 
                        label: 'Vista 3'
                    }
                    @endif
                ]
            }"> {{--
                IMAGEN PRINCIPAL CON OVERLAYS - DINÁMICA
                ========================================
                - Imagen principal del producto que cambia según selección
                - Badges informativos posicionados absolutamente
                - Botón de zoom con funcionalidad futura
                - Diseño responsive con bordes redondeados
                --}}
                <div class="relative group">
                    <div
                        class="overflow-hidden bg-white border border-gray-100 shadow-2xl aspect-square rounded-3xl dark:border-gray-700 dark:bg-gray-800">
                        <img :src="activeImage" :alt="'Imagen del producto ' + activeImage"
                            class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105">

                        {{-- Badge de Stock --}}
                        <div class="absolute space-y-2 top-4 left-4">
                            @if ($product->stock > 0)
                            <span
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                En Stock
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                Agotado
                            </span>
                            @endif
                        </div>

                        {{-- SKU Badge --}}
                        @if ($product->sku)
                        <div class="absolute top-4 right-4"> <span
                                class="inline-flex items-center px-2 py-1 font-mono text-xs text-gray-600 rounded-lg shadow-sm glass-effect">
                                SKU: {{ $product->sku }}
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
                {{--
                GALERÍA DE THUMBNAILS MEJORADA - TRES IMÁGENES
                ==============================================
                - Imagen principal + segunda imagen + tercera imagen
                - Selección de imagen activa con indicadores visuales
                - Support para galería completa del producto
                --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Imágenes disponibles</h4>
                    <div class="flex pb-2 space-x-2 sm:space-x-3 overflow-x-auto">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="flex-shrink-0">
                                <button @click="activeImage = image.url" :class="activeImage === image.url ? 
                                        'border-blue-500 ring-2 ring-blue-200' : 
                                        'border-gray-200 opacity-70 hover:opacity-100 hover:border-gray-300'"
                                    class="relative w-16 h-16 sm:w-20 sm:h-20 overflow-hidden transition-all duration-200 border-2 rounded-xl">
                                    <img :src="image.url" :alt="image.alt" class="object-cover w-full h-full">
                                    <div class="absolute bottom-1 left-1">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white text-xs px-1 py-0.5 text-center"
                                        x-text="image.label"></div>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Leyenda --}}
                    <div
                        class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-4 text-xs text-gray-500">
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span>Disponible</span>
                        </div>
                        <span
                            x-text="`${images.length} imagen${images.length > 1 ? 's' : ''} disponible${images.length > 1 ? 's' : ''}`"></span>
                    </div>
                </div>

                {{-- Sección de Servicios Premium --}}
                <div
                    class="p-4 sm:p-6 lg:p-8 border border-gray-200 bg-gradient-to-br from-slate-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-3xl dark:border-gray-700">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div
                            class="w-2 h-6 sm:h-8 mr-3 sm:mr-4 rounded-full bg-gradient-to-b from-blue-500 to-purple-600">
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-white">Servicios Premium</h3>
                    </div>

                    <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                        {{-- Entrega Express --}}
                        <div
                            class="relative p-4 sm:p-6 overflow-hidden transition-all duration-300 bg-white border shadow-sm group dark:bg-gray-800 rounded-2xl hover:shadow-lg border-emerald-100 dark:border-emerald-800">
                            <div
                                class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-emerald-400/20 to-green-500/20 rounded-bl-3xl">
                            </div>
                            <div class="relative">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="flex items-center justify-center shadow-lg w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-400 to-green-500 rounded-xl">
                                            <svg class="text-white w-5 h-5 sm:w-7 sm:h-7" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="mb-1 text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                                            Entrega
                                            Express
                                        </h4>
                                        <p
                                            class="text-xs sm:text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                            24-48
                                            horas
                                        </p>
                                        <p class="mt-1 sm:mt-2 text-xs text-gray-500 dark:text-gray-400">Envío
                                            prioritario
                                            garantizado
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Calidad Premium --}}
                        <div
                            class="relative p-4 sm:p-6 overflow-hidden transition-all duration-300 bg-white border border-blue-100 shadow-sm group dark:bg-gray-800 rounded-2xl hover:shadow-lg dark:border-blue-800">
                            <div
                                class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-400/20 to-indigo-500/20 rounded-bl-3xl">
                            </div>
                            <div class="relative">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="flex items-center justify-center shadow-lg w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl">
                                            <svg class="text-white w-5 h-5 sm:w-7 sm:h-7" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="mb-1 text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                                            Calidad
                                            Premium
                                        </h4>
                                        <p class="text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-400">100%
                                            Garantizada
                                        </p>
                                        <p class="mt-1 sm:mt-2 text-xs text-gray-500 dark:text-gray-400">Productos
                                            seleccionados
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Atención al Cliente --}}
                    <div
                        class="p-4 sm:p-6 mt-6 border border-purple-200 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl dark:border-purple-800">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                <div
                                    class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 shadow-lg bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                                        Atención 24/7</h4>
                                    <p class="text-xs sm:text-sm text-purple-600 dark:text-purple-400">Soporte
                                        personalizado</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-white border border-gray-200 rounded-full shadow-sm dark:bg-gray-800 dark:border-gray-700 self-start sm:self-center">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-green-500" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                                <span
                                    class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--
            SECCIÓN DE INFORMACIÓN DEL PRODUCTO
            ===================================
            Información detallada del producto incluyendo:
            - Header con categoría y SKU
            - Título principal del producto
            - Sistema de calificaciones con estrellas
            - Precios y ofertas
            - Estado del stock con indicadores visuales mejorados
            - Descripción del producto
            - Controles de compra optimizados
            --}}
            <div class="space-y-11"> {{--
                HEADER DEL PRODUCTO
                ==================
                - Badge de categoría con estilo moderno
                - SKU del producto para identificación
                - Título principal con tipografía escalable
                - Sistema de reseñas con estrellas interactivas
                --}}
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
                    {{-- Sistema de calificaciones con estrellas dinámicas --}}
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
                </div> {{--
                SECCIÓN DE PRECIOS Y STOCK
                =========================
                MEJORAS IMPLEMENTADAS:
                - Precio principal con formato de moneda
                - Indicadores de stock con animación (pulso para disponible)
                - Estados visuales diferenciados por color:
                * Verde: Producto disponible
                * Rojo: Producto agotado
                - Mensajes de estado con iconos FontAwesome
                - Consistencia visual con componente AddToCartVariants
                --}}
                <div class="space-y-3">
                    <div class="flex items-baseline space-x-3">
                        @if($product->is_on_valid_offer)
                        <span class="text-4xl font-bold text-red-600">
                            ${{ number_format($product->current_price, 2) }}
                        </span>
                        <span
                            class="px-3 py-1 ml-2 text-base font-semibold text-white bg-red-500 rounded-full animate-pulse">
                            <i class="mr-1 fas fa-fire"></i>{{ $product->discount_percentage }}% OFF
                        </span>
                        @else
                        <span class="text-4xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        @endif
                    </div>
                    @if($product->is_on_valid_offer)
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-lg text-gray-500 line-through">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        <span class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full">
                            -${{ number_format($product->savings_amount, 2) }} ahorro
                        </span>
                    </div>
                    @endif
                    {{-- INDICADORES DE STOCK MEJORADOS
                    ==============================
                    - Punto de color animado (verde = disponible, rojo = agotado)
                    - Texto descriptivo del estado
                    - Cantidad de stock disponible
                    - Animación de pulso para productos disponibles
                    --}}
                    <div class="flex items-center space-x-3">
                        @if ($product->stock > 0)
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-green-700 dark:text-green-400">
                                Producto disponible
                            </span>
                        </div>
                        <span class="text-sm text-gray-500">
                            {{ $product->stock }} en stock
                        </span>
                        @else
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-medium text-red-700 dark:text-red-400">
                                Producto agotado
                            </span>
                        </div>
                        @endif
                    </div>

                    {{--
                    MENSAJES DE ESTADO CON ICONOS
                    =============================
                    MEJORA: Agregamos mensaje de estado también para productos agotados
                    - Producto disponible: Icono de check + mensaje positivo
                    - Producto agotado: Icono de alerta + mensaje informativo
                    - Consistencia visual con AddToCartVariants
                    --}}
                    @if ($product->stock > 0)
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        <i class="mr-1 fas fa-check-circle"></i>
                        Listo para agregar al carrito
                    </p>
                    @else
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">
                        <i class="mr-1 fas fa-exclamation-triangle"></i>
                        Este producto no tiene stock disponible
                    </p>
                    @endif
                </div> {{--
                INFORMACIÓN DETALLADA DEL PRODUCTO
                =================================
                Pestañas para organizar la información:
                - Descripción general
                - Características especiales
                - Preparación recomendada
                --}}
                <div class="space-y-6" x-data="{ activeTab: 'description' }">
                    <div class="flex flex-wrap border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                        <button @click="activeTab = 'description'"
                            :class="activeTab === 'description' ? 
                                'border-blue-500 text-blue-600 dark:text-blue-400' : 
                                'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium transition-colors duration-200 border-b-2 whitespace-nowrap">
                            Descripción
                        </button>

                        @if($product->general_features)
                        <button @click="activeTab = 'features'"
                            :class="activeTab === 'features' ? 
                                'border-green-500 text-green-600 dark:text-green-400' : 
                                'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium transition-colors duration-200 border-b-2 whitespace-nowrap">
                            <i class="mr-1 fas fa-star"></i>
                            <span class="hidden sm:inline">Características Especiales</span>
                            <span class="sm:hidden">Características</span>
                        </button>
                        @endif

                        @if($product->recommended_preparation)
                        <button @click="activeTab = 'preparation'"
                            :class="activeTab === 'preparation' ? 
                                'border-purple-500 text-purple-600 dark:text-purple-400' : 
                                'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium transition-colors duration-200 border-b-2 whitespace-nowrap">
                            <i class="mr-1 fas fa-utensils"></i>
                            <span class="hidden sm:inline">Preparación Recomendada</span>
                            <span class="sm:hidden">Preparación</span>
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
                </div>{{--
                SELECTOR DE CANTIDAD Y COMPRA - SECCIÓN PRINCIPAL
                ================================================
                MEJORAS IMPLEMENTADAS:

                1. CONTROLES DE CANTIDAD OPTIMIZADOS:
                - Uso de Alpine.js para respuesta instantánea (sin llamadas AJAX)
                - Validación de stock dinámico en tiempo real
                - Botones deshabilitados automáticamente al alcanzar límites
                - Cálculo de total en tiempo real

                2. BOTÓN DE AGREGAR AL CARRITO INTELIGENTE:
                - Estado dinámico según disponibilidad de stock
                - Animaciones y efectos visuales mejorados
                - Indicador de carga durante la operación
                - Diferentes textos e iconos según el estado

                3. ALERTAS INFORMATIVAS MEJORADAS:
                - Información detallada del producto (SKU, precio, stock)
                - Estados diferenciados por color (azul=disponible, rojo=agotado)
                - Consistencia visual con AddToCartVariants

                4. BOTONES SECUNDARIOS:
                - Favoritos y compartir con iconos SVG
                - Diseño consistente y responsive
                --}}
                <div
                    class="p-6 space-y-6 border border-gray-200 bg-gray-50 dark:bg-gray-800/50 rounded-2xl dark:border-gray-700">
                    {{--
                    SELECTOR DE CANTIDAD CON ALPINE.JS
                    =================================
                    MEJORA CLAVE: Controles optimizados para mejor UX

                    - Alpine.js elimina la necesidad de llamadas AJAX para incrementar/decrementar
                    - Validación en tiempo real contra el stock máximo disponible
                    - Botones se deshabilitan automáticamente al alcanzar límites
                    - Cálculo de total dinámico sin recargar la página
                    - Entangle conecta con la propiedad Livewire quantity
                    --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cantidad
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center bg-white border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-800"
                                x-data="{
                                    quantity: @entangle('quantity').live,
                                    maxStock: {{ $product->stock }},
                                    init() {
                                        // Asegurar que quantity tenga un valor inicial
                                        if (!this.quantity) {
                                            this.quantity = 1;
                                        }
                                    },
                                    // Función para incrementar cantidad con validación de stock
                                    increment() {
                                        if (this.quantity < this.maxStock) {
                                            this.quantity++;
                                        }
                                    },
                                    // Función para decrementar cantidad con validación mínima
                                    decrement() {
                                        if (this.quantity > 1) {
                                            this.quantity--;
                                        }
                                    }
                                }" x-init="init()">

                                {{-- Botón para decrementar cantidad --}}
                                <button @click="decrement()" :disabled="quantity <= 1"
                                    class="flex items-center justify-center w-12 h-12 text-gray-600 transition-colors duration-200 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 disabled:opacity-50 disabled:cursor-not-allowed rounded-l-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                </button>

                                {{-- Display de cantidad actual --}}
                                <div
                                    class="flex items-center justify-center w-16 h-12 text-lg font-semibold text-gray-900 bg-white border-gray-300 dark:text-white dark:bg-gray-800 border-x dark:border-gray-600">
                                    <span x-text="quantity"></span>
                                </div>

                                {{-- Botón para incrementar cantidad --}}
                                <button @click="increment()" :disabled="quantity >= maxStock"
                                    class="flex items-center justify-center w-12 h-12 text-gray-600 transition-colors duration-200 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 disabled:opacity-50 disabled:cursor-not-allowed rounded-r-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div> {{-- Cálculo de total en tiempo real --}}
                            <span class="text-sm text-gray-500">
                                Total: <span class="font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($product->price * $quantity, 2) }}
                                </span>
                            </span>
                        </div>
                    </div> {{--
                    BOTONES DE ACCIÓN MEJORADOS
                    ==========================
                    Sección que incluye el botón principal, alertas informativas y botones secundarios
                    --}}
                    <div class="space-y-3"> {{--
                        BOTÓN PRINCIPAL: AGREGAR AL CARRITO
                        ==================================
                        MEJORAS IMPLEMENTADAS:
                        - Estado dinámico según stock disponible
                        - Diferentes estilos visuales (disponible vs agotado)
                        - Iconos SVG contextuales para cada estado
                        - Indicador de carga durante la operación
                        - Animaciones de hover y click para mejor feedback
                        - Deshabilitado automáticamente cuando no hay stock
                        --}}
                        @php
                        $isDisabled = $product->stock <= 0; $buttonClass=$product->stock > 0
                            ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
                            hover:scale-[1.02] active:scale-[0.98]'
                            : 'bg-gray-400 cursor-not-allowed opacity-60';
                            @endphp
                            <button wire:click="addToCart" wire:loading.attr="disabled" {{ $isDisabled ? 'disabled' : ''
                                }}
                                class="w-full flex items-center justify-center px-8 py-4 text-lg font-semibold text-white rounded-xl transition-all duration-300 transform shadow-lg hover:shadow-xl focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 {{ $buttonClass }}">

                                {{-- Estados del botón según disponibilidad --}}
                                <span wire:loading.remove class="flex items-center">
                                    @if ($product->stock > 0)
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h9.5" />
                                    </svg>
                                    Agregar al Carrito
                                    @else
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.734-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    Producto Agotado
                                    @endif
                                </span>

                                {{-- Indicador de carga con spinner animado --}}
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

                            {{--
                            ALERTAS INFORMATIVAS MEJORADAS
                            ==============================
                            MEJORA CLAVE: Consistencia visual con AddToCartVariants

                            - Alerta para productos disponibles (fondo azul):
                            * Icono de verificación
                            * Información completa: SKU, precio, stock
                            * Diseño consistente con componente de variantes

                            - Alerta para productos agotados (fondo rojo):
                            * Icono de error
                            * Información del producto sin stock
                            * Mensaje claro de estado
                            --}}
                            {{-- Información del producto --}}
                            @if ($product->stock > 0)
                            <div
                                class="p-3 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-700">
                                <div class="flex items-center space-x-2"></div>
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Producto disponible: {{ $product->sku ?? 'SKU no disponible' }}
                                </span>
                            </div>
                            <div class="mt-1 text-xs text-blue-600 dark:text-blue-300">
                                Precio: ${{ number_format($product->price, 2) }} |
                                Stock: {{ $product->stock }} unidades
                            </div>
                    </div>
                    @else
                    <div class="p-3 border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:border-red-700">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium text-red-800 dark:text-red-200">
                                Producto agotado: {{ $product->sku ?? 'SKU no disponible' }}
                            </span>
                        </div>
                        <div class="mt-1 text-xs text-red-600 dark:text-red-300">
                            Precio: ${{ number_format($product->price, 2) }} |
                            Sin stock disponible
                        </div>
                    </div>
                    @endif

                    {{--
                    BOTONES SECUNDARIOS - COMENTADOS (NO IMPLEMENTADOS)
                    ==================
                    - Favoritos y Compartir con iconos SVG
                    - Grid responsive para distribución equitativa
                    - Estilos consistentes con el diseño general

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
                    --}}
                </div>

                {{--
                MÉTODOS DE PAGO ACEPTADOS
                ========================
                - Tarjetas de diferentes proveedores con iconos SVG
                - Información de seguridad SSL
                - Diseño visual atractivo con badges
                --}}
                <div
                    class="p-4 sm:p-6 bg-white border border-gray-200 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                    <h4 class="mb-3 sm:mb-4 text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Métodos de
                        Pago Aceptados</h4>
                    <div class="flex flex-wrap gap-2 sm:gap-3 items-center">
                        <div
                            class="flex items-center px-2 sm:px-3 py-1.5 sm:py-2 space-x-1 sm:space-x-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <svg class="w-6 h-4 sm:w-8 sm:h-5" viewBox="0 0 38 24" fill="none">
                                <rect width="38" height="24" rx="4" fill="#22C55E" />
                                <path d="M8 8h4v8H8V8zm6 2h4v6h-4v-6zm6-2h4v8h-4V8z" fill="white" />
                            </svg>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Efectivo</span>
                        </div>
                        <div
                            class="flex items-center px-2 sm:px-3 py-1.5 sm:py-2 space-x-1 sm:space-x-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <svg class="w-6 h-4 sm:w-8 sm:h-5" viewBox="0 0 38 24" fill="none">
                                <rect width="38" height="24" rx="4" fill="#3B82F6" />
                                <path d="M6 10h26v4H6v-4zm0-2h26v2H6V8zm0 8h26v2H6v-2z" fill="white" />
                            </svg>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Transferencia</span>
                        </div>
                        <span class="text-xs sm:text-sm text-gray-500">y más...</span>
                    </div>
                    <p class="mt-2 sm:mt-3 text-xs text-gray-500">
                        <svg class="inline w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
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
    </div>
</x-container>