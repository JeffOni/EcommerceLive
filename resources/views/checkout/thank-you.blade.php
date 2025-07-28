<x-app-layout>
    {{-- SOLUCIÓN: Script para actualizar contador del carrito después de compra --}}
    @if(session('cartCleared'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Actualizar el contador del carrito a 0
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = '0';
            }
            
            // También disparar el evento Livewire si existe
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('cartUpdated', {count: 0});
            }
        });
    </script>
    @endif

    <div class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen py-3 xs:py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-2 xs:px-4 sm:px-6 lg:px-8">
            <!-- Header de agradecimiento -->
            <div class="text-center mb-6 xs:mb-8 sm:mb-12">
                <div class="flex justify-center mb-3 xs:mb-4 sm:mb-6">
                    <div class="bg-green-100 rounded-full p-3 xs:p-4 sm:p-6">
                        <div class="bg-green-500 rounded-full p-2 xs:p-3 sm:p-4">
                            <svg class="w-6 h-6 xs:w-8 xs:h-8 sm:w-12 sm:h-12 text-white" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <h1
                    class="text-xl xs:text-2xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-2 xs:mb-3 sm:mb-4 px-2">
                    ¡Gracias por tu compra!
                </h1>

                <p class="text-sm xs:text-base sm:text-xl text-gray-600 max-w-2xl mx-auto px-2">
                    Hemos recibido tu comprobante de pago y lo estamos validando. Te notificaremos pronto sobre el
                    estado de tu pedido.
                </p>
            </div>

            <!-- Cards informativos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 xs:gap-6 sm:gap-8 mb-6 xs:mb-8 sm:mb-12">
                <!-- Qué sigue -->
                <div class="bg-white rounded-xl xs:rounded-2xl shadow-lg p-4 xs:p-6 sm:p-8">
                    <div class="flex items-center mb-4 xs:mb-6">
                        <div class="bg-blue-100 rounded-full p-2 xs:p-3 mr-3 xs:mr-4">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 text-blue-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-900">¿Qué sigue?</h2>
                    </div>

                    <div class="space-y-3 xs:space-y-4">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-5 h-5 xs:w-6 xs:h-6 bg-green-500 rounded-full flex items-center justify-center mr-2 xs:mr-3 mt-0.5 xs:mt-1">
                                <span class="text-white text-xs font-bold">1</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm xs:text-base">Validación del pago</h3>
                                <p class="text-gray-600 text-xs xs:text-sm">Verificaremos tu comprobante en las próximas
                                    24-48
                                    horas.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-5 h-5 xs:w-6 xs:h-6 bg-green-500 rounded-full flex items-center justify-center mr-2 xs:mr-3 mt-0.5 xs:mt-1">
                                <span class="text-white text-xs font-bold">2</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm xs:text-base">Confirmación</h3>
                                <p class="text-gray-600 text-xs xs:text-sm">Te contactaremos por teléfono o email para
                                    confirmar tu
                                    pedido.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-5 h-5 xs:w-6 xs:h-6 bg-green-500 rounded-full flex items-center justify-center mr-2 xs:mr-3 mt-0.5 xs:mt-1">
                                <span class="text-white text-xs font-bold">3</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm xs:text-base">Preparación y envío</h3>
                                <p class="text-gray-600 text-xs xs:text-sm">Una vez confirmado, prepararemos tu pedido
                                    para el
                                    envío.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="bg-white rounded-xl xs:rounded-2xl shadow-lg p-4 xs:p-6 sm:p-8">
                    <div class="flex items-center mb-4 xs:mb-6">
                        <div class="bg-purple-100 rounded-full p-2 xs:p-3 mr-3 xs:mr-4">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 text-purple-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-900">
                            <span class="hidden xs:inline">Mantente en contacto</span>
                            <span class="xs:hidden">Contacto</span>
                        </h2>
                    </div>

                    <div class="space-y-3 xs:space-y-4">
                        <div class="flex items-center p-3 xs:p-4 bg-gray-50 rounded-lg">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5 text-gray-600 mr-2 xs:mr-3 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-gray-700 text-xs xs:text-sm">
                                <span class="hidden xs:inline">Te enviaremos un email de confirmación</span>
                                <span class="xs:hidden">Email de confirmación</span>
                            </span>
                        </div>

                        <div class="flex items-center p-3 xs:p-4 bg-gray-50 rounded-lg">
                            <svg class="w-4 h-4 xs:w-5 xs:h-5 text-gray-600 mr-2 xs:mr-3 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <span class="text-gray-700 text-xs xs:text-sm">
                                <span class="hidden xs:inline">Nos comunicaremos contigo por teléfono</span>
                                <span class="xs:hidden">Llamada telefónica</span>
                            </span>
                        </div>

                        <div class="p-3 xs:p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-blue-800 text-xs xs:text-sm">
                                <span class="font-semibold">¿Tienes preguntas?</span><br>
                                <span class="hidden xs:inline">Puedes contactarnos en cualquier momento para consultar
                                    el estado de tu pedido.</span>
                                <span class="xs:hidden">Contáctanos para consultar tu pedido.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información importante -->
            <div class="bg-white rounded-xl xs:rounded-2xl shadow-lg p-4 xs:p-6 sm:p-8 mb-4 xs:mb-6 sm:mb-8">
                <h2 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-900 mb-4 xs:mb-6 text-center">
                    <span class="hidden xs:inline">Información importante</span>
                    <span class="xs:hidden">Info importante</span>
                </h2>

                <div class="grid grid-cols-1 xs:grid-cols-3 gap-4 xs:gap-6">
                    <div class="text-center">
                        <div
                            class="bg-yellow-100 rounded-full p-3 xs:p-4 w-12 h-12 xs:w-16 xs:h-16 mx-auto mb-2 xs:mb-4">
                            <svg class="w-6 h-6 xs:w-8 xs:h-8 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1 xs:mb-2 text-xs xs:text-sm sm:text-base">
                            <span class="hidden xs:inline">Tiempo de validación</span>
                            <span class="xs:hidden">Validación</span>
                        </h3>
                        <p class="text-gray-600 text-xs xs:text-sm">
                            <span class="hidden xs:inline">Los pagos se validan en 24-48 horas hábiles</span>
                            <span class="xs:hidden">24-48 horas</span>
                        </p>
                    </div>

                    <div class="text-center">
                        <div
                            class="bg-green-100 rounded-full p-3 xs:p-4 w-12 h-12 xs:w-16 xs:h-16 mx-auto mb-2 xs:mb-4">
                            <svg class="w-6 h-6 xs:w-8 xs:h-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1 xs:mb-2 text-xs xs:text-sm sm:text-base">
                            <span class="hidden xs:inline">Garantía de seguridad</span>
                            <span class="xs:hidden">Seguridad</span>
                        </h3>
                        <p class="text-gray-600 text-xs xs:text-sm">
                            <span class="hidden xs:inline">Tu información y pago están completamente seguros</span>
                            <span class="xs:hidden">Pago seguro</span>
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="bg-blue-100 rounded-full p-3 xs:p-4 w-12 h-12 xs:w-16 xs:h-16 mx-auto mb-2 xs:mb-4">
                            <svg class="w-6 h-6 xs:w-8 xs:h-8 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1 xs:mb-2 text-xs xs:text-sm sm:text-base">
                            <span class="hidden xs:inline">Soporte 24/7</span>
                            <span class="xs:hidden">Soporte</span>
                        </h3>
                        <p class="text-gray-600 text-xs xs:text-sm">
                            <span class="hidden xs:inline">Estamos aquí para ayudarte en todo momento</span>
                            <span class="xs:hidden">24/7 disponible</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="text-center space-y-3 xs:space-y-4 md:space-y-0 md:space-x-4 md:flex md:justify-center px-2">
                <a href="{{ route('welcome.index') }}"
                    class="inline-flex items-center justify-center w-full md:w-auto px-4 xs:px-6 sm:px-8 py-3 xs:py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg text-sm xs:text-base">
                    <svg class="w-4 h-4 xs:w-5 xs:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="hidden xs:inline">Volver al Inicio</span>
                    <span class="xs:hidden">Inicio</span>
                </a>

                <a href="{{ route('cart.index') }}"
                    class="inline-flex items-center justify-center w-full md:w-auto px-4 xs:px-6 sm:px-8 py-3 xs:py-4 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-lg hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg text-sm xs:text-base">
                    <svg class="w-4 h-4 xs:w-5 xs:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h9.5"></path>
                    </svg>
                    <span class="hidden xs:inline">Ver Carrito</span>
                    <span class="xs:hidden">Carrito</span>
                </a>
            </div>

            <!-- Footer con mensaje -->
            <div class="mt-6 xs:mt-8 sm:mt-12 text-center px-2">
                <p class="text-gray-500 text-xs xs:text-sm">
                    <span class="hidden xs:inline">Si necesitas ayuda, no dudes en contactarnos. ¡Gracias por confiar en
                        nosotros!</span>
                    <span class="xs:hidden">¡Gracias por tu confianza!</span>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>