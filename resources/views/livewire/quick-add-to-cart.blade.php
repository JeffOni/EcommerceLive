<button wire:click="addToCart" wire:loading.attr="disabled"
    class="bg-gray-100 text-gray-600 p-3 rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-all duration-300 hover:scale-110 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
    {{ $product->stock <= 0 ? 'disabled' : '' }}>
        <span wire:loading.remove>
            <i class="fas fa-shopping-cart"></i>
        </span>
        <span wire:loading>
            <i class="fas fa-spinner fa-spin"></i>
        </span>
</button>