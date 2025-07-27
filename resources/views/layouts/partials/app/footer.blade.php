<footer class="bg-primary-900 text-cream-100 py-16 relative overflow-hidden">
    {{-- Elementos decorativos de fondo --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute rounded-full -top-20 -right-20 w-64 h-64 bg-coral-400/10 blur-3xl"></div>
        <div class="absolute rounded-full -bottom-20 -left-20 w-64 h-64 bg-secondary-400/10 blur-3xl"></div>
    </div>

    <x-container class="relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            {{-- Company Info --}}
            <div class="md:col-span-2">
                <h3
                    class="text-2xl font-bold mb-4 bg-gradient-to-r from-cream-100 to-coral-200 bg-clip-text text-transparent">
                    LagoFish</h3>
                <p class="text-cream-200 mb-6 leading-relaxed">
                    Tu pescadería online de confianza. Ofrecemos pescados y mariscos frescos de la más alta calidad con
                    envíos rápidos y seguros a todo el país.
                </p>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/lago.fish.1" target="_blank"
                        class="w-12 h-12 bg-secondary-600 rounded-full flex items-center justify-center hover:bg-secondary-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110">
                        <i class="fab fa-facebook-f text-xl text-cream-100"></i>
                    </a>
                    <a href="https://www.instagram.com/fishlago/?hl=es-la" target="_blank"
                        class="w-12 h-12 bg-gradient-to-r from-coral-500 to-coral-600 rounded-full flex items-center justify-center hover:from-coral-600 hover:to-coral-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110">
                        <i class="fab fa-instagram text-xl text-cream-100"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-lg font-semibold mb-4 text-coral-300">Enlaces Rápidos</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('products.index') }}"
                            class="text-cream-200 hover:text-coral-200 transition-colors duration-300 flex items-center">
                            <i class="fas fa-fish mr-2 text-coral-400"></i>Productos</a></li>
                    <li><a href="#"
                            class="text-cream-200 hover:text-coral-200 transition-colors duration-300 flex items-center">
                            <i class="fas fa-list mr-2 text-coral-400"></i>Categorías</a></li>
                    <li><a href="#"
                            class="text-cream-200 hover:text-coral-200 transition-colors duration-300 flex items-center">
                            <i class="fas fa-tags mr-2 text-coral-400"></i>Ofertas</a></li>
                    <li><a href="#"
                            class="text-cream-200 hover:text-coral-200 transition-colors duration-300 flex items-center">
                            <i class="fas fa-star mr-2 text-coral-400"></i>Nuevo</a></li>
                </ul>
            </div>

            {{-- Customer Service --}}
            <div>
                <h4 class="text-lg font-semibold mb-4 text-secondary-300">Atención al Cliente</h4>
                <ul class="space-y-3">
                    <li><a href="#"
                            class="text-cream-200 hover:text-secondary-200 transition-colors duration-300 flex items-center">
                            <i class="fas fa-envelope mr-2 text-secondary-400"></i>Contáctanos</a></li>
                    <li><a href="#"
                            class="text-cream-200 hover:text-secondary-200 transition-colors duration-300 flex items-center">
                            <i class="fas fa-truck mr-2 text-secondary-400"></i>Envíos</a></li>
                    <li><a href="#"
                            class="text-cream-200 hover:text-secondary-200 transition-colors duration-300 flex items-center">
                            <i class="fas fa-undo mr-2 text-secondary-400"></i>Devoluciones</a></li>
                    <li><a href="#"
                            class="text-cream-200 hover:text-secondary-200 transition-colors duration-300 flex items-center">
                            <i class="fas fa-question-circle mr-2 text-secondary-400"></i>FAQ</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Footer --}}
        <div class="border-t border-secondary-700/50 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-cream-300 text-sm">
                © {{ date('Y') }} LagoFish. Todos los derechos reservados.
            </p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="text-cream-300 hover:text-coral-200 text-sm transition-colors duration-300">Términos
                    de Servicio</a>
                <a href="#" class="text-cream-300 hover:text-coral-200 text-sm transition-colors duration-300">Política
                    de Privacidad</a>
            </div>
        </div>
    </x-container>
</footer>