<x-app-layout>
    <!-- Contenedor principal que alberga el contenido del formulario de autenticación. -->
    <x-container>
        <div class="grid grid-cols-3 gap-4">

            <div class="col-span-2">
                @livewire('shipping-addresses')
            </div>
        </div>
        <div class="col-span-1">
            <div class="mb-4 overflow-hidden bg-white rounded shadow-lg">
                <div class="flex items-center justify-between p-4 text-white bg-blue-600">
                    <p class="font-semibold">
                        Resumen de compras ({{ Cart::instance('shopping')->count() }})
                    </p>
                    <a href="{{ route('cart.index') }}"
                        class="text-white transition-colors duration-200 cursor-pointer hover:text-blue-200">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="text-sm">Total: {{ Cart::instance('shopping')->total() }}</span>
                    </a>
                </div>
                <div class="p-4 text-grys-600">
                    <ul>
                        @foreach (Cart::content() as $item)
                            <li class="flex items-center space-x-4 ">
                                <figure class="shrink-0">
                                    <img class="h-12 aspect-square" src="{{ $item->options->image }}" alt="">
                                </figure>
                                <div class="flex-1">
                                    <p class="font-semibold">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-600 truncate">Precio: {{ $item->price }}</p>
                                </div>
                                <div>

                                    <p class="text-sm text-gray-600">Cantidad: {{ $item->qty }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <hr class="my-4 border-gray-200">
                    <div>
                        <p class="text-lg font-semibold text-gray-800">
                            Total: {{ Cart::instance('shopping')->subtotal() }}

                        </p>
                    </div>
                </div>

            </div>
            <a href="{{ route('checkout.index') }}"
                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Siguiente
            </a>
        </div>
    </x-container>
</x-app-layout>
