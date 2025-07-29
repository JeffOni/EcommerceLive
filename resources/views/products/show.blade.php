<x-app-layout>
    {{-- Breadcrumb Mejorado --}}
    <x-container class="py-4">
        <nav class="flex flex-wrap items-center gap-1 px-4 sm:px-0 text-[11px] sm:text-sm text-gray-600 dark:text-gray-400"
            aria-label="Breadcrumb">
            <a href="/"
                class="flex items-center truncate transition-colors duration-200 hover:text-blue-600 dark:hover:text-blue-400">
                <svg class="w-3 h-3 mr-1 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                </svg>
                Inicio
            </a>

            <span class="mx-1">›</span>

            <a href="{{ route('families.show', $product->subcategory->category->family->id) }}"
                class="truncate transition-colors duration-200 hover:text-blue-600 dark:hover:text-blue-400">
                {{ $product->subcategory->category->family->name }}
            </a>

            <span class="mx-1">›</span>

            <a href="{{ route('categories.show', $product->subcategory->category) }}"
                class="truncate transition-colors duration-200 hover:text-blue-600 dark:hover:text-blue-400">
                {{ $product->subcategory->category->name }}
            </a>

            <span class="mx-1">›</span>

            <a href="{{ route('subcategories.show', $product->subcategory) }}"
                class="truncate transition-colors duration-200 hover:text-blue-600 dark:hover:text-blue-400">
                {{ $product->subcategory->name }}
            </a>

            <span class="mx-1">›</span>

            <span class="font-medium text-gray-900 truncate dark:text-white">{{ $product->name }}</span>
        </nav>
    </x-container>

    {{-- Contenido del producto --}}
    @if ($product->variants->count() > 0)
    @livewire('products.add-to-cart-variants', ['product' => $product])
    @else
    @livewire('products.add-to-cart', ['product' => $product])
    @endif




</x-app-layout>