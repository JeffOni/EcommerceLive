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
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Categorías</a>
                    </li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Ofertas</a>
                    </li>
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