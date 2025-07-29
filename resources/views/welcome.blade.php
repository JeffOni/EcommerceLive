<x-app-layout>
    @push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        body {
            overflow-x: hidden;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .badge-new {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
        }

        /* Modern Carousel Styles */
        .modern-carousel .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.5);
            border: 2px solid white;
            opacity: 1;
        }

        .modern-carousel .swiper-pagination-bullet-active {
            background: white;
            transform: scale(1.2);
        }

        .modern-carousel .swiper-button-prev,
        .modern-carousel .swiper-button-next {
            color: transparent;
        }

        .modern-carousel .swiper-button-prev::after,
        .modern-carousel .swiper-button-next::after {
            display: none;
        }

        /* Animaciones suaves */
        .feature-card {
            will-change: transform;
        }

        .product-card {
            will-change: transform;
        }

        /* Efectos de hover mejorados */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        /* Hero section responsive mejoras */
        @media (max-width: 768px) {
            .modern-carousel .swiper-slide h2 {
                font-size: 2.5rem;
            }

            .modern-carousel .swiper-slide p {
                font-size: 1.125rem;
            }
        }

        /* Mejoras específicas para dispositivos muy pequeños (344px) */
        @media (max-width: 380px) {

            body,
            html {
                overflow-x: hidden !important;
                max-width: 100vw;
            }

            * {
                max-width: 100%;
                box-sizing: border-box;
            }

            .modern-carousel .swiper-slide h2 {
                font-size: 1.875rem;
                /* text-3xl */
                line-height: 1.2;
            }

            .modern-carousel .swiper-slide p {
                font-size: 0.875rem;
                /* text-sm */
                line-height: 1.4;
            }

            .hero-gradient {
                min-height: 400px;
            }

            .feature-card {
                padding: 1.5rem;
            }

            .product-card {
                margin-bottom: 1rem;
            }

            .swiper-container {
                overflow: hidden !important;
            }
        }
    </style>
    @endpush

    {{-- Modern Covers Carousel Section --}}
    @if(isset($covers) && $covers->count() > 0)
    <div class="relative overflow-hidden">
        <div class="swiper modern-carousel overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach ($covers as $cover)
                <div class="swiper-slide">
                    <div class="relative h-[400px] sm:h-[500px] lg:h-[600px]">
                        <img src="{{ asset($cover->image) }}" alt="Banner" class="object-cover w-full h-full">
                        <div class="absolute inset-0 bg-black/20">
                            <div class="flex items-end justify-center h-full pb-12 sm:pb-16 lg:pb-20">
                                <div class="flex flex-col gap-3 sm:gap-4 sm:flex-row px-4">
                                    <a href="{{ route('products.index') }}"
                                        class="inline-flex items-center justify-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold text-white transition duration-300 transform shadow-xl bg-gradient-to-r from-primary-600 to-coral-500 backdrop-blur-sm rounded-xl hover:from-primary-700 hover:to-coral-600 hover:shadow-2xl hover:-translate-y-1">
                                        <i class="mr-2 text-sm fas fa-shopping-bag"></i>Explorar Productos
                                    </a>
                                    @guest
                                    <a href="{{ route('register') }}"
                                        class="inline-flex items-center justify-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold text-white transition duration-300 border-2 border-white/90 backdrop-blur-sm rounded-xl hover:bg-white hover:text-primary-800">
                                        <i class="mr-2 text-sm fas fa-user-plus"></i>Crear Cuenta
                                    </a>
                                    @else
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center justify-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold text-white transition duration-300 border-2 border-white/90 backdrop-blur-sm rounded-xl hover:bg-white hover:text-primary-800">
                                        <i class="mr-2 text-sm fas fa-user"></i>Mi Cuenta
                                    </a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Modern Navigation --}}
            <div class="swiper-pagination !bottom-4 sm:!bottom-8"></div>
            <div
                class="swiper-button-prev !left-2 sm:!left-4 !w-10 !h-10 sm:!w-12 sm:!h-12 !bg-white/20 !rounded-full backdrop-blur-sm hover:!bg-white/30 transition-all duration-300">
                <i class="fas fa-chevron-left !text-white !text-xs sm:!text-sm"></i>
            </div>
            <div
                class="swiper-button-next !right-2 sm:!right-4 !w-10 !h-10 sm:!w-12 sm:!h-12 !bg-white/20 !rounded-full backdrop-blur-sm hover:!bg-white/30 transition-all duration-300">
                <i class="fas fa-chevron-right !text-white !text-xs sm:!text-sm"></i>
            </div>
        </div>
    </div>
    @else
    {{-- Fallback Hero cuando no hay covers --}}
    <div
        class="relative h-[400px] sm:h-[500px] lg:h-[600px] bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative flex items-end justify-center h-full pb-12 sm:pb-16 lg:pb-20">
            <div class="flex flex-col gap-3 sm:gap-4 sm:flex-row px-4">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold text-purple-600 transition duration-300 bg-white shadow-xl rounded-xl hover:bg-gray-100">
                    <i class="mr-2 text-sm fas fa-shopping-bag"></i>Explorar Catálogo
                </a>
                @guest
                <a href="{{ route('register') }}"
                    class="inline-flex items-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold text-white transition duration-300 border-2 border-white rounded-xl hover:bg-white hover:text-purple-600">
                    <i class="mr-2 text-sm fas fa-user-plus"></i>Crear Cuenta
                </a>
                @endguest
            </div>
        </div>
    </div>
    @endif

    {{-- Features Section --}}
    <div class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <x-container>
            <div class="mb-12 sm:mb-16 text-center px-4">
                <h2 class="mb-4 sm:mb-6 text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800">¿Por qué elegirnos?
                </h2>
                <p class="max-w-3xl mx-auto text-base sm:text-lg lg:text-xl text-gray-600">Ofrecemos la mejor
                    experiencia de compra online con
                    servicios
                    de calidad que garantizan tu satisfacción</p>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-3 px-4">
                <div
                    class="p-6 sm:p-8 lg:p-10 text-center transition-all duration-300 bg-white border border-gray-100 shadow-lg feature-card group rounded-2xl hover:shadow-2xl">
                    <div
                        class="flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 transition-transform duration-300 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl group-hover:scale-110">
                        <i class="text-2xl sm:text-3xl text-white fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="mb-3 sm:mb-4 text-xl sm:text-2xl font-bold text-gray-800">Envío Rápido</h3>
                    <p class="text-sm sm:text-base leading-relaxed text-gray-600">Entrega express en 24-48 horas con
                        seguimiento en tiempo
                        real de tu pedido desde el momento de la compra.</p>
                </div>
                <div
                    class="p-6 sm:p-8 lg:p-10 text-center transition-all duration-300 bg-white border border-gray-100 shadow-lg feature-card group rounded-2xl hover:shadow-2xl">
                    <div
                        class="flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 transition-transform duration-300 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl group-hover:scale-110">
                        <i class="text-2xl sm:text-3xl text-white fas fa-shield-alt"></i>
                    </div>
                    <h3 class="mb-3 sm:mb-4 text-xl sm:text-2xl font-bold text-gray-800">Compra Segura</h3>
                    <p class="text-sm sm:text-base leading-relaxed text-gray-600">Transacciones 100% protegidas con
                        encriptación SSL y
                        múltiples métodos de pago disponibles.</p>
                </div>
                <div
                    class="p-6 sm:p-8 lg:p-10 text-center transition-all duration-300 bg-white border border-gray-100 shadow-lg feature-card group rounded-2xl hover:shadow-2xl">
                    <div
                        class="flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 transition-transform duration-300 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl group-hover:scale-110">
                        <i class="text-2xl sm:text-3xl text-white fas fa-headset"></i>
                    </div>
                    <h3 class="mb-3 sm:mb-4 text-xl sm:text-2xl font-bold text-gray-800">Soporte 24/7</h3>
                    <p class="text-sm sm:text-base leading-relaxed text-gray-600">Atención al cliente profesional
                        disponible las 24 horas
                        para resolver todas tus consultas.</p>
                </div>
            </div>
        </x-container>
    </div>

    {{-- Products Section --}}
    @if(isset($lastProducts) && $lastProducts->count() > 0)
    <div class="py-12 sm:py-16 lg:py-20 bg-white">
        <x-container>
            {{-- Título de sección actualizado --}}
            <div class="mb-12 sm:mb-16 text-center px-4">
                <h2 class="mb-4 sm:mb-6 text-2xl sm:text-3xl lg:text-4xl font-bold text-primary-800">Productos
                    Destacados</h2>
                <p class="max-w-3xl mx-auto text-base sm:text-lg lg:text-xl text-secondary-600">Descubre nuestra
                    selección exclusiva de
                    productos más
                    populares y recientes, cuidadosamente elegidos para ti</p>
                <div
                    class="w-16 sm:w-24 h-1 mx-auto mt-6 sm:mt-8 rounded-full bg-gradient-to-r from-coral-500 to-secondary-500">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 px-4">
                @foreach ($lastProducts as $product)
                <article
                    class="flex flex-col h-full overflow-hidden transition-all duration-300 bg-white/90 backdrop-blur-sm border border-secondary-200 shadow-lg product-card group rounded-2xl hover:shadow-2xl hover:border-coral-200">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                            class="object-cover w-full h-48 sm:h-56 lg:h-64 transition-transform duration-500 group-hover:scale-110">

                        {{-- Badge de ofertas o nuevo --}}
                        @if($product->is_on_valid_offer)
                        <div
                            class="absolute px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold text-white bg-coral-500 rounded-full top-3 sm:top-4 left-3 sm:left-4 animate-pulse shadow-lg">
                            <i class="mr-1 text-xs fas fa-fire"></i>{{ $product->discount_percentage }}% OFF
                        </div>
                        @elseif($loop->index < 3) <div
                            class="absolute px-2 sm:px-3 py-1 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-coral-500 to-coral-600 rounded-full shadow-lg top-3 sm:top-4 left-3 sm:left-4">
                            <i class="mr-1 text-xs fas fa-star"></i>Nuevo
                    </div>
                    @endif

                    <div
                        class="absolute transition-all duration-300 transform translate-y-2 opacity-0 top-3 sm:top-4 right-3 sm:right-4 group-hover:opacity-100 group-hover:translate-y-0">
                        <button
                            class="p-2 sm:p-3 transition-all duration-200 rounded-full shadow-lg bg-white/90 backdrop-blur-sm hover:bg-white hover:scale-110">
                            <i
                                class="text-secondary-600 transition-colors duration-200 text-sm fas fa-heart hover:text-coral-500"></i>
                        </button>
                    </div>

                    {{-- Quick view overlay --}}
                    <div
                        class="absolute inset-0 flex items-center justify-center transition-all duration-300 opacity-0 bg-primary-900/60 group-hover:opacity-100">
                        <a href="{{ route('products.show', $product) }}"
                            class="px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base font-semibold text-primary-800 transition-all duration-200 transform scale-90 bg-white rounded-full hover:bg-cream-50 group-hover:scale-100 shadow-xl">
                            <i class="mr-2 text-xs fas fa-eye"></i>Vista Rápida
                        </a>
                    </div>
            </div>

            <div class="flex flex-col flex-grow p-4 sm:p-6">
                <div class="mb-2">
                    <span
                        class="px-2 py-1 text-xs sm:text-sm font-medium text-secondary-700 rounded-full bg-secondary-100">
                        {{ $product->subcategory->category->family->name ?? 'Producto' }}
                    </span>
                </div>

                <h3
                    class="text-base sm:text-lg font-bold text-primary-800 mb-2 sm:mb-3 line-clamp-2 min-h-[48px] sm:min-h-[56px] group-hover:text-coral-600 transition-colors duration-200">
                    {{ $product->name }}
                </h3>

                @if($product->description)
                <p class="flex-grow mb-3 sm:mb-4 text-xs sm:text-sm leading-relaxed text-secondary-600 line-clamp-2">
                    {{ Str::limit($product->description, 60) }}
                </p>
                @endif

                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    {{-- Precios con ofertas --}}
                    @if($product->is_on_valid_offer)
                    <div class="flex flex-col">
                        <div class="text-lg sm:text-2xl font-bold text-coral-600">
                            ${{ number_format($product->current_price, 2) }}
                        </div>
                        <div class="flex items-center gap-1 sm:gap-2">
                            <span class="text-sm sm:text-lg text-secondary-500 line-through">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            <span
                                class="px-1 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold text-white bg-coral-500 rounded-full">
                                -${{ number_format($product->savings_amount, 2) }}
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="text-lg sm:text-2xl font-bold text-primary-600">
                        ${{ number_format($product->price, 2) }}
                    </div>
                    @endif

                    <div class="flex items-center text-yellow-400">
                        @for($i = 1; $i <= 5; $i++) <i class="text-xs sm:text-sm fas fa-star"></i>
                            @endfor
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm text-secondary-500">(4.8)</span>
                    </div>
                </div>

                {{-- Stock indicator --}}
                @if($product->hasAvailableStock())
                <div class="flex items-center mb-3 sm:mb-4 text-xs sm:text-sm text-secondary-600">
                    <i class="mr-1 sm:mr-2 text-xs fas fa-check-circle text-secondary-500"></i>
                    <span>{{ $product->getAvailableStock() }} disponibles</span>
                </div>
                @else
                <div class="flex items-center mb-3 sm:mb-4 text-xs sm:text-sm text-coral-600">
                    <i class="mr-1 sm:mr-2 text-xs fas fa-times-circle text-coral-500"></i>
                    <span>Agotado</span>
                </div>
                @endif

                <!-- Botones fijos en la parte inferior -->
                <div class="flex gap-2 sm:gap-3 mt-auto">
                    <a href="{{ route('products.show', $product) }}"
                        class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center py-2 sm:py-3 px-3 sm:px-4 rounded-xl text-xs sm:text-sm font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="mr-1 sm:mr-2 text-xs fas fa-eye"></i>Ver Detalles
                    </a>
                    <livewire:quick-add-to-cart :product="$product" :key="'welcome-cart-'.$product->id" />
                </div>
            </div>
            </article>
            @endforeach
    </div>

    <div class="mt-12 sm:mt-16 text-center px-4">
        <a href="{{ route('products.index') }}"
            class="inline-flex items-center px-6 sm:px-8 lg:px-10 py-3 sm:py-4 text-base sm:text-lg font-bold text-white transition-all duration-300 transform shadow-xl bg-gradient-to-r from-primary-600 to-coral-500 rounded-xl hover:from-primary-700 hover:to-coral-600 hover:shadow-2xl hover:-translate-y-1">
            <i class="mr-2 sm:mr-3 text-sm fas fa-th-large"></i>Ver Todos los Productos
            <i class="ml-2 sm:ml-3 text-sm fas fa-arrow-right"></i>
        </a>
    </div>
    </x-container>
    </div>
    @endif

    {{-- Contact Form Section --}}
    <div
        class="relative py-12 sm:py-16 lg:py-20 overflow-hidden text-white bg-gradient-to-br from-primary-900 via-secondary-800 to-primary-800">
        {{-- Background decoration --}}
        <div class="absolute inset-0 opacity-10 overflow-hidden">
            <div
                class="absolute bg-coral-300 rounded-full top-5 sm:top-10 left-5 sm:left-10 w-36 h-36 sm:w-72 sm:h-72 mix-blend-overlay filter blur-xl animate-pulse">
            </div>
            <div class="absolute bg-secondary-300 rounded-full bottom-5 sm:bottom-10 right-5 sm:right-10 w-36 h-36 sm:w-72 sm:h-72 mix-blend-overlay filter blur-xl animate-pulse"
                style="animation-delay: 2s;"></div>
        </div>

        <x-container class="relative z-10 overflow-x-hidden">
            <div class="max-w-4xl mx-auto text-center px-4">
                <div class="mb-8 sm:mb-12">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 mb-4 sm:mb-6 bg-coral-500/20 backdrop-blur-sm rounded-2xl border border-coral-300/30">
                        <i class="text-2xl sm:text-3xl text-coral-200 fas fa-envelope"></i>
                    </div>
                    <h2 class="mb-4 sm:mb-6 text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold">¡Contáctanos!</h2>
                    <p
                        class="max-w-2xl mx-auto mb-6 sm:mb-8 text-base sm:text-lg lg:text-xl leading-relaxed text-cream-200">
                        ¿Tienes alguna pregunta o necesitas ayuda? Nuestro equipo de atención al cliente está aquí para
                        ayudarte.
                    </p>
                </div>

                <form id="contactForm" action="{{ route('contact.send') }}" method="POST"
                    class="max-w-2xl mx-auto space-y-4 sm:space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">
                        <div>
                            <input type="text" name="name" placeholder="Tu nombre completo" required
                                class="w-full px-4 sm:px-6 py-3 sm:py-4 text-base sm:text-lg text-primary-800 placeholder-secondary-500 transition-all duration-300 rounded-xl bg-white/90 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:bg-white">
                        </div>
                        <div>
                            <input type="email" name="email" placeholder="tu@correo.com" required
                                class="w-full px-4 sm:px-6 py-3 sm:py-4 text-base sm:text-lg text-primary-800 placeholder-secondary-500 transition-all duration-300 rounded-xl bg-white/90 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:bg-white">
                        </div>
                    </div>

                    <div>
                        <input type="text" name="subject" placeholder="Asunto del mensaje" required
                            class="w-full px-4 sm:px-6 py-3 sm:py-4 text-base sm:text-lg text-primary-800 placeholder-secondary-500 transition-all duration-300 rounded-xl bg-white/90 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:bg-white">
                    </div>

                    <div>
                        <textarea name="message" rows="5" placeholder="Escribe tu mensaje aquí..." required
                            class="w-full px-4 sm:px-6 py-3 sm:py-4 text-base sm:text-lg text-primary-800 placeholder-secondary-500 transition-all duration-300 resize-none rounded-xl bg-white/90 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:bg-white"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="px-6 sm:px-8 lg:px-10 py-3 sm:py-4 text-base sm:text-lg font-bold transition-all duration-300 transform shadow-xl bg-gradient-to-r from-coral-500 to-coral-600 rounded-xl hover:from-coral-600 hover:to-coral-700 hover:shadow-2xl hover:-translate-y-1">
                            <i class="mr-2 text-sm fas fa-paper-plane"></i>Enviar Mensaje
                        </button>
                    </div>
                </form>

                <p class="flex items-center justify-center gap-2 mt-4 sm:mt-6 text-xs sm:text-sm text-cream-300">
                    <i class="text-xs fas fa-shield-alt text-secondary-300"></i>
                    Tus datos están seguros. Responderemos en menos de 24 horas.
                </p>

                {{-- Contact Methods --}}
                <div class="grid grid-cols-1 gap-6 sm:gap-8 mt-12 sm:mt-16 md:grid-cols-3">
                    <div class="text-center">
                        <div
                            class="flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-full bg-white/10">
                            <i class="text-lg sm:text-2xl text-green-300 fas fa-phone"></i>
                        </div>
                        <h3 class="mb-2 text-base sm:text-lg font-semibold">Llámanos</h3>
                        <p class="text-xs sm:text-sm text-gray-300">Atención personalizada por teléfono</p>
                    </div>
                    <div class="text-center">
                        <div
                            class="flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-full bg-white/10">
                            <i class="text-lg sm:text-2xl text-blue-300 fas fa-envelope"></i>
                        </div>
                        <h3 class="mb-2 text-base sm:text-lg font-semibold">Escríbenos</h3>
                        <p class="text-xs sm:text-sm text-gray-300">Respuesta garantizada en 24h</p>
                    </div>
                    <div class="text-center">
                        <div
                            class="flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-full bg-white/10">
                            <i class="text-lg sm:text-2xl text-purple-300 fas fa-comments"></i>
                        </div>
                        <h3 class="mb-2 text-base sm:text-lg font-semibold">Chat Online</h3>
                        <p class="text-xs sm:text-sm text-gray-300">Soporte en tiempo real</p>
                    </div>
                </div>
            </div>
        </x-container>
    </div>

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modern Carousel Swiper
            const modernCarousel = new Swiper('.modern-carousel', {
                loop: true,
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                speed: 1000,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + '"></span>';
                    },
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                keyboard: {
                    enabled: true,
                },
                mousewheel: false,
                on: {
                    init: function () {
                        // Animación de entrada para el primer slide
                        const firstSlide = this.slides[this.activeIndex];
                        const content = firstSlide.querySelector('.max-w-xl');
                        if (content) {
                            content.style.opacity = '0';
                            content.style.transform = 'translateY(30px)';
                            setTimeout(() => {
                                content.style.transition = 'all 0.8s ease-out';
                                content.style.opacity = '1';
                                content.style.transform = 'translateY(0)';
                            }, 200);
                        }
                    },
                    slideChange: function () {
                        // Animación para cada cambio de slide
                        const activeSlide = this.slides[this.activeIndex];
                        const content = activeSlide.querySelector('.max-w-xl');
                        if (content) {
                            content.style.opacity = '0';
                            content.style.transform = 'translateY(30px)';
                            setTimeout(() => {
                                content.style.transition = 'all 0.6s ease-out';
                                content.style.opacity = '1';
                                content.style.transform = 'translateY(0)';
                            }, 100);
                        }
                    }
                }
            });

            // Contact Form functionality
            const contactForm = document.getElementById('contactForm');
            
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(contactForm);
                    const name = formData.get('name').trim();
                    const email = formData.get('email').trim();
                    const subject = formData.get('subject').trim();
                    const message = formData.get('message').trim();
                    
                    // Validación básica
                    if (!name || !email || !subject || !message) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Campos incompletos',
                                text: 'Por favor, completa todos los campos.',
                                confirmButtonColor: '#7c3aed'
                            });
                        } else {
                            alert('Por favor, completa todos los campos.');
                        }
                        return;
                    }
                    
                    if (!email.includes('@')) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Email inválido',
                                text: 'Por favor, ingresa un email válido.',
                                confirmButtonColor: '#7c3aed'
                            });
                        } else {
                            alert('Por favor, ingresa un email válido.');
                        }
                        return;
                    }
                    
                    // Envío real del formulario
                    const submitButton = contactForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>Enviando...';
                    submitButton.disabled = true;
                    
                    // Realizar la petición AJAX
                    fetch('{{ route("contact.send") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Mensaje enviado!',
                                    text: data.message,
                                    confirmButtonColor: '#7c3aed'
                                });
                            } else {
                                alert(data.message);
                            }
                            contactForm.reset();
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'Error al enviar el mensaje. Por favor, inténtalo de nuevo.',
                                confirmButtonColor: '#7c3aed'
                            });
                        } else {
                            alert('Error al enviar el mensaje. Por favor, inténtalo de nuevo.');
                        }
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                    });
                });
            }

            // Add to favorites functionality con mejor UX
            document.querySelectorAll('.fa-heart').forEach(heart => {
                heart.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    this.classList.toggle('text-red-500');
                    this.classList.toggle('text-gray-600');
                    
                    // Animación de pulso
                    this.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                    
                    // Feedback visual
                    if (this.classList.contains('text-red-500')) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Agregado a favoritos',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
            });

            // Lazy loading para imágenes mejorado
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.style.opacity = '0';
                            img.style.transition = 'opacity 0.6s ease-in-out';
                            img.onload = () => {
                                img.style.opacity = '1';
                            };
                            observer.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }

            // Mejora de accesibilidad para teclado
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Cerrar cualquier modal o carousel focus
                    document.activeElement.blur();
                }
            });
        });
    </script>
    @endpush


</x-app-layout>