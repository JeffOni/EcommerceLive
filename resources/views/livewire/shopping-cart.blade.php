{{-- 
    VISTA DEL CARRITO DE COMPRAS - COMPONENTE LIVEWIRE
    ==================================================
    
    Esta vista maneja la presentación completa del carrito de compras con:
    
    CARACTERÍSTICAS PRINCIPALES:
    - Diseño moderno con gradientes y efectos glassmorphism
    - Lista detallada de productos con imágenes y controles de cantidad
    - Resumen de compra con cálculos automáticos (subtotal, impuestos, total)
    - Estados vacío y con productos diferenciados
    - Controles interactivos para modificar cantidades
    - Botones para eliminar productos individuales o limpiar todo el carrito
    - Efectos visuales y animaciones para mejorar la experiencia de usuario
    
    ESTRUCTURA:
    1. Fondo decorativo con elementos glassmorphism
    2. Header principal con título e información del carrito
    3. Grid responsive: Lista de productos (2/3) + Resumen (1/3)
    4. Productos individuales con controles de cantidad
    5. Panel de resumen con totales y botón de pago
    
    @author Tu Nombre
    @version 1.0
--}}

<!-- Fondo moderno con gradiente y elementos decorativos -->
<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100">
    {{-- 
        ELEMENTOS DECORATIVOS DE FONDO
        =============================
        Crean un efecto visual moderno con formas difusas y gradientes
        que se posicionan absolutamente sin interferir con el contenido
    --}}
    <!-- Elementos decorativos de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        {{-- Círculo decorativo superior derecho --}}
        <div
            class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-indigo-400/20 to-purple-500/10 blur-3xl animate-pulse">
        </div>
        {{-- Círculo decorativo inferior izquierdo --}}
        <div
            class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-400/20 to-pink-500/10 blur-3xl animate-pulse">
        </div>
        {{-- Elementos decorativos medianos para dar profundidad --}}
        <div
            class="absolute w-32 h-32 rounded-full top-1/3 right-1/4 bg-gradient-to-r from-blue-300/30 to-indigo-400/20 blur-2xl">
        </div>
        <div
            class="absolute w-24 h-24 rounded-full bottom-1/3 left-1/4 bg-gradient-to-r from-emerald-300/30 to-blue-400/20 blur-xl">
        </div>
    </div>

    <div class="relative px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{-- 
            HEADER PRINCIPAL DEL CARRITO
            ===========================
            Sección centrada que incluye:
            - Icono principal del carrito con gradiente
            - Título principal con efecto de texto transparente
            - Contador de productos con estilo glassmorphism
        --}}
        <!-- Header mejorado del carrito -->
        <div class="mb-12 text-center">
            {{-- Icono principal con gradiente y sombra --}}
            <div
                class="inline-flex items-center justify-center w-16 h-16 mb-4 shadow-lg bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl">
                <i class="text-2xl text-white fas fa-shopping-cart"></i>
            </div>
            {{-- Título principal con efecto degradado en el texto --}}
            <h1
                class="mb-3 text-5xl font-bold text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text">
                Carrito de Compras
            </h1>
            {{-- Badge informativo con contador de productos --}}
            <div class="inline-flex items-center px-4 py-2 border rounded-full shadow-md glass-effect border-white/40">
                <i class="mr-2 text-indigo-500 fas fa-shopping-bag"></i>
                <span class="font-medium text-gray-700">
                    {{ Cart::count() }} {{ Cart::count() === 1 ? 'artículo' : 'artículos' }} en tu carrito
                </span>
            </div>
        </div>

        {{-- 
            LAYOUT PRINCIPAL - GRID RESPONSIVE
            ==================================
            Distribución en grid que se adapta a diferentes tamaños de pantalla:
            - 1 columna en móviles
            - 3 columnas en desktop (2 para productos + 1 para resumen)
        --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {{-- 
                SECCIÓN DE PRODUCTOS DEL CARRITO
                ================================
                Ocupa 2/3 del espacio en desktop y columna completa en móvil
            --}}
            <!-- Lista de productos del carrito -->
            <div class="lg:col-span-2">
                {{-- Contenedor principal con efecto glassmorphism --}}
                <!-- Contenedor principal con backdrop blur -->
                <div class="overflow-hidden border shadow-2xl backdrop-blur-sm bg-white/70 rounded-3xl border-white/20">
                    {{-- 
                        HEADER DE LA SECCIÓN DE PRODUCTOS
                        ================================
                        Incluye título, descripción y botón para limpiar carrito
                    --}}
                    <!-- Header de la sección -->
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                {{-- Icono con efecto glassmorphism --}}
                                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <i class="text-lg text-white fas fa-list"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Productos Seleccionados</h2>
                                    <p class="text-sm text-indigo-100">Revisa y modifica tu selección</p>
                                </div>
                            </div>
                            {{-- Botón para limpiar carrito (solo visible si hay productos) --}}
                            @if (Cart::count() > 0)
                                <button wire:click="clearCart"
                                    class="inline-flex items-center px-4 py-2 font-medium text-white transition-all duration-200 border rounded-lg bg-red-500/20 hover:bg-red-500/30 backdrop-blur-sm border-red-300/30">
                                    <i class="mr-2 fas fa-trash-alt"></i>
                                    Limpiar Carrito
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- 
                        CONTENIDO DE PRODUCTOS
                        =====================
                        Lista de productos con @forelse para manejar estado vacío
                    --}}
                    <!-- Contenido de productos -->
                    <div class="p-6">
                        @forelse (Cart::content() as $item)
                            {{-- 
                                PRODUCTO INDIVIDUAL
                                ==================
                                Cada producto incluye:
                                - Efectos visuales y animaciones
                                - Imagen del producto
                                - Información detallada
                                - Controles de cantidad
                                - Botón de eliminación
                                - Cálculo de subtotal
                            --}}
                            <!-- Producto individual con animaciones premium -->
                            <div
                                class="relative p-6 mb-6 overflow-hidden border shadow-lg group bg-gradient-to-br from-white via-blue-50/30 to-indigo-50/50 rounded-3xl border-white/60 shadow-gray-400 last:mb-0 backdrop-blur-sm">
                                {{-- Efecto de brillo que se activa en hover --}}
                                <!-- Efecto de brillo dinámico -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 group-hover:opacity-100 transform -skew-x-12 translate-x-[-100%] group-hover:translate-x-[200%] transition-all duration-1000">
                                </div>
                                {{-- Indicadores de estado en la esquina superior derecha --}}
                                <!-- Indicadores de estado múltiples -->
                                <div class="absolute flex items-center space-x-2 top-4 right-4">
                                    <div
                                        class="w-3 h-3 rounded-full shadow-lg bg-gradient-to-r from-green-400 to-emerald-500 ">
                                    </div>
                                    <div
                                        class="w-2 h-2 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 opacity-60">
                                    </div>
                                </div>
                                {{-- Badge premium en la esquina superior izquierda --}}
                                <!-- Badge de premium en esquina -->
                                <div class="absolute transform -translate-y-1/2 top-5 left-6">
                                    <div
                                        class="px-3 py-1 text-xs font-bold text-white rounded-full shadow-lg bg-gradient-to-r from-amber-400 to-orange-500">
                                        <i class="mr-1 fas fa-star"></i>
                                        PREMIUM
                                    </div>
                                </div>

                                {{-- 
                                    LAYOUT PRINCIPAL DEL PRODUCTO
                                    ============================
                                    Flexible layout que se adapta a móvil y desktop
                                --}}
                                <div
                                    class="flex flex-col items-start space-y-6 lg:flex-row lg:items-center lg:space-y-0 lg:space-x-8">
                                    {{-- 
                                        IMAGEN DEL PRODUCTO
                                        ==================
                                        Sección de imagen con efectos hover y badge de verificación
                                    --}}
                                    <!-- Imagen del producto mejorada -->
                                    <div class="relative flex-shrink-0">
                                        <div
                                            class="overflow-hidden transition-all duration-300 shadow-xl w-28 h-28 lg:w-36 lg:h-36 rounded-2xl ">
                                            {{-- Mostrar imagen del producto si existe, sino placeholder --}}
                                            @if ($item->options->image)
                                                <img class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                                                    src="{{ $item->options->image }}" alt="{{ $item->name }}">
                                            @else
                                                {{-- Placeholder cuando no hay imagen disponible --}}
                                                <div
                                                    class="flex items-center justify-center w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100">
                                                    <i class="text-3xl text-indigo-400 fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Badge de verificación/estado del producto --}}
                                        <!-- Badge de descuento (decorativo) -->
                                        <div
                                            class="absolute flex items-center justify-center w-8 h-8 rounded-full shadow-lg -top-2 -left-2 bg-gradient-to-r from-emerald-500 to-green-500">
                                            <i class="text-xs text-white fas fa-check"></i>
                                        </div>
                                    </div>

                                    {{-- 
                                        INFORMACIÓN DEL PRODUCTO
                                        =======================
                                        Sección que contiene nombre, precio y botón de eliminación
                                    --}}
                                    <!-- Información del producto mejorada -->
                                    <div class="flex-1 min-w-0 space-y-3">
                                        <div class="flex items-start justify-between">
                                            {{-- Nombre del producto con enlace --}}
                                            <h3
                                                class="mb-2 text-xl font-bold text-gray-900 underline transition-colors duration-300 hover:text-indigo-600">
                                                <a href="{{ route('products.show', $item->id) }}"
                                                    class="underline transition-colors duration-200 hover:text-indigo-600 line-clamp-2">
                                                    {{ $item->name }}
                                                </a>
                                            </h3>
                                        </div>
                                        {{-- Precio por unidad --}}
                                        <div class="flex items-center mb-3 space-x-4">
                                            <span class="text-xl font-bold text-green-600">
                                                ${{ number_format($item->price, 2) }}
                                            </span>
                                            <span class="text-sm text-gray-500">por unidad</span>
                                        </div>
                                        {{-- Botón para eliminar producto del carrito --}}
                                        <button wire:click="removeItem('{{ $item->rowId }}')"
                                            class="inline-flex items-center text-sm text-red-500 transition-colors duration-200 hover:text-red-700">
                                            <i class="mr-2 fas fa-trash"></i>
                                            Quitar del carrito
                                        </button>
                                    </div> {{-- 
                                        CONTROLES DE CANTIDAD
                                        ====================
                                        Sección con controles para modificar la cantidad del producto
                                        y mostrar el subtotal calculado dinámicamente
                                    --}}
                                    <!-- Controles de cantidad con animaciones premium -->
                                    <div class="flex-shrink-0">
                                        <div class="space-y-3">
                                            {{-- Etiqueta con gradiente --}}
                                            <label
                                                class="block text-sm font-bold text-center text-transparent text-gray-700 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text">
                                                Cantidad
                                            </label>
                                            {{-- Controles de incremento/decremento --}}
                                            <div class="flex items-center justify-center">
                                                <div
                                                    class="flex items-center bg-white border border-gray-300 rounded-xl">
                                                    {{-- Botón decrementar cantidad --}}
                                                    <!-- Botón decrementar -->
                                                    <button x-on:click="$wire.decreaseQuantity('{{ $item->rowId }}')"
                                                        :disabled="{{ $item->qty }} <= 1"
                                                        class="flex items-center justify-center w-12 h-12 text-gray-600 transition-colors duration-200 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed rounded-l-xl hover:bg-gray-50">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M20 12H4" />
                                                        </svg>
                                                    </button>
                                                    {{-- Display de cantidad actual --}}
                                                    <!-- Display de cantidad -->
                                                    <div
                                                        class="flex items-center justify-center w-16 h-12 text-lg font-semibold text-gray-900 bg-white border-gray-300 border-x">
                                                        <span>{{ $item->qty }}</span>
                                                    </div>
                                                    {{-- Botón incrementar cantidad --}}
                                                    <!-- Botón incrementar -->
                                                    <button x-on:click="$wire.increaseQuantity('{{ $item->rowId }}')"
                                                        :disabled="{{ $item->qty }} >= {{ $item->options->stock }}"
                                                        class="flex items-center justify-center w-12 h-12 text-gray-600 transition-colors duration-200 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed rounded-r-xl hover:bg-gray-50">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div> {{-- 
                                                SUBTOTAL CON EFECTOS VISUALES
                                                ============================
                                                Muestra el cálculo automático del subtotal por producto
                                                con sugerencias de ahorro
                                            --}}
                                            <!-- Subtotal con efectos visuales -->
                                            <div
                                                class="p-3 text-center border bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-green-200/50">
                                                <div class="mb-1 text-xs font-medium text-gray-500">Subtotal</div>
                                                {{-- Precio total del producto (precio × cantidad) --}}
                                                <div
                                                    class="text-lg font-bold text-transparent bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text">
                                                    ${{ number_format($item->price * $item->qty, 2) }}
                                                </div>
                                                {{-- Sugerencia de ahorro (decorativa) --}}
                                                <!-- Indicador de ahorro -->
                                                <div class="mt-1 text-xs text-green-600">
                                                    <i class="mr-1 fas fa-piggy-bank"></i>
                                                    Ahorra más comprando {{ $item->qty + 1 }}+
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- 
                                ESTADO VACÍO DEL CARRITO
                                =======================
                                Pantalla que se muestra cuando no hay productos en el carrito
                            --}}
                            <!-- Estado vacío mejorado -->
                            <div class="py-16 text-center">
                                {{-- Icono decorativo del carrito vacío --}}
                                <div
                                    class="inline-flex items-center justify-center w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100">
                                    <i class="text-4xl text-indigo-500 fas fa-shopping-cart"></i>
                                </div>
                                {{-- Mensaje principal --}}
                                <h3 class="mb-4 text-2xl font-semibold text-gray-800">Tu carrito está vacío</h3>
                                {{-- Mensaje secundario con llamada a la acción --}}
                                <p class="max-w-md mx-auto mb-8 text-gray-600">No tienes productos en tu carrito de
                                    compras. ¡Explora nuestro catálogo y encuentra productos increíbles!</p>
                                {{-- Botón para regresar a comprar --}}
                                <button onclick="window.history.back()"
                                    class="inline-flex items-center px-8 py-3 font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl hover:shadow-xl hover:scale-105">
                                    <i class="mr-3 text-white fas fa-store"></i>
                                    <span class="text-white">Explorar Productos</span>
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 
                SECCIÓN DEL RESUMEN DEL CARRITO
                ==============================
                Panel lateral sticky que ocupa 1/3 del espacio en desktop
                y columna completa en móvil
            --}}
            <!-- Resumen del carrito -->
            <div class="lg:col-span-1">
                {{-- Contenedor principal sticky con glassmorphism --}}
                <div
                    class="sticky overflow-hidden border shadow-2xl backdrop-blur-sm bg-white/70 rounded-3xl border-white/20 top-8">
                    {{-- 
                        HEADER DEL RESUMEN
                        =================
                        Título y descripción del panel de resumen
                    --}}
                    <!-- Header del resumen -->
                    <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
                        <div class="flex items-center space-x-3">
                            {{-- Icono con efecto glassmorphism --}}
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <i class="text-lg text-white fas fa-calculator"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Resumen del Pedido</h2>
                                <p class="text-sm text-green-100">Total a pagar</p>
                            </div>
                        </div>
                    </div>
                    @if (Cart::count() > 0)
                        <!-- Detalles del resumen con animaciones premium -->
                        <div class="p-6 space-y-5">
                            <!-- Subtotal con efectos -->
                            <div
                                class="flex items-center justify-between px-4 py-3 transition-all duration-300 border bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-blue-200/50 hover:shadow-md">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                    <span class="font-medium text-gray-700">Subtotal ({{ Cart::count() }}
                                        {{ Cart::count() === 1 ? 'artículo' : 'artículos' }})</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">${{ Cart::subtotal() }}</span>
                            </div> <!-- Envío con badge especial -->
                            <div
                                class="flex items-center justify-between px-4 py-3 transition-all duration-300 border bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-green-200/50 hover:shadow-md">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Envío</span>
                                    <div
                                        class="inline-flex items-center px-2 py-1 text-xs font-bold text-white bg-green-500 rounded-full">
                                        <i class="mr-1 fas fa-gift"></i>
                                        GRATIS
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-2 text-sm font-bold text-green-600 line-through">$15.00</span>
                                    <span class="font-bold text-green-600">$0.00</span>
                                </div>
                            </div> <!-- Impuestos -->
                            <div
                                class="flex items-center justify-between px-4 py-3 transition-all duration-300 border bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border-amber-200/50 hover:shadow-md">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                    <span class="font-medium text-gray-700">Impuestos</span>
                                </div>
                                <span class="font-bold text-gray-900">${{ Cart::tax() }}</span>
                            </div>

                            <!-- Separador con gradiente -->
                            <div class="h-px my-4 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>

                            <!-- Total con efectos especiales -->
                            <div
                                class="relative p-6 overflow-hidden shadow-xl bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl">
                                <!-- Efecto de brillo -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 translate-x-[-100%] animate-pulse">
                                </div>

                                <div class="relative flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white/20">
                                            <i class="text-sm text-white fas fa-dollar-sign"></i>
                                        </div>
                                        <span class="text-xl font-bold text-white">Total Final</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-black text-white drop-shadow-lg">
                                            ${{ Cart::total() }}
                                        </div>
                                        <div class="text-sm text-green-100">IVA incluido</div>
                                    </div>
                                </div>
                            </div> <!-- Ofertas especiales (decorativo) -->
                            <div class="p-4 border glass-effect rounded-xl border-purple-200/50">
                                <div class="flex items-center mb-2 space-x-2">
                                    <i class="text-purple-500 fas fa-star float"></i>
                                    <span class="text-sm font-bold text-purple-700">¡Oferta Especial!</span>
                                </div>
                                <p class="text-xs text-purple-600">Compra $50 más y obtén 10% de descuento en tu
                                    próxima compra</p>
                                <div class="h-2 mt-2 overflow-hidden bg-purple-200 rounded-full">
                                    <div
                                        class="w-3/4 h-full rounded-full bg-gradient-to-r from-purple-500 to-pink-500">
                                    </div>
                                </div>
                            </div><!-- Botones de acción premium -->
                            <div class="pt-6 space-y-4"> <!-- Botón principal de pago (sin ruta específica) -->
                                <a href="{{ route('shipping.index') }}"
                                    class="relative inline-flex items-center justify-center w-full px-8 py-5 overflow-hidden font-bold text-white transition-all duration-500 transform shadow-2xl group bg-gradient-to-r from-green-600 via-emerald-600 to-green-700 hover:from-green-700 hover:via-emerald-700 hover:to-green-800 rounded-2xl hover:shadow-2xl hover:scale-105">
                                    <!-- Efecto de brillo dinámico -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent opacity-0 group-hover:opacity-100 transform -skew-x-12 translate-x-[-200%] group-hover:translate-x-[200%] transition-all duration-1000">
                                    </div>

                                    <!-- Iconos y texto -->
                                    <div class="relative flex items-center space-x-3">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm">
                                            <i class="text-white fas fa-credit-card"></i>
                                        </div>
                                        <span class="text-lg">Proceder al Pago Seguro</span>
                                        <div class="flex items-center justify-center w-6 h-6 rounded-full bg-white/20">
                                            <i class="text-sm text-white fas fa-shield-alt"></i>
                                        </div>
                                    </div>

                                    <!-- Indicador de seguridad -->
                                    <div
                                        class="absolute w-4 h-4 bg-green-400 border-2 border-white rounded-full -top-1 -right-1 animate-pulse">
                                    </div>
                                </a> <!-- Botón secundario mejorado -->
                                <button onclick="window.history.back()"
                                    class="inline-flex items-center justify-center w-full px-6 py-4 font-semibold text-gray-700 transition-all duration-300 border-2 border-gray-200 shadow-md group bg-gradient-to-r from-gray-100 via-white to-gray-100 hover:from-indigo-50 hover:via-blue-50 hover:to-indigo-50 hover:text-indigo-700 rounded-xl hover:border-indigo-300 hover:shadow-lg">
                                    <div class="flex items-center space-x-3">
                                        <i
                                            class="text-gray-500 transition-colors duration-300 fas fa-arrow-left group-hover:text-indigo-500"></i>
                                        <span>Seguir Comprando</span>
                                        <div
                                            class="w-2 h-2 transition-colors duration-300 bg-gray-400 rounded-full group-hover:bg-indigo-400">
                                        </div>
                                    </div>
                                </button> <!-- Garantías de seguridad -->
                                <div class="grid grid-cols-2 gap-3 pt-4">
                                    <div class="flex items-center space-x-2 text-xs text-gray-600">
                                        <i class="text-green-500 fas fa-lock"></i>
                                        <span>Pago Seguro</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-gray-600">
                                        <i class="text-blue-500 fas fa-truck"></i>
                                        <span>Envío Gratis</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-gray-600">
                                        <i class="text-purple-500 fas fa-undo"></i>
                                        <span>Devoluciones</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-gray-600">
                                        <i class="text-orange-500 fas fa-headset"></i>
                                        <span>Soporte 24/7</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Estado vacío del resumen -->
                        <div class="p-6 text-center">
                            <div class="mb-4 text-gray-500">
                                <i class="mb-3 text-4xl fas fa-receipt"></i>
                                <p>No hay productos para calcular</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
