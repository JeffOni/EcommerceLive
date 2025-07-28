<x-app-layout>
    <!-- Contenedor principal optimizado para 344px -->
    <x-container class="mt-4 xs:mt-6 sm:mt-8">
        <div class="grid grid-cols-1 gap-4 xs:gap-6 lg:grid-cols-3">
            <!-- Sección principal del formulario de envío - responsive -->
            <div class="order-2 lg:order-1 lg:col-span-2">
                @livewire('shipping-addresses')
            </div>

            <!-- Resumen del pedido - responsive -->
            <div class="order-1 lg:order-2 lg:col-span-1">
                <div
                    class="sticky overflow-hidden bg-white border border-gray-200 rounded-lg xs:rounded-xl shadow-lg xs:shadow-xl top-4 xs:top-6 lg:top-8">
                    <!-- Header del resumen responsive -->
                    <div
                        class="px-3 xs:px-4 sm:px-6 py-3 xs:py-4 text-white bg-gradient-to-r from-green-600 to-emerald-600">
                        <div class="flex items-center space-x-2 xs:space-x-3">
                            <div class="p-1.5 xs:p-2 rounded-lg bg-white/20 flex-shrink-0">
                                <i class="text-sm xs:text-base sm:text-lg text-white fas fa-receipt"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-base xs:text-lg sm:text-xl font-bold text-white truncate">Resumen del
                                    Pedido</h2>
                                <p class="text-xs xs:text-sm text-green-100 truncate">
                                    {{ Cart::instance('shopping')->count() }}
                                    <span class="hidden xs:inline">{{ Cart::instance('shopping')->count() === 1 ?
                                        'artículo' : 'artículos' }}</span>
                                    <span class="xs:hidden">{{ Cart::instance('shopping')->count() === 1 ? 'art.' :
                                        'arts.' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de productos ultra-responsive -->
                    <div class="p-3 xs:p-4 sm:p-6 space-y-3 xs:space-y-4">
                        @foreach (Cart::content() as $item)
                        <div
                            class="flex items-center p-2 xs:p-3 sm:p-4 space-x-2 xs:space-x-3 sm:space-x-4 transition-all duration-300 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md hover:border-gray-200">
                            <!-- Imagen del producto responsive -->
                            <div class="relative flex-shrink-0">
                                <div
                                    class="w-12 h-12 xs:w-14 xs:h-14 sm:w-16 sm:h-16 overflow-hidden bg-gray-100 rounded-lg shadow-md">
                                    @if ($item->options->image)
                                    <img class="object-cover w-full h-full" src="{{ $item->options->image }}"
                                        alt="{{ $item->name }}">
                                    @else
                                    <div
                                        class="flex items-center justify-center w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100">
                                        <i class="text-base xs:text-lg sm:text-2xl text-indigo-400 fas fa-image"></i>
                                    </div>
                                    @endif
                                </div>
                                <!-- Badge de cantidad responsive -->
                                <div
                                    class="absolute flex items-center justify-center w-5 h-5 xs:w-6 xs:h-6 text-xs font-bold text-white bg-green-500 rounded-full shadow-lg -top-1 xs:-top-2 -right-1 xs:-right-2">
                                    {{ $item->qty }}
                                </div>
                            </div>

                            <!-- Información del producto responsive -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 line-clamp-1 text-xs xs:text-sm sm:text-base">{{
                                    $item->name }}</h4>
                                <div
                                    class="flex flex-col xs:flex-row xs:items-center xs:justify-between mt-1 space-y-1 xs:space-y-0">
                                    @if(isset($item->options['is_on_offer']) && $item->options['is_on_offer'])
                                    <div class="flex flex-col">
                                        <div class="flex items-center space-x-1 xs:space-x-2">
                                            <span class="text-xs xs:text-sm text-red-600 font-medium">${{
                                                number_format($item->price, 2) }}</span>
                                            <span class="text-xs bg-red-500 text-white px-1 py-0.5 rounded">
                                                {{ $item->options['discount_percentage'] }}% OFF
                                            </span>
                                        </div>
                                        <span class="text-xs text-gray-500 line-through">
                                            ${{ number_format($item->options['original_price'], 2) }}
                                            <span class="hidden xs:inline">c/u</span>
                                        </span>
                                    </div>
                                    @else
                                    <span class="text-xs xs:text-sm text-gray-600">
                                        ${{ number_format($item->price, 2) }}
                                        <span class="hidden xs:inline">c/u</span>
                                    </span>
                                    @endif
                                    <span class="text-sm xs:text-base font-bold text-green-600">${{
                                        number_format($item->price * $item->qty, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Separador responsive -->
                        <div class="h-px my-4 xs:my-6 bg-gradient-to-r from-transparent via-gray-300 to-transparent">
                        </div>

                        <!-- Total final responsive -->
                        <div
                            class="relative p-3 xs:p-4 sm:p-6 overflow-hidden text-white rounded-lg shadow-lg xs:shadow-xl bg-gradient-to-r from-green-600 to-emerald-600">
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
                                    <div class="text-lg xs:text-2xl sm:text-3xl font-black text-white">
                                        ${{ Cart::instance('shopping')->total() }}
                                    </div>
                                    <div class="text-xs xs:text-sm text-green-100">IVA incluido</div>
                                </div>
                            </div>
                        </div>

                        <!-- Botón de continuar responsive -->
                        <div class="pt-4 xs:pt-6">
                            <a href="{{ route('checkout.index') }}"
                                class="inline-flex items-center justify-center w-full px-4 xs:px-6 sm:px-8 py-3 xs:py-4 text-sm xs:text-base sm:text-lg font-semibold text-white transition-all duration-300 transform rounded-lg shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <i class="mr-2 xs:mr-3 fas fa-arrow-right text-sm xs:text-base"></i>
                                <span>
                                    <span class="hidden sm:inline">Continuar al Pago</span>
                                    <span class="sm:hidden xs:inline">Continuar</span>
                                    <span class="xs:hidden">Pagar</span>
                                </span>
                                <i class="ml-2 xs:ml-3 fas fa-credit-card text-sm xs:text-base"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>