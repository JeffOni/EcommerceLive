<x-container>
    <div class="card">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

            <div class="col-span-1">
                <figure>
                    <img src="{{ $product->image }}" alt="Imagen de Producto" class=" aspect-[16/9] object-cover ">
                    <figcaption class="mt-2 text-lg font-semibold text-center">
                        {{ $product->name }}
                    </figcaption>
                </figure>
                <div class="mb-2">
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $product->description }}
                    </p>

                </div>
            </div>
            <div class="col-span-1">
                <h1 class="mb-2 text-2xl font-bold text-gray-500 dark:text-gray-400">
                    {{ $product->name }}
                </h1>
                <div class="flex space-x-2 rtl:space-x-reverse">
                    <ul class="flex mb-4 space-x-1 text-sm rtl:space-x-reverse">
                        <li>
                            <i class="text-yellow-400 fa-solid fa-star"></i>
                        </li>
                        <li>
                            <i class="text-yellow-400 fa-solid fa-star"></i>
                        </li>
                        <li>
                            <i class="text-yellow-400 fa-solid fa-star"></i>
                        </li>
                        <li>
                            <i class="text-yellow-400 fa-solid fa-star"></i>
                        </li>
                        <li>
                            <i class="text-yellow-400 fa-solid fa-star"></i>
                        </li>
                    </ul>
                    <p class="text-sm text-gray-700">4.7 (55)</p>
                </div>

                <p class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">
                    ${{ number_format($product->price, 2) }}

                </p>

                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <button
                        class="flex items-center justify-center w-10 h-10 text-white transition-colors duration-200 bg-gray-600 rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                            </path>
                        </svg>
                    </button>
                    <span
                        class="inline-flex items-center justify-center w-16 h-10 text-lg font-semibold text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        1
                    </span>
                    <button
                        class="flex items-center justify-center w-10 h-10 text-white transition-colors duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>

                {{-- Botón Agregar al Carrito --}}
                <div class="mt-6">
                    <button
                        class="w-full px-6 py-3 text-lg font-semibold text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 focus:outline-none dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h9.5"></path>
                        </svg>
                        Agregar al Carrito
                    </button>
                </div>
                <div class="flex items-center mt-4 space-x-4 text-gray-700 dark:text-gray-400 rtl:space-x-reverse">
                    <i class="text-2xl fa-solid fa-truck-fast"></i>
                    <p>
                        Entrega a Domicilio
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-container>
