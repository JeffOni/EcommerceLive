<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endpush

    {{-- swiperslider --}}
    <!-- Slider main container -->
    <div class="mb-12 swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            @foreach ($covers as $cover)
                <div class="swiper-slide">
                    <img src="{{ asset($cover->image) }}" alt="Cover Image" class="w-full aspect-[3/1] object-center">
                </div>
            @endforeach
        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <!-- If we need scrollbar -->
        <div class="swiper-scrollbar"></div>
    </div>

    {{-- Product List --}}

    <x-container>
        <h1 class="mb-4 text-2xl font-bold text-gray-800">
            Ultimos Productos
        </h1>
        <div
            class="grid grid-cols-1 gap-6 transition-all duration-300 ease-out sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($lastProducts as $product)
                <article class="flex flex-col h-full overflow-hidden bg-white rounded-md shadow-md">
                    <div class="relative">
                        <img src="{{ asset($product->image) }}" alt="Product Image" class="object-center w-full h-48">
                        <div class="absolute top-0 left-0 px-2 py-1 text-white bg-blue-500 rounded-br-md">
                            {{ $product->name }}
                        </div>
                    </div>
                    <div class="flex flex-col flex-1 p-4">
                        <h2 class="text-lg font-semibold line-clamp-2 min-h-[56px] text-gray-800">{{ $product->name }}</h2>
                        {{-- <p class="mt-2 text-gray-600">{{ $product->description }}</p> --}}
                        <p class="mt-4 text-xl font-bold text-gray-800">${{ $product->price }}</p>
                        <x-link class="block w-full mt-auto text-center" name="Ver Mas"></x-link>
                    </div>
                </article>
            @endforeach

        </div>
    </x-container>


    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script>
            const swiper = new Swiper('.swiper', {
                // Optional parameters
                // direction: 'vertical',
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },

                // If we need pagination
                pagination: {
                    el: '.swiper-pagination',
                },

                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                // And if we need scrollbar
                scrollbar: {
                    el: '.swiper-scrollbar',
                },
            });
        </script>
    @endpush


</x-app-layout>
