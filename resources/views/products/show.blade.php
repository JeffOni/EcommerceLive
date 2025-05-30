<x-app-layout>
    {{-- Breadcrumb Mejorado --}}
    <x-container class="py-4">
        <nav class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400" aria-label="Breadcrumb">
            <a href="/"
                class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                </svg>
                Inicio
            </a>

            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>

            <a href="{{ route('families.show', $product->subcategory->category->family->id) }}"
                class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                {{ $product->subcategory->category->family->name }}
            </a>

            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>

            <a href="{{ route('categories.show', $product->subcategory->category) }}"
                class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                {{ $product->subcategory->category->name }}
            </a>

            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>

            <a href="{{ route('subcategories.show', $product->subcategory) }}"
                class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                {{ $product->subcategory->name }}
            </a>

            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>

            <span class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
        </nav>
    </x-container>

    {{-- Contenido del producto --}}
    @if ($product->variants->count() > 0)
        @livewire('products.add-to-cart-variants', ['product' => $product])
    @else
        @livewire('products.add-to-cart', ['product' => $product])
    @endif




</x-app-layout>
