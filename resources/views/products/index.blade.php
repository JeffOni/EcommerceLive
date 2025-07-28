<x-app-layout>
    @push('css')
    <style>
        .product-card {
            transition: all 0.3s ease;
            will-change: transform;
        }

        .produ {
                {
                -- Products Grid --
            }
        }

        @if($products->isEmpty()) <div class="text-center py-16 bg-cream-50/50 rounded-2xl border border-secondary-200/50"><div class="w-24 h-24 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-6"><i class="fas fa-box-open text-4xl text-secondary-500"></i></div><h3 class="text-2xl font-bold text-primary-800 mb-4">No hay productos disponibles</h3><p class="text-secondary-600 mb-8">No se encontraron productos que coincidan con tus criterios de búsqueda.</p><a href="{{ route('products.index') }}"

        class="inline-flex items-center bg-gradient-to-r from-coral-500 to-coral-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-coral-600 hover:to-coral-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"><i class="fas fa-refresh mr-2"></i>Ver Todos los Productos </a>ver {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(68, 88, 144, 0.25);
        }

        .filter-btn {
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #123155 0%, #D94F41 100%);
            color: white;
        }

        .price-range {
            background: linear-gradient(135deg, #123155 0%, #D94F41 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #123155 0%, #83aec5 50%, #D94F41 100%);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        @media (max-width: 640px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                gap: 1.5rem;
            }
        }

        /* Estilos para ofertas */
        .offer-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .price-original {
            position: relative;
        }

        .price-original::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #ef4444;
            transform: translateY(-50%);
        }

        .savings-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
    </style>
    @endpush

    <div class="hero-gradient text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <x-container class="relative z-10">
            <div class="text-center">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4 drop-shadow-lg">Catálogo de Productos</h1>
                <p class="text-xl text-white/90 max-w-2xl mx-auto drop-shadow">
                    Descubre nuestra amplia selección de productos de alta calidad
                </p>
            </div>
        </x-container>
    </div>

    <x-container class="py-12">
        {{-- Filters Section --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mb-8 border border-secondary-200/50">
            <div class="flex flex-wrap items-center gap-4 mb-6">
                <h2 class="text-lg font-semibold text-primary-800 flex items-center">
                    <i class="fas fa-filter mr-2 text-coral-500"></i>
                    Filtrar por:
                </h2>
                <button
                    class="filter-btn {{ request('filter') == '' ? 'active' : '' }} px-6 py-2 rounded-full border border-secondary-300 hover:border-coral-500 transition-all duration-300 {{ request('filter') == '' ? 'bg-coral-500 text-white border-coral-500' : 'text-primary-700 hover:text-coral-600' }}"
                    onclick="applyFilter('')">
                    Todos
                </button>
                <button
                    class="filter-btn {{ request('filter') == 'bestsellers' ? 'active' : '' }} px-6 py-2 rounded-full border border-secondary-300 hover:border-coral-500 transition-all duration-300 {{ request('filter') == 'bestsellers' ? 'bg-coral-500 text-white border-coral-500' : 'text-primary-700 hover:text-coral-600' }}"
                    onclick="applyFilter('bestsellers')">
                    <i class="fas fa-star mr-1"></i>
                    Más Vendidos
                </button>
                <button
                    class="filter-btn {{ request('filter') == 'offers' ? 'active' : '' }} px-6 py-2 rounded-full border border-secondary-300 hover:border-coral-500 transition-all duration-300 {{ request('filter') == 'offers' ? 'bg-coral-500 text-white border-coral-500' : 'text-primary-700 hover:text-coral-600' }}"
                    onclick="applyFilter('offers')">
                    <i class="fas fa-tag mr-1"></i>
                    Ofertas
                </button>
                <button
                    class="filter-btn {{ request('filter') == 'new' ? 'active' : '' }} px-6 py-2 rounded-full border border-secondary-300 hover:border-coral-500 transition-all duration-300 {{ request('filter') == 'new' ? 'bg-coral-500 text-white border-coral-500' : 'text-primary-700 hover:text-coral-600' }}"
                    onclick="applyFilter('new')">
                    <i class="fas fa-sparkles mr-1"></i>
                    Nuevos
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-primary-700 flex items-center">
                        <i class="fas fa-sort mr-1 text-coral-500"></i>
                        Ordenar por:
                    </label>
                    <select
                        class="border border-secondary-300 rounded-lg px-3 py-2 text-primary-700 focus:ring-2 focus:ring-coral-400 focus:border-coral-400 transition-colors"
                        onchange="applySort(this.value)">
                        <option value="">Relevancia</option>
                        <option value="price_low" {{ request('sort')=='price_low' ? 'selected' : '' }}>Precio: Menor a
                            Mayor</option>
                        <option value="price_high" {{ request('sort')=='price_high' ? 'selected' : '' }}>Precio: Mayor a
                            Menor</option>
                        <option value="name" {{ request('sort')=='name' ? 'selected' : '' }}>Nombre A-Z</option>
                        <option value="newest" {{ request('sort')=='newest' ? 'selected' : '' }}>Más Recientes</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-primary-700 flex items-center">
                        <i class="fas fa-eye mr-1 text-coral-500"></i>
                        Mostrar:
                    </label>
                    <select
                        class="border border-secondary-300 rounded-lg px-3 py-2 text-primary-700 focus:ring-2 focus:ring-coral-400 focus:border-coral-400 transition-colors"
                        onchange="applyPerPage(this.value)">
                        <option value="12" {{ request('per_page', 12)==12 ? 'selected' : '' }}>12 productos</option>
                        <option value="24" {{ request('per_page')==24 ? 'selected' : '' }}>24 productos</option>
                        <option value="48" {{ request('per_page')==48 ? 'selected' : '' }}>48 productos</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Products Grid --}}
        @if($products->isEmpty())
        <div class="text-center py-16 bg-cream-50/50 rounded-2xl border border-secondary-200/50">
            <div class="w-24 h-24 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-box-open text-4xl text-secondary-500"></i>
            </div>
            <h3 class="text-2xl font-bold text-primary-800 mb-4">No hay productos disponibles</h3>
            <p class="text-secondary-600 mb-8">No se encontraron productos que coincidan con tus criterios de búsqueda.
            </p>
            <a href="{{ route('welcome') }}"
                class="inline-flex items-center bg-gradient-to-r from-coral-500 to-coral-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-coral-600 hover:to-coral-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i class="fas fa-home mr-2"></i>Volver al Inicio
            </a>
        </div>
        @else
        <div class="product-grid">
            @foreach($products as $product)
            <article
                class="product-card group bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-secondary-200/50 hover:border-coral-300/50 transition-all duration-300">
                <div class="relative overflow-hidden">
                    {{-- Usamos el accessor image que ya existe en el modelo --}}
                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                        class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500"
                        onerror="this.src='https://via.placeholder.com/300x256/e5e7eb/9ca3af?text=Sin+Imagen'"
                        loading="lazy">

                    {{-- Badge de stock y ofertas --}}
                    @if($product->is_on_valid_offer)
                    <div
                        class="absolute top-4 left-4 bg-coral-500 text-white px-3 py-1 rounded-full text-sm font-semibold offer-badge shadow-lg">
                        <i class="fas fa-fire mr-1"></i>{{ $product->discount_percentage }}% OFF
                    </div>
                    @elseif($product->hasAvailableStock())
                    <div
                        class="absolute top-4 left-4 bg-secondary-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                        <i class="fas fa-check mr-1"></i>Disponible
                    </div>
                    @else
                    <div
                        class="absolute top-4 left-4 bg-slate-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                        <i class="fas fa-times mr-1"></i>Agotado
                    </div>
                    @endif

                    {{-- Wishlist button --}}
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <button
                            class="w-10 h-10 bg-cream-50/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg hover:bg-cream-100 transition-all duration-200 hover:scale-110 border border-secondary-200/50">
                            <i
                                class="fas fa-heart text-slate-600 hover:text-coral-500 transition-colors duration-200"></i>
                        </button>
                    </div>

                    {{-- Quick view overlay --}}
                    <div
                        class="absolute inset-0 bg-primary-900/60 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center backdrop-blur-sm">
                        <a href="{{ route('products.show', $product) }}"
                            class="bg-cream-50 text-slate-800 px-6 py-3 rounded-full font-semibold hover:bg-cream-100 transition-all duration-200 transform scale-90 group-hover:scale-100 shadow-lg border border-secondary-200">
                            <i class="fas fa-eye mr-2 text-coral-500"></i>Vista Rápida
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    {{-- Category badge --}}
                    <div class="mb-3">
                        <span
                            class="text-sm text-coral-600 font-medium bg-coral-50 px-3 py-1 rounded-full border border-coral-200/50">
                            {{ $product->subcategory->category->family->name ?? 'Producto' }}
                        </span>
                    </div>

                    {{-- Product name --}}
                    <h3
                        class="text-lg font-bold text-slate-800 mb-3 line-clamp-2 group-hover:text-coral-600 transition-colors duration-200">
                        {{ $product->name }}
                    </h3>

                    {{-- Description --}}
                    @if($product->description)
                    <p class="text-slate-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                        {{ Str::limit($product->description, 80) }}
                    </p>
                    @endif

                    {{-- Precios con ofertas --}}
                    <div class="flex items-center justify-between mb-4">
                        @if($product->is_on_valid_offer)
                        <div class="flex flex-col">
                            <div class="text-2xl font-bold text-coral-600">
                                ${{ number_format($product->current_price, 2) }}
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-lg text-slate-500 line-through">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                                <span class="bg-coral-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    -${{ number_format($product->savings_amount, 2) }}
                                </span>
                            </div>
                        </div>
                        @else
                        <div
                            class="text-2xl font-bold bg-gradient-to-r from-primary-700 to-coral-500 bg-clip-text text-transparent">
                            ${{ number_format($product->price, 2) }}
                        </div>
                        @endif

                        <div class="flex items-center text-coral-400">
                            @for($i = 1; $i <= 5; $i++) <i class="fas fa-star text-sm"></i>
                                @endfor
                                <span class="text-slate-500 text-sm ml-2">(4.8)</span>
                        </div>
                    </div>

                    {{-- Stock info --}}
                    @if($product->hasAvailableStock())
                    <div class="flex items-center text-secondary-600 text-sm mb-4">
                        <i class="fas fa-box mr-2"></i>
                        <span>{{ $product->getAvailableStock() }} en stock</span>
                    </div>
                    @else
                    <div class="flex items-center text-coral-600 text-sm mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>Producto agotado</span>
                    </div>
                    @endif

                    {{-- Action buttons --}}
                    <div class="flex gap-3">
                        <a href="{{ route('products.show', $product) }}"
                            class="flex-1 bg-gradient-to-r from-primary-700 to-coral-500 text-white text-center py-3 px-4 rounded-xl font-semibold hover:from-primary-800 hover:to-coral-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-eye mr-2"></i>Ver Detalles
                        </a>
                        <livewire:quick-add-to-cart :product="$product" :key="'index-cart-'.$product->id" />
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>
        @endif
    </x-container>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wishlist functionality
            document.querySelectorAll('.fa-heart').forEach(heart => {
                heart.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    
                    this.classList.toggle('text-red-500');
                    this.classList.toggle('text-gray-600');
                    
                    // Animation
                    this.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });
        });

        // Función para aplicar filtros
        function applyFilter(filter) {
            const url = new URL(window.location);
            if (filter) {
                url.searchParams.set('filter', filter);
            } else {
                url.searchParams.delete('filter');
            }
            // Resetear página a 1 cuando se cambia filtro
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        // Función para aplicar ordenamiento
        function applySort(sort) {
            const url = new URL(window.location);
            if (sort) {
                url.searchParams.set('sort', sort);
            } else {
                url.searchParams.delete('sort');
            }
            // Resetear página a 1 cuando se cambia ordenamiento
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        // Función para cambiar productos por página
        function applyPerPage(perPage) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', perPage);
            // Resetear página a 1 cuando se cambia cantidad por página
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    </script>
    @endpush
</x-app-layout>