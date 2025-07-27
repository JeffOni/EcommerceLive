<x-app-layout>
    <!-- Contenedor principal que alberga el contenido del formulario de autenticación. -->
    <x-container class="mt-8">
        <div class="grid grid-cols-3 gap-4">

            <div class="col-span-2">
                @livewire('shipping-addresses')
            </div>
        </div>
        <div class="col-span-1">
            <div class="sticky overflow-hidden bg-white border border-gray-200 rounded-lg shadow-xl top-8">
                <!-- Header del resumen -->
                <div class="px-6 py-4 text-white bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 rounded-lg bg-white/20">
                            <i class="text-lg text-white fas fa-receipt"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Resumen del Pedido</h2>
                            <p class="text-sm text-green-100">{{ Cart::instance('shopping')->count() }} {{
                                Cart::instance('shopping')->count() === 1 ? 'artículo' : 'artículos' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lista de productos -->
                <div class="p-6 space-y-4">
                    @foreach (Cart::content() as $item)
                    <div
                        class="flex items-center p-4 space-x-4 transition-all duration-300 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md hover:border-gray-200">
                        <!-- Imagen del producto -->
                        <div class="relative flex-shrink-0">
                            <div class="w-16 h-16 overflow-hidden bg-gray-100 rounded-lg shadow-md">
                                @if ($item->options->image)
                                <img class="object-cover w-full h-full" src="{{ $item->options->image }}"
                                    alt="{{ $item->name }}">
                                @else
                                <div
                                    class="flex items-center justify-center w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100">
                                    <i class="text-2xl text-indigo-400 fas fa-image"></i>
                                </div>
                                @endif
                            </div>
                            <!-- Badge de cantidad -->
                            <div
                                class="absolute flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-green-500 rounded-full shadow-lg -top-2 -right-2">
                                {{ $item->qty }}
                            </div>
                        </div>

                        <!-- Información del producto -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 line-clamp-1">{{ $item->name }}</h4>
                            <div class="flex items-center justify-between mt-1">
                                @if(isset($item->options['is_on_offer']) && $item->options['is_on_offer'])
                                <div class="flex flex-col">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-red-600 font-medium">${{ number_format($item->price,
                                            2) }}</span>
                                        <span class="text-xs bg-red-500 text-white px-1 py-0.5 rounded">
                                            {{ $item->options['discount_percentage'] }}% OFF
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500 line-through">
                                        ${{ number_format($item->options['original_price'], 2) }} c/u
                                    </span>
                                </div>
                                @else
                                <span class="text-sm text-gray-600">${{ number_format($item->price, 2) }} c/u</span>
                                @endif
                                <span class="font-bold text-green-600">${{ number_format($item->price * $item->qty, 2)
                                    }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Separador con gradiente -->
                    <div class="h-px my-6 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>

                    <!-- Total final -->
                    <div
                        class="relative p-6 overflow-hidden text-white rounded-lg shadow-xl bg-gradient-to-r from-green-600 to-emerald-600">
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white/20">
                                    <i class="text-sm text-white fas fa-dollar-sign"></i>
                                </div>
                                <span class="text-xl font-bold text-white">Total Final</span>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-black text-white">
                                    ${{ Cart::instance('shopping')->total() }}
                                </div>
                                <div class="text-sm text-green-100">IVA incluido</div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de continuar -->
                    <div class="pt-6">
                        <a href="{{ route('checkout.index') }}"
                            class="inline-flex items-center justify-center w-full px-8 py-4 text-lg font-semibold text-white transition-all duration-300 transform rounded-lg shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="mr-3 fas fa-arrow-right"></i>
                            <span>Continuar al Pago</span>
                            <i class="ml-3 fas fa-credit-card"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>