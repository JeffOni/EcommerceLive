<x-app-layout>
    @push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
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
    </style>
    @endpush

    {{-- Modern Covers Carousel Section --}}
    @if(isset($covers) && $covers->count() > 0)
    <div class="relative overflow-hidden">
        <div class="swiper modern-carousel">
            <div class="swiper-wrapper">
                @foreach ($covers as $cover)
                <div class="swiper-slide">
                    <div class="relative h-[500px] lg:h-[600px]">
                        <img src="{{ asset($cover->image) }}" alt="Banner" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/20">
                            <div class="flex items-end justify-center h-full pb-20">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="{{ route('products.index') }}"
                                        class="inline-flex items-center justify-center bg-purple-600/90 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold hover:bg-purple-700 transition duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                        <i class="fas fa-shopping-bag mr-2"></i>Explorar Productos
                                    </a>
                                    @guest
                                    <a href="{{ route('register') }}"
                                        class="inline-flex items-center justify-center border-2 border-white/90 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-gray-800 transition duration-300">
                                        <i class="fas fa-user-plus mr-2"></i>Crear Cuenta
                                    </a>
                                    @else
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center justify-center border-2 border-white/90 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-gray-800 transition duration-300">
                                        <i class="fas fa-user mr-2"></i>Mi Cuenta
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
            <div class="swiper-pagination !bottom-8"></div>
            <div
                class="swiper-button-prev !left-4 !w-12 !h-12 !bg-white/20 !rounded-full backdrop-blur-sm hover:!bg-white/30 transition-all duration-300">
                <i class="fas fa-chevron-left !text-white !text-sm"></i>
            </div>
            <div
                class="swiper-button-next !right-4 !w-12 !h-12 !bg-white/20 !rounded-full backdrop-blur-sm hover:!bg-white/30 transition-all duration-300">
                <i class="fas fa-chevron-right !text-white !text-sm"></i>
            </div>
        </div>
    </div>
    @else
    {{-- Fallback Hero cuando no hay covers --}}
    <div class="relative h-[500px] lg:h-[600px] bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative flex items-end justify-center h-full pb-20">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center bg-white text-purple-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition duration-300 shadow-xl">
                    <i class="fas fa-shopping-bag mr-2"></i>Explorar Catálogo
                </a>
                @guest
                <a href="{{ route('register') }}"
                    class="inline-flex items-center border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-purple-600 transition duration-300">
                    <i class="fas fa-user-plus mr-2"></i>Crear Cuenta
                </a>
                @endguest
            </div>
        </div>
    </div>
    @endif

    {{-- Features Section --}}
    <div class="py-20 bg-gray-50">
        <x-container>
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">¿Por qué elegirnos?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Ofrecemos la mejor experiencia de compra online con
                    servicios
                    de calidad que garantizan tu satisfacción</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="feature-card group bg-white p-10 rounded-2xl shadow-lg text-center transition-all duration-300 hover:shadow-2xl border border-gray-100">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shipping-fast text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Envío Rápido</h3>
                    <p class="text-gray-600 leading-relaxed">Entrega express en 24-48 horas con seguimiento en tiempo
                        real de tu pedido desde el momento de la compra.</p>
                </div>
                <div
                    class="feature-card group bg-white p-10 rounded-2xl shadow-lg text-center transition-all duration-300 hover:shadow-2xl border border-gray-100">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Compra Segura</h3>
                    <p class="text-gray-600 leading-relaxed">Transacciones 100% protegidas con encriptación SSL y
                        múltiples métodos de pago disponibles.</p>
                </div>
                <div
                    class="feature-card group bg-white p-10 rounded-2xl shadow-lg text-center transition-all duration-300 hover:shadow-2xl border border-gray-100">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-headset text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Soporte 24/7</h3>
                    <p class="text-gray-600 leading-relaxed">Atención al cliente profesional disponible las 24 horas
                        para resolver todas tus consultas.</p>
                </div>
            </div>
        </x-container>
    </div>

    {{-- Products Section --}}
    @if(isset($lastProducts) && $lastProducts->count() > 0)
    <div class="py-20 bg-white">
        <x-container>
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">Productos Destacados</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Descubre nuestra selección exclusiva de productos más
                    populares y recientes, cuidadosamente elegidos para ti</p>
                <div class="w-24 h-1 bg-gradient-to-r from-purple-500 to-blue-500 mx-auto mt-8 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($lastProducts as $product)
                <article
                    class="product-card group bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl flex flex-col h-full border border-gray-100">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                            class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">

                        {{-- Badge de ofertas o nuevo --}}
                        @if($product->is_on_valid_offer)
                        <div
                            class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold animate-pulse">
                            <i class="fas fa-fire mr-1"></i>{{ $product->discount_percentage }}% OFF
                        </div>
                        @elseif($loop->index < 3) <div
                            class="badge-new absolute top-4 left-4 px-3 py-1 text-white text-sm font-bold rounded-full shadow-lg">
                            <i class="fas fa-star mr-1"></i>Nuevo
                    </div>
                    @endif

                    <div
                        class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                        <button
                            class="bg-white/90 backdrop-blur-sm p-3 rounded-full shadow-lg hover:bg-white transition-all duration-200 hover:scale-110">
                            <i class="fas fa-heart text-gray-600 hover:text-red-500 transition-colors duration-200"></i>
                        </button>
                    </div>

                    {{-- Quick view overlay --}}
                    <div
                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                        <a href="{{ route('products.show', $product) }}"
                            class="bg-white text-gray-800 px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition-all duration-200 transform scale-90 group-hover:scale-100">
                            <i class="fas fa-eye mr-2"></i>Vista Rápida
                        </a>
                    </div>
            </div>

            <div class="p-6 flex flex-col flex-grow">
                <div class="mb-2">
                    <span class="text-sm text-purple-600 font-medium bg-purple-50 px-2 py-1 rounded-full">
                        {{ $product->subcategory->category->family->name ?? 'Producto' }}
                    </span>
                </div>

                <h3
                    class="text-lg font-bold text-gray-800 mb-3 line-clamp-2 min-h-[56px] group-hover:text-purple-600 transition-colors duration-200">
                    {{ $product->name }}
                </h3>

                @if($product->description)
                <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow leading-relaxed">
                    {{ Str::limit($product->description, 80) }}
                </p>
                @endif

                <div class="flex items-center justify-between mb-6">
                    {{-- Precios con ofertas --}}
                    @if($product->is_on_valid_offer)
                    <div class="flex flex-col">
                        <div class="text-2xl font-bold text-red-600">
                            ${{ number_format($product->current_price, 2) }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg text-gray-500 line-through">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                -${{ number_format($product->savings_amount, 2) }}
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="text-2xl font-bold text-gray-800">
                        <span class="text-lg text-gray-500">$</span>{{ number_format($product->price, 2) }}
                    </div>
                    @endif

                    <div class="flex items-center text-yellow-400">
                        @for($i = 1; $i <= 5; $i++) <i class="fas fa-star text-sm"></i>
                            @endfor
                            <span class="text-gray-500 text-sm ml-2">(4.8)</span>
                    </div>
                </div>

                {{-- Stock indicator --}}
                @if($product->stock > 0)
                <div class="flex items-center text-green-600 text-sm mb-4">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ $product->stock }} disponibles</span>
                </div>
                @else
                <div class="flex items-center text-red-600 text-sm mb-4">
                    <i class="fas fa-times-circle mr-2"></i>
                    <span>Agotado</span>
                </div>
                @endif

                <!-- Botones fijos en la parte inferior -->
                <div class="flex gap-3 mt-auto">
                    <a href="{{ route('products.show', $product) }}"
                        class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-center py-3 px-4 rounded-xl font-semibold hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-eye mr-2"></i>Ver Detalles
                    </a>
                    <livewire:quick-add-to-cart :product="$product" :key="'welcome-cart-'.$product->id" />
                </div>
            </div>
            </article>
            @endforeach
    </div>

    <div class="text-center mt-16">
        <a href="{{ route('products.index') }}"
            class="inline-flex items-center bg-gradient-to-r from-purple-600 to-blue-600 text-white px-10 py-4 rounded-xl font-bold text-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
            <i class="fas fa-th-large mr-3"></i>Ver Todos los Productos
            <i class="fas fa-arrow-right ml-3"></i>
        </a>
    </div>
    </x-container>
    </div>
    @endif

    {{-- Contact Form Section --}}
    <div class="relative bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 text-white py-20 overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-xl animate-pulse">
            </div>
            <div class="absolute bottom-10 right-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-overlay filter blur-xl animate-pulse"
                style="animation-delay: 2s;"></div>
        </div>

        <x-container class="relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-12">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
                        <i class="fas fa-envelope text-3xl text-white"></i>
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold mb-6">¡Contáctanos!</h2>
                    <p class="text-xl text-gray-200 mb-8 leading-relaxed max-w-2xl mx-auto">
                        ¿Tienes alguna pregunta o necesitas ayuda? Nuestro equipo de atención al cliente está aquí para
                        ayudarte.
                    </p>
                </div>

                <form id="contactForm" class="max-w-2xl mx-auto space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="name" placeholder="Tu nombre completo" required
                                class="w-full px-6 py-4 rounded-xl bg-white/90 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:bg-white transition-all duration-300 text-lg">
                        </div>
                        <div>
                            <input type="email" name="email" placeholder="tu@correo.com" required
                                class="w-full px-6 py-4 rounded-xl bg-white/90 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:bg-white transition-all duration-300 text-lg">
                        </div>
                    </div>

                    <div>
                        <input type="text" name="subject" placeholder="Asunto del mensaje" required
                            class="w-full px-6 py-4 rounded-xl bg-white/90 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:bg-white transition-all duration-300 text-lg">
                    </div>

                    <div>
                        <textarea name="message" rows="6" placeholder="Escribe tu mensaje aquí..." required
                            class="w-full px-6 py-4 rounded-xl bg-white/90 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:bg-white transition-all duration-300 text-lg resize-none"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="bg-gradient-to-r from-purple-600 to-blue-600 px-10 py-4 rounded-xl font-bold text-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-paper-plane mr-2"></i>Enviar Mensaje
                        </button>
                    </div>
                </form>

                <p class="text-sm text-gray-300 mt-6 flex items-center justify-center gap-2">
                    <i class="fas fa-shield-alt"></i>
                    Tus datos están seguros. Responderemos en menos de 24 horas.
                </p>

                {{-- Contact Methods --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-phone text-2xl text-green-300"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Llámanos</h3>
                        <p class="text-gray-300 text-sm">Atención personalizada por teléfono</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-envelope text-2xl text-blue-300"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Escríbenos</h3>
                        <p class="text-gray-300 text-sm">Respuesta garantizada en 24h</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-comments text-2xl text-purple-300"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Chat Online</h3>
                        <p class="text-gray-300 text-sm">Soporte en tiempo real</p>
                    </div>
                </div>
            </div>
        </x-container>
    </div>

    {{-- Footer Section --}}
    <footer class="bg-gray-900 text-white py-16">
        <x-container>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                {{-- Company Info --}}
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4 text-purple-400">EcommerceLive</h3>
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        Tu tienda online de confianza. Ofrecemos productos de la más alta calidad con envíos rápidos y
                        seguros a todo el país.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/lago.fish.1" target="_blank"
                            class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors duration-300">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="https://www.instagram.com/fishlago/?hl=es-la" target="_blank"
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center hover:from-purple-600 hover:to-pink-600 transition-all duration-300">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-purple-400">Enlaces Rápidos</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('products.index') }}"
                                class="text-gray-300 hover:text-white transition-colors duration-300">Productos</a></li>
                        <li><a href="#"
                                class="text-gray-300 hover:text-white transition-colors duration-300">Categorías</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-300 hover:text-white transition-colors duration-300">Ofertas</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Nuevo</a>
                        </li>
                    </ul>
                </div>

                {{-- Customer Service --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-purple-400">Atención al Cliente</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-gray-300 hover:text-white transition-colors duration-300">Contáctanos</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Envíos</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-300 hover:text-white transition-colors duration-300">Devoluciones</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">FAQ</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Bottom Footer --}}
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} EcommerceLive. Todos los derechos reservados.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-300">Términos
                        de Servicio</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-300">Política
                        de Privacidad</a>
                </div>
            </div>
        </x-container>
    </footer>


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
                    
                    // Simulación de envío (aquí puedes agregar la lógica real de envío)
                    const submitButton = contactForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
                    submitButton.disabled = true;
                    
                    // Simular envío del formulario
                    setTimeout(() => {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Mensaje enviado!',
                                text: 'Hemos recibido tu mensaje. Te responderemos pronto.',
                                confirmButtonColor: '#7c3aed'
                            });
                        } else {
                            alert('¡Mensaje enviado! Te responderemos pronto.');
                        }
                        
                        // Limpiar formulario
                        contactForm.reset();
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                    }, 2000);
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