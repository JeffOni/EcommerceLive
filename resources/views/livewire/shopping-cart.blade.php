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
<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-cream-50 via-secondary-50 to-primary-50">
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
            class="absolute rounded-full -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-brand-coral/20 to-coral-400/10 blur-3xl animate-pulse">
        </div>
        {{-- Círculo decorativo inferior izquierdo --}}
        <div
            class="absolute rounded-full -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-primary-400/20 to-secondary-500/10 blur-3xl animate-pulse">
        </div>
        {{-- Elementos decorativos medianos para dar profundidad --}}
        <div
            class="absolute w-32 h-32 rounded-full top-1/3 right-1/4 bg-gradient-to-r from-secondary-300/30 to-primary-400/20 blur-2xl">
        </div>
        <div
            class="absolute w-24 h-24 rounded-full bottom-1/3 left-1/4 bg-gradient-to-r from-coral-300/30 to-brand-coral/20 blur-xl">
        </div>
    </div>

    <div class="relative px-2 xs:px-4 py-6 xs:py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{--
        HEADER PRINCIPAL DEL CARRITO - OPTIMIZADO PARA 344px
        ====================================================
        Sección centrada que incluye:
        - Icono principal del carrito con gradiente responsive
        - Título principal con texto responsive
        - Contador de productos con estilo glassmorphism adaptativo
        --}}
        <!-- Header mejorado del carrito responsive -->
        <div class="mb-8 xs:mb-10 sm:mb-12 text-center">
            {{-- Icono principal con gradiente y sombra responsive --}}
            <div
                class="inline-flex items-center justify-center w-12 h-12 xs:w-14 xs:h-14 sm:w-16 sm:h-16 mb-3 xs:mb-4 shadow-lg bg-gradient-to-r from-primary-900 to-secondary-500 rounded-xl xs:rounded-2xl">
                <i class="text-lg xs:text-xl sm:text-2xl text-white fas fa-shopping-cart"></i>
            </div>
            {{-- Título principal con efecto degradado responsive --}}
            <h1
                class="mb-2 xs:mb-3 text-2xl xs:text-3xl sm:text-4xl lg:text-5xl font-bold text-transparent bg-gradient-to-r from-primary-600 via-brand-coral to-coral-600 bg-clip-text leading-tight">
                Carrito de Compras
            </h1>
            {{-- Badge informativo con contador de productos responsive --}}
            <div
                class="inline-flex items-center px-3 xs:px-4 py-2 border rounded-full shadow-md glass-effect border-white/40 text-sm xs:text-base">
                <i class="mr-1 xs:mr-2 text-indigo-500 fas fa-shopping-bag text-sm xs:text-base"></i>
                <span class="font-medium text-gray-700">
                    {{ Cart::count() }} {{ Cart::count() === 1 ? 'artículo' : 'artículos' }} en tu carrito
                </span>
            </div>
        </div>

        {{--
        LAYOUT PRINCIPAL - GRID RESPONSIVE OPTIMIZADO PARA 344px
        ========================================================
        Distribución en grid que se adapta a diferentes tamaños de pantalla:
        - 1 columna en móviles (344px-767px)
        - 3 columnas en desktop (2 para productos + 1 para resumen)
        - Orden optimizado: productos primero en móvil, resumen después
        --}}
        <div class="grid grid-cols-1 gap-4 xs:gap-6 sm:gap-8 lg:grid-cols-3">
            {{--
            SECCIÓN DE PRODUCTOS DEL CARRITO - RESPONSIVE 344px
            ===================================================
            Ocupa 2/3 del espacio en desktop y columna completa en móvil
            Optimizado para pantallas desde 344px
            --}}
            <!-- Lista de productos del carrito responsive -->
            <div class="lg:col-span-2 order-1 lg:order-1">
                {{-- Contenedor principal con efecto glassmorphism responsive --}}
                <!-- Contenedor principal con backdrop blur optimizado -->
                <div
                    class="overflow-hidden border shadow-lg xs:shadow-xl sm:shadow-2xl glass-effect rounded-2xl xs:rounded-3xl">
                    {{--
                    HEADER DE LA SECCIÓN DE PRODUCTOS - RESPONSIVE
                    =============================================
                    Incluye título, descripción y botón para limpiar carrito
                    --}}
                    <!-- Header de la sección responsive -->
                    <div class="px-3 xs:px-4 sm:px-6 py-3 xs:py-4 bg-gradient-to-r from-primary-900 to-secondary-500">
                        <div class="flex items-center justify-between flex-wrap gap-2 xs:gap-3">
                            <div class="flex items-center space-x-2 xs:space-x-3 min-w-0 flex-1">
                                {{-- Icono con efecto glassmorphism responsive --}}
                                <div class="p-1.5 xs:p-2 glass-effect rounded-lg xs:rounded-xl flex-shrink-0">
                                    <i class="text-sm xs:text-base sm:text-lg text-white fas fa-list"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-base xs:text-lg sm:text-xl font-bold text-white truncate">Productos
                                        Seleccionados</h2>
                                    <p class="text-xs xs:text-sm text-indigo-100 truncate">Revisa y modifica tu
                                        selección</p>
                                </div>
                            </div>
                            {{-- Botón para limpiar carrito responsive --}}
                            @if (Cart::count() > 0)
                            <button wire:click="clearCart"
                                class="inline-flex items-center px-2 xs:px-3 sm:px-4 py-1.5 xs:py-2 text-xs xs:text-sm font-medium text-white transition-all duration-200 border rounded-lg glass-effect border-red-300/30 flex-shrink-0">
                                <i class="mr-1 xs:mr-2 fas fa-trash-alt text-xs xs:text-sm"></i>
                                <span class="hidden xs:inline">Limpiar</span>
                                <span class="xs:hidden">×</span>
                            </button>
                            @endif
                        </div>
                    </div>

                    {{--
                    CONTENIDO DE PRODUCTOS - RESPONSIVE 344px
                    ==========================================
                    Lista de productos con @forelse para manejar estado vacío
                    Optimizado para pantallas ultra-compactas
                    --}}
                    <!-- Contenido de productos responsive -->
                    <div class="p-3 xs:p-4 sm:p-6">
                        @forelse (Cart::content() as $item)
                        {{--
                        PRODUCTO INDIVIDUAL - ULTRA-RESPONSIVE
                        ====================================
                        Cada producto incluye:
                        - Layout adaptativo para 344px
                        - Imagen optimizada
                        - información compacta
                        - Controles de cantidad touch-friendly
                        - Botón de eliminación accesible
                        --}}
                        <!-- Producto individual ultra-responsive -->
                        <div
                            class="relative p-3 xs:p-4 sm:p-6 mb-3 xs:mb-4 sm:mb-6 overflow-hidden border shadow-md xs:shadow-lg sm:shadow-lg group bg-gradient-to-br from-white via-blue-50/30 to-indigo-50/50 rounded-2xl xs:rounded-3xl border-white/60 last:mb-0 glass-effect">

                            {{-- Efecto de brillo responsive --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 group-hover:opacity-100 transform -skew-x-12 translate-x-[-100%] group-hover:translate-x-[200%] transition-all duration-1000">
                            </div>

                            {{-- Indicadores de estado compactos --}}
                            <div
                                class="absolute flex items-center space-x-1 xs:space-x-2 top-2 xs:top-3 sm:top-4 right-2 xs:right-3 sm:right-4">
                                <div
                                    class="w-2 h-2 xs:w-3 xs:h-3 rounded-full shadow-lg bg-gradient-to-r from-green-400 to-emerald-500">
                                </div>
                                <div
                                    class="w-1.5 h-1.5 xs:w-2 xs:h-2 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 opacity-60">
                                </div>
                            </div>

                            {{--
                            LAYOUT PRINCIPAL DEL PRODUCTO - STACK EN MÓVIL
                            ==============================================
                            Layout completamente adaptativo que cambia estructura según pantalla
                            --}}
                            <div
                                class="flex flex-col space-y-3 xs:space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-6 lg:space-x-8">

                                {{-- IMAGEN DEL PRODUCTO - RESPONSIVE --}}
                                <div class="relative flex-shrink-0 self-center sm:self-start">
                                    <div
                                        class="overflow-hidden transition-all duration-300 shadow-lg w-20 h-20 xs:w-24 xs:h-24 sm:w-28 sm:h-28 lg:w-36 lg:h-36 rounded-xl xs:rounded-2xl">
                                        @if ($item->options->image)
                                        <img class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                                            src="{{ $item->options->image }}" alt="{{ $item->name }}">
                                        @else
                                        <div
                                            class="flex items-center justify-center w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100">
                                            <i
                                                class="text-lg xs:text-xl sm:text-2xl lg:text-3xl text-indigo-400 fas fa-image"></i>
                                        </div>
                                        @endif
                                    </div>
                                    {{-- Badge de verificación responsive --}}
                                    <div
                                        class="absolute flex items-center justify-center w-6 h-6 xs:w-7 xs:h-7 sm:w-8 sm:h-8 rounded-full shadow-lg -top-1 xs:-top-2 -left-1 xs:-left-2 bg-gradient-to-r from-emerald-500 to-green-500">
                                        <i class="text-xs sm:text-sm text-white fas fa-check"></i>
                                    </div>
                                </div>

                                {{-- INFORMACIÓN DEL PRODUCTO - RESPONSIVE --}}
                                <div class="flex-1 min-w-0 space-y-2 xs:space-y-3">
                                    {{-- Nombre del producto responsive --}}
                                    <h3
                                        class="text-sm xs:text-base sm:text-lg lg:text-xl font-bold text-gray-900 leading-tight">
                                        <a href="{{ route('products.show', $item->id) }}"
                                            class="underline transition-colors duration-200 hover:text-indigo-600 line-clamp-2">
                                            {{ $item->name }}
                                        </a>
                                    </h3>

                                    {{-- Precio por unidad responsive --}}
                                    <div
                                        class="flex flex-col xs:flex-row xs:items-center space-y-1 xs:space-y-0 xs:space-x-3">
                                        @if(isset($item->options['is_on_offer']) && $item->options['is_on_offer'])
                                        <div class="flex flex-col space-y-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-base xs:text-lg sm:text-xl font-bold text-red-600">
                                                    ${{ number_format($item->price, 2) }}
                                                </span>
                                                <span
                                                    class="px-1.5 xs:px-2 py-0.5 xs:py-1 text-xs text-white bg-red-500 rounded-full">
                                                    {{ $item->options['discount_percentage'] }}% OFF
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs xs:text-sm text-gray-500 line-through">
                                                    ${{ number_format($item->options['original_price'], 2) }}
                                                </span>
                                                <span class="text-xs text-gray-500">precio original</span>
                                            </div>
                                            @if($item->options['offer_name'])
                                            <span class="text-xs font-medium text-green-600 truncate">
                                                🎯 {{ $item->options['offer_name'] }}
                                            </span>
                                            @endif
                                        </div>
                                        @else
                                        <span class="text-base xs:text-lg sm:text-xl font-bold text-green-600">
                                            ${{ number_format($item->price, 2) }}
                                        </span>
                                        @endif
                                        <span class="text-xs xs:text-sm text-gray-500">por unidad</span>
                                    </div>

                                    {{-- Botón eliminar responsive --}}
                                    <button wire:click="removeItem('{{ $item->rowId }}')"
                                        class="inline-flex items-center text-xs xs:text-sm text-red-500 transition-colors duration-200 hover:text-red-700">
                                        <i class="mr-1 xs:mr-2 fas fa-trash text-xs"></i>
                                        <span class="hidden xs:inline">Quitar del carrito</span>
                                        <span class="xs:hidden">Quitar</span>
                                    </button>
                                </div>

                                {{-- CONTROLES DE CANTIDAD - ULTRA-RESPONSIVE --}}
                                <div class="flex-shrink-0 self-center">
                                    <div class="space-y-2 xs:space-y-3">
                                        {{-- Etiqueta responsive --}}
                                        <label class="block text-xs xs:text-sm font-bold text-center text-gray-700">
                                            Cantidad
                                        </label>

                                        {{-- Controles de cantidad compactos --}}
                                        <div class="flex items-center justify-center">
                                            <div
                                                class="flex items-center bg-white border border-gray-300 rounded-lg xs:rounded-xl">
                                                {{-- Botón decrementar compacto --}}
                                                <button x-on:click="$wire.decreaseQuantity('{{ $item->rowId }}')"
                                                    :disabled="{{ $item->qty }} <= 1"
                                                    class="flex items-center justify-center w-8 h-8 xs:w-10 xs:h-10 sm:w-12 sm:h-12 text-gray-600 transition-colors duration-200 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed rounded-l-lg xs:rounded-l-xl hover:bg-gray-50">
                                                    <svg class="w-3 h-3 xs:w-4 xs:h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>

                                                {{-- Display de cantidad compacto --}}
                                                <div
                                                    class="flex items-center justify-center w-10 h-8 xs:w-12 xs:h-10 sm:w-16 sm:h-12 text-sm xs:text-base sm:text-lg font-semibold text-gray-900 bg-white border-gray-300 border-x">
                                                    <span>{{ $item->qty }}</span>
                                                </div>

                                                {{-- Botón incrementar compacto --}}
                                                <button x-on:click="$wire.increaseQuantity('{{ $item->rowId }}')"
                                                    :disabled="{{ $item->qty }} >= {{ $item->options->stock }}"
                                                    class="flex items-center justify-center w-8 h-8 xs:w-10 xs:h-10 sm:w-12 sm:h-12 text-gray-600 transition-colors duration-200 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed rounded-r-lg xs:rounded-r-xl hover:bg-gray-50">
                                                    <svg class="w-3 h-3 xs:w-4 xs:h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Subtotal compacto --}}
                                        <div
                                            class="p-2 xs:p-3 text-center border bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg xs:rounded-xl border-green-200/50">
                                            <div class="mb-1 text-xs font-medium text-gray-500">Subtotal</div>
                                            <div
                                                class="text-sm xs:text-base sm:text-lg font-bold text-transparent bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text">
                                                ${{ number_format($item->price * $item->qty, 2) }}
                                            </div>
                                            <div class="mt-1 text-xs text-green-600">
                                                <i class="mr-1 fas fa-piggy-bank"></i>
                                                <span class="hidden xs:inline">El precio más bajo del mercado</span>
                                                <span class="xs:hidden">Mejor precio</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        {{--
                        ESTADO VACÍO DEL CARRITO - RESPONSIVE 344px
                        ===========================================
                        Pantalla que se muestra cuando no hay productos en el carrito
                        Optimizada para dispositivos ultra-compactos
                        --}}
                        <!-- Estado vacío ultra-responsive -->
                        <div class="py-8 xs:py-12 sm:py-16 text-center">
                            {{-- Icono decorativo responsive --}}
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 xs:w-20 xs:h-20 sm:w-24 sm:h-24 mb-4 xs:mb-6 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100">
                                <i class="text-2xl xs:text-3xl sm:text-4xl text-indigo-500 fas fa-shopping-cart"></i>
                            </div>
                            {{-- Mensaje principal responsive --}}
                            <h3 class="mb-3 xs:mb-4 text-lg xs:text-xl sm:text-2xl font-semibold text-gray-800">Tu
                                carrito está vacío</h3>
                            {{-- Mensaje secundario responsive --}}
                            <p
                                class="max-w-xs xs:max-w-sm sm:max-w-md mx-auto mb-6 xs:mb-8 text-sm xs:text-base text-gray-600 px-2">
                                No tienes productos en tu carrito de compras. ¡Explora nuestro catálogo y encuentra
                                productos increíbles!
                            </p>
                            {{-- Botón responsive --}}
                            <button onclick="window.history.back()"
                                class="inline-flex items-center px-4 xs:px-6 sm:px-8 py-2 xs:py-2.5 sm:py-3 text-sm xs:text-base font-semibold text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg xs:rounded-xl hover:shadow-xl hover:scale-105">
                                <i class="mr-2 xs:mr-3 text-white fas fa-store text-sm xs:text-base"></i>
                                <span class="text-white">
                                    <span class="hidden xs:inline">Explorar Productos</span>
                                    <span class="xs:hidden">Explorar</span>
                                </span>
                            </button>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{--
            SECCIÓN DEL RESUMEN DEL CARRITO - RESPONSIVE 344px
            ==================================================
            Panel lateral sticky que ocupa 1/3 del espacio en desktop
            y columna completa en móvil. Optimizado para 344px
            --}}
            <!-- Resumen del carrito responsive -->
            <div class="lg:col-span-1 order-2 lg:order-2">
                {{-- Contenedor principal sticky responsive --}}
                <div
                    class="sticky overflow-hidden border shadow-lg xs:shadow-xl sm:shadow-2xl glass-effect rounded-2xl xs:rounded-3xl top-4 xs:top-6 lg:top-8">
                    {{--
                    HEADER DEL RESUMEN - RESPONSIVE
                    ==============================
                    Título y descripción del panel de resumen optimizado
                    --}}
                    <!-- Header del resumen responsive -->
                    <div class="px-3 xs:px-4 sm:px-6 py-3 xs:py-4 bg-gradient-to-r from-primary-900 to-secondary-500">
                        <div class="flex items-center space-x-2 xs:space-x-3">
                            {{-- Icono responsive --}}
                            <div class="p-1.5 xs:p-2 glass-effect rounded-lg xs:rounded-xl flex-shrink-0">
                                <i class="text-sm xs:text-base sm:text-lg text-white fas fa-calculator"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-base xs:text-lg sm:text-xl font-bold text-white truncate">Resumen del
                                    Pedido</h2>
                                <p class="text-xs xs:text-sm text-green-100 truncate">Total a pagar</p>
                            </div>
                        </div>
                    </div>
                    @if (Cart::count() > 0)
                    <!-- Detalles del resumen ultra-responsive -->
                    <div class="p-3 xs:p-4 sm:p-6 space-y-3 xs:space-y-4 sm:space-y-5">
                        <!-- Subtotal responsive -->
                        <div
                            class="flex items-center justify-between px-2 xs:px-3 sm:px-4 py-2 xs:py-3 transition-all duration-300 border bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg xs:rounded-xl border-blue-200/50 hover:shadow-md">
                            <div class="flex items-center space-x-1 xs:space-x-2 min-w-0 flex-1">
                                <div
                                    class="w-1.5 h-1.5 xs:w-2 xs:h-2 bg-blue-400 rounded-full animate-pulse flex-shrink-0">
                                </div>
                                <span class="font-medium text-gray-700 text-xs xs:text-sm truncate">
                                    Subtotal ({{ Cart::count() }}
                                    <span class="hidden xs:inline">{{ Cart::count() === 1 ? 'artículo' : 'artículos'
                                        }})</span>
                                    <span class="xs:hidden">{{ Cart::count() === 1 ? 'art.' : 'arts.' }})</span>
                                </span>
                            </div>
                            <span class="text-sm xs:text-base sm:text-lg font-bold text-gray-900 flex-shrink-0">${{
                                Cart::subtotal() }}</span>
                        </div> <!-- Envío con badge especial -->
                        {{-- <div
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
                        </div> --}}

                        <!-- Impuestos responsive -->
                        <div
                            class="flex items-center justify-between px-2 xs:px-3 sm:px-4 py-2 xs:py-3 transition-all duration-300 border bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg xs:rounded-xl border-amber-200/50 hover:shadow-md">
                            <div class="flex items-center space-x-1 xs:space-x-2 min-w-0 flex-1">
                                <div class="w-1.5 h-1.5 xs:w-2 xs:h-2 rounded-full bg-amber-400 flex-shrink-0"></div>
                                <span class="font-medium text-gray-700 text-xs xs:text-sm truncate">Impuestos</span>
                            </div>
                            <span class="font-bold text-gray-900 text-sm xs:text-base sm:text-lg flex-shrink-0">${{
                                Cart::tax() }}</span>
                        </div>

                        <!-- Separador responsive -->
                        <div class="h-px my-3 xs:my-4 bg-gradient-to-r from-transparent via-gray-300 to-transparent">
                        </div>

                        <!-- Total final ultra-responsive -->
                        <div
                            class="relative p-3 xs:p-4 sm:p-6 overflow-hidden shadow-lg xs:shadow-xl bg-gradient-to-r from-primary-900 to-secondary-500 rounded-xl xs:rounded-2xl">
                            <!-- Efecto de brillo responsive -->
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 translate-x-[-100%] animate-pulse">
                            </div>

                            <div class="relative flex items-center justify-between">
                                <div class="flex items-center space-x-2 xs:space-x-3 min-w-0 flex-1">
                                    <div
                                        class="flex items-center justify-center w-6 h-6 xs:w-7 xs:h-7 sm:w-8 sm:h-8 rounded-full bg-white/20 flex-shrink-0">
                                        <i class="text-xs xs:text-sm text-white fas fa-dollar-sign"></i>
                                    </div>
                                    <span class="text-sm xs:text-lg sm:text-xl font-bold text-white truncate">
                                        <span class="hidden xs:inline">Total Final</span>
                                        <span class="xs:hidden">Total</span>
                                    </span>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-lg xs:text-2xl sm:text-3xl font-black text-white drop-shadow-lg">
                                        ${{ Cart::total() }}
                                    </div>
                                    <div class="text-xs xs:text-sm text-green-100">IVA incluido</div>
                                </div>
                            </div>
                        </div>
                        <!-- Ofertas especiales (decorativo) -->
                        {{-- <div class="p-4 border glass-effect rounded-xl border-purple-200/50">
                            <div class="flex items-center mb-2 space-x-2">
                                <i class="text-purple-500 fas fa-star float"></i>
                                <span class="text-sm font-bold text-purple-700">¡Oferta Especial!</span>
                            </div>
                            <p class="text-xs text-purple-600">Compra $50 más y obtén 10% de descuento en tu
                                próxima compra</p>
                            <div class="h-2 mt-2 overflow-hidden bg-purple-200 rounded-full">
                                <div class="w-3/4 h-full rounded-full bg-gradient-to-r from-purple-500 to-pink-500">
                                </div>
                            </div>
                        </div> --}}
                        <!-- Botones de acción ultra-responsive -->
                        <div class="pt-4 xs:pt-6 space-y-3 xs:space-y-4">
                            <!-- Botón principal de pago responsive -->
                            <a href="{{ route('shipping.index') }}"
                                class="relative inline-flex items-center justify-center w-full px-4 xs:px-6 sm:px-8 py-3 xs:py-4 sm:py-5 overflow-hidden text-sm xs:text-base sm:text-lg font-bold text-white transition-all duration-500 transform shadow-lg xs:shadow-xl sm:shadow-2xl group bg-gradient-to-r from-green-600 via-emerald-600 to-green-700 hover:from-green-700 hover:via-emerald-700 hover:to-green-800 rounded-xl xs:rounded-2xl hover:shadow-2xl hover:scale-105">
                                <!-- Efecto de brillo responsive -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent opacity-0 group-hover:opacity-100 transform -skew-x-12 translate-x-[-200%] group-hover:translate-x-[200%] transition-all duration-1000">
                                </div>

                                <!-- Iconos y texto responsive -->
                                <div class="relative flex items-center space-x-2 xs:space-x-3">
                                    <div
                                        class="flex items-center justify-center w-6 h-6 xs:w-7 xs:h-7 sm:w-8 sm:h-8 rounded-full glass-effect flex-shrink-0">
                                        <i class="text-white fas fa-credit-card text-xs xs:text-sm"></i>
                                    </div>
                                    <span class="truncate">
                                        <span class="hidden sm:inline">Proceder al Pago Seguro</span>
                                        <span class="sm:hidden xs:inline">Pago Seguro</span>
                                        <span class="xs:hidden">Pagar</span>
                                    </span>
                                    <div
                                        class="flex items-center justify-center w-5 h-5 xs:w-6 xs:h-6 rounded-full glass-effect flex-shrink-0">
                                        <i class="text-xs text-white fas fa-shield-alt"></i>
                                    </div>
                                </div>

                                <!-- Indicador de seguridad responsive -->
                                <div
                                    class="absolute w-3 h-3 xs:w-4 xs:h-4 bg-green-400 border-2 border-white rounded-full -top-1 -right-1 animate-pulse">
                                </div>
                            </a>

                            <!-- Botón secundario responsive -->
                            <button onclick="window.history.back()"
                                class="inline-flex items-center justify-center w-full px-4 xs:px-6 py-3 xs:py-4 text-sm xs:text-base font-semibold text-gray-700 transition-all duration-300 border-2 border-gray-200 shadow-md group bg-gradient-to-r from-gray-100 via-white to-gray-100 hover:from-indigo-50 hover:via-blue-50 hover:to-indigo-50 hover:text-indigo-700 rounded-lg xs:rounded-xl hover:border-indigo-300 hover:shadow-lg">
                                <div class="flex items-center space-x-2 xs:space-x-3">
                                    <i
                                        class="text-gray-500 transition-colors duration-300 fas fa-arrow-left group-hover:text-indigo-500 text-xs xs:text-sm"></i>
                                    <span>
                                        <span class="hidden xs:inline">Seguir Comprando</span>
                                        <span class="xs:hidden">Seguir</span>
                                    </span>
                                    <div
                                        class="w-1.5 h-1.5 xs:w-2 xs:h-2 transition-colors duration-300 bg-gray-400 rounded-full group-hover:bg-indigo-400">
                                    </div>
                                </div>
                            </button>
                        </div>
                        </button> <!-- Garantías de seguridad -->
                        <!-- Garantías de seguridad responsive -->
                        <div class="grid grid-cols-2 gap-2 xs:gap-3 pt-3 xs:pt-4">
                            <div class="flex items-center space-x-1 xs:space-x-2 text-xs text-gray-600">
                                <i class="text-green-500 fas fa-lock text-xs"></i>
                                <span class="truncate">
                                    <span class="hidden xs:inline">Pago Seguro</span>
                                    <span class="xs:hidden">Seguro</span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-1 xs:space-x-2 text-xs text-gray-600">
                                <i class="text-orange-500 fas fa-headset text-xs"></i>
                                <span class="truncate">
                                    <span class="hidden xs:inline">Soporte 24/7</span>
                                    <span class="xs:hidden">Soporte</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Estado vacío del resumen responsive -->
                <div class="p-3 xs:p-4 sm:p-6 text-center">
                    <div class="mb-3 xs:mb-4 text-gray-500">
                        <i class="mb-2 xs:mb-3 text-2xl xs:text-3xl sm:text-4xl fas fa-receipt"></i>
                        <p class="text-xs xs:text-sm">No hay productos para calcular</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>