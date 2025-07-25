<x-app-layout>
    <x-container class="py-8">
        <h1 class="text-2xl font-bold mb-6">Catálogo de Productos</h1>
        @if($products->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 text-yellow-800 rounded">
            No hay productos disponibles actualmente.
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <a href="{{ route('products.show', $product) }}"
                class="block bg-white rounded-lg shadow hover:shadow-lg transition">
                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                    class="w-full h-40 object-cover rounded-t-lg">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-500 mb-2">{{ $product->subcategory->name }}</p>
                    <span class="text-xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $products->links() }}
        </div>
        @endif
    </x-container>
</x-app-layout>