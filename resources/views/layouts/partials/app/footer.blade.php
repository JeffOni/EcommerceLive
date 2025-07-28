<footer class="relative py-8 sm:py-12 lg:py-16 overflow-hidden bg-primary-900 text-cream-100">
    {{-- Elementos decorativos de fondo --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute w-32 h-32 sm:w-64 sm:h-64 rounded-full -top-10 sm:-top-20 -right-10 sm:-right-20 bg-coral-400/10 blur-3xl">
        </div>
        <div
            class="absolute w-32 h-32 sm:w-64 sm:h-64 rounded-full -bottom-10 sm:-bottom-20 -left-10 sm:-left-20 bg-secondary-400/10 blur-3xl">
        </div>
    </div>

    <x-container class="relative z-10 overflow-x-hidden">
        <div class="grid grid-cols-1 gap-6 sm:gap-8 mb-8 sm:mb-12 md:grid-cols-4 px-4">
            {{-- Company Info --}}
            <div class="md:col-span-2">
                <h3
                    class="mb-3 sm:mb-4 text-xl sm:text-2xl font-bold text-transparent bg-gradient-to-r from-cream-100 to-coral-200 bg-clip-text">
                    LagoFish</h3>
                <p class="mb-4 sm:mb-6 text-sm sm:text-base leading-relaxed text-cream-200">
                    Tu pescadería online de confianza. Ofrecemos pescados y mariscos frescos de la más alta calidad con
                    envíos rápidos y seguros a todo el país.
                </p>
                <div class="flex space-x-3 sm:space-x-4">
                    <a href="https://www.facebook.com/lago.fish.1" target="_blank"
                        class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 transition-all duration-300 transform rounded-full shadow-lg bg-secondary-600 hover:bg-secondary-700 hover:shadow-xl hover:scale-110">
                        <i class="text-lg sm:text-xl fab fa-facebook-f text-cream-100"></i>
                    </a>
                    <a href="https://www.instagram.com/fishlago/?hl=es-la" target="_blank"
                        class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 transition-all duration-300 transform rounded-full shadow-lg bg-gradient-to-r from-coral-500 to-coral-600 hover:from-coral-600 hover:to-coral-700 hover:shadow-xl hover:scale-110">
                        <i class="text-lg sm:text-xl fab fa-instagram text-cream-100"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="mb-3 sm:mb-4 text-base sm:text-lg font-semibold text-coral-300">Enlaces Rápidos</h4>
                <ul class="space-y-2 sm:space-y-3">
                    <li><a href="{{ route('products.index') }}"
                            class="flex items-center text-sm sm:text-base transition-colors duration-300 text-cream-200 hover:text-coral-200">
                            <i class="mr-2 text-xs sm:text-sm fas fa-fish text-coral-400"></i>Productos</a></li>
                    <li><a href="#"
                            class="flex items-center text-sm sm:text-base transition-colors duration-300 text-cream-200 hover:text-coral-200">
                            <i class="mr-2 text-xs sm:text-sm fas fa-list text-coral-400"></i>Categorías</a></li>
                    <li><a href="#"
                            class="flex items-center text-sm sm:text-base transition-colors duration-300 text-cream-200 hover:text-coral-200">
                            <i class="mr-2 text-xs sm:text-sm fas fa-tags text-coral-400"></i>Ofertas</a></li>
                    <li><a href="#"
                            class="flex items-center text-sm sm:text-base transition-colors duration-300 text-cream-200 hover:text-coral-200">
                            <i class="mr-2 text-xs sm:text-sm fas fa-star text-coral-400"></i>Nuevo</a></li>
                </ul>
            </div>

            {{-- Customer Service --}}
            <div>
                <h4 class="mb-3 sm:mb-4 text-base sm:text-lg font-semibold text-secondary-300">Atención al Cliente</h4>
                <ul class="space-y-2 sm:space-y-3">
                    <li><a href="#"
                            class="flex items-center text-sm sm:text-base transition-colors duration-300 text-cream-200 hover:text-secondary-200">
                            <i class="mr-2 text-xs sm:text-sm fas fa-envelope text-secondary-400"></i>Contáctanos</a>
                    </li>
                    <li><a href="#"
                            class="flex items-center text-sm sm:text-base transition-colors duration-300 text-cream-200 hover:text-secondary-200">
                            <i class="mr-2 text-xs sm:text-sm fas fa-truck text-secondary-400"></i>Envíos</a></li>
                    <li><a href="#"
                            class="flex items-center text-sm sm:text-base transition-colors duration-300 text-cream-200 hover:text-secondary-200">
                            <i class="mr-2 text-xs sm:text-sm fas fa-headset text-secondary-400"></i>Soporte</a></li>
                    <li><a href="#"
                            class="flex items-center text-sm sm:text-base transition-colors duration-300 text-cream-200 hover:text-secondary-200">
                            <i class="mr-2 text-xs sm:text-sm fas fa-shield-alt text-secondary-400"></i>Seguridad</a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom Footer --}}
        <div
            class="flex flex-col items-center justify-between pt-6 sm:pt-8 border-t border-secondary-700/50 md:flex-row px-4">
            <p class="text-xs sm:text-sm text-cream-300 text-center md:text-left">
                © {{ date('Y') }} LagoFish. Todos los derechos reservados.
            </p>
            <div class="flex flex-col sm:flex-row mt-3 sm:mt-4 md:mt-0 space-y-2 sm:space-y-0 sm:space-x-6 text-center">
                <a href="#"
                    class="text-xs sm:text-sm transition-colors duration-300 text-cream-300 hover:text-coral-200 whitespace-nowrap">Términos
                    de Servicio</a>
                <a href="#"
                    class="text-xs sm:text-sm transition-colors duration-300 text-cream-300 hover:text-coral-200 whitespace-nowrap">Política
                    de Privacidad</a>
            </div>
        </div>
    </x-container>
</footer>