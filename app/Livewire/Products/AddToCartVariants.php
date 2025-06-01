<?php

/*
|--------------------------------------------------------------------------
| AddToCartVariants Livewire Component - MEJORAS UX/UI IMPLEMENTADAS
|--------------------------------------------------------------------------
|
| OPTIMIZACIONES REALIZADAS EN ESTE COMPONENTE:
|
| 1. GESTIÓN MEJORADA DE VARIANTES:
|    - Computed property variantInfo con información completa
|    - Validación de stock por variante (hasStock)
|    - Precios dinámicos según la variante seleccionada
|    - Imágenes locales en lugar de URLs externas
|
| 2. CONTROLES DE CANTIDAD OPTIMIZADOS:
|    - Integración con Alpine.js para respuesta instantánea
|    - Validación de stock dinámico por variante
|    - Reseteo automático de cantidad al cambiar variante
|
| 3. VALIDACIÓN DE STOCK AVANZADA:
|    - Verificación de disponibilidad por variante
|    - Estados diferenciados: disponible/agotado/seleccionar opciones
|    - Límites dinámicos de cantidad según stock
|
| 4. EXPERIENCIA DE USUARIO MEJORADA:
|    - Estados visuales claros con colores diferenciados
|    - Alertas informativas detalladas
|    - Botones inteligentes que cambian según el contexto
|    - Feedback inmediato en todos los controles
|
*/

namespace App\Livewire\Products;

use App\Models\Feature;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddToCartVariants extends Component
{

    public $product;

    public $quantity = 1;

    public $selectedFeatures = [];

    public function mount()
    {
        foreach ($this->product->options as $option) {
            $features = collect($option->pivot->features);
            $this->selectedFeatures[$option->id] = $features->first()['id'] ?? null;
        }
    }

    #[Computed]
    public function variant()
    {
        // Convertir selectedFeatures a array simple de IDs
        $selectedFeatureIds = collect($this->selectedFeatures)->values();

        return $this->product->variants->filter(function ($variant) use ($selectedFeatureIds) {
            $variantFeatureIds = $variant->features->pluck('id')->sort();
            $selectedIds = $selectedFeatureIds->sort();

            // Verificar que ambos conjuntos sean idénticos
            return $variantFeatureIds->diff($selectedIds)->isEmpty() &&
                $selectedIds->diff($variantFeatureIds)->isEmpty();
        })->first();
    }

    /**
     * Obtiene la imagen del variant seleccionado con fallback al producto
     */
    #[Computed]
    public function currentImage()
    {
        if ($this->variant && $this->variant->image) {
            return $this->variant->image;
        }

        return $this->product->image ?? asset('img/no-image.png');
    }

    /**
     * Debug helper para verificar el estado del variant
     */
    public function debugVariant()
    {
        logger('Selected Features:', $this->selectedFeatures);
        logger('Available Variants:', $this->product->variants->pluck('id')->toArray());

        if ($this->variant) {
            logger('Found Variant:', [
                'id' => $this->variant->id,
                'features' => $this->variant->features->pluck('id')->toArray(),
                'image' => $this->variant->image
            ]);
        } else {
            logger('No variant found');
        }
    }

    public function addToCart()
    {
        // Validar que existe variant y tiene stock
        if (!$this->variant) {
            $this->dispatch('swal', [
                'title' => 'Selecciona todas las opciones',
                'text' => 'Por favor selecciona todas las opciones del producto antes de agregarlo al carrito.',
                'icon' => 'warning',
                'timer' => 3000,
                'showConfirmButton' => false,
            ]);
            return;
        }

        if (!$this->variantInfo['hasStock']) {
            $this->dispatch('swal', [
                'title' => 'Producto agotado',
                'text' => 'Lo sentimos, esta variante del producto no tiene stock disponible.',
                'icon' => 'error',
                'timer' => 3000,
                'showConfirmButton' => false,
            ]);
            return;
        }

        if ($this->quantity > $this->variantInfo['stock']) {
            $this->dispatch('swal', [
                'title' => 'Stock insuficiente',
                'text' => 'Solo tenemos ' . $this->variantInfo['stock'] . ' unidades disponibles.',
                'icon' => 'warning',
                'timer' => 3000,
                'showConfirmButton' => false,
            ]);
            return;
        }

        Cart::instance('shopping');
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->quantity,
            'price' => $this->product->price,
            // 'weight' => 0, // Peso opcional, si no se usa puede ser 0
            'options' => [
                'image' => $this->currentImage, // Suponiendo que el producto tiene una imagen
                'sku' => $this->variant->sku, // SKU del producto
                'features' => Feature::whereIn('id', $this->selectedFeatures)// Características seleccionadas
                    ->pluck('description', 'id')// Puedes agregar más opciones si es necesario
                    ->toArray(),
            ],
        ]);

        if (auth()->check()) {
            // Si el usuario está autenticado, actualizamos el carrito del usuario
            Cart::store(auth()->id());
        } //else {
        //     // Si no está autenticado, guardamos en la sesión
        //     Cart::store('guest');
        // }

        // Lógica para agregar al carrito
        $this->dispatch('swal', [
            'title' => 'Producto agregado al carrito!',
            'text' => 'El producto ' . $this->product->name . ' ha sido agregado exitosamente.',
            'icon' => 'success',
            'timer' => 3000,
            'showConfirmButton' => false,
        ]);

        // Resetear cantidad después de agregar
        // $this->quantity = 1; // Reinicia la cantidad después de agregar al carrito
    }


    /**
     * Obtiene información adicional del variant actual
     */
    #[Computed]
    public function variantInfo()
    {
        if (!$this->variant) {
            return [
                'available' => false,
                'price' => $this->product->price,
                'stock' => 0,
                'sku' => $this->product->sku,
                'image' => $this->product->image,
                'hasStock' => false
            ];
        }

        $stock = $this->variant->stock ?? 0;

        return [
            'available' => true,
            'price' => $this->variant->price ?? $this->product->price,
            'stock' => $stock,
            'sku' => $this->variant->sku,
            'image' => $this->variant->image,
            'hasStock' => $stock > 0
        ];
    }

    /**
     * Obtiene las imágenes disponibles para el variant actual
     */
    #[Computed]
    public function availableImages()
    {
        $images = collect();

        // Agregar imagen principal del producto
        if ($this->product->image) {
            $images->push([
                'url' => $this->product->image,
                'type' => 'product',
                'active' => !$this->variant
            ]);
        }

        // Agregar imagen del variant si existe
        if ($this->variant && $this->variant->image) {
            $images->push([
                'url' => $this->variant->image,
                'type' => 'variant',
                'active' => true
            ]);
        }

        // Si no hay imágenes, usar imagen local
        if ($images->isEmpty()) {
            $images->push([
                'url' => asset('img/no-image.png'),
                'type' => 'placeholder',
                'active' => true
            ]);
        }

        return $images;
    }

    /**
     * Resetea la cantidad cuando cambian las features seleccionadas
     */
    public function updatedSelectedFeatures()
    {
        // Resetear cantidad a 1 cuando cambie la variante
        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.products.add-to-cart-variants');
    }
}
