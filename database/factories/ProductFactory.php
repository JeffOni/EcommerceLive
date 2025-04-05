<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Crear una imagen falsa y guardarla en storage
        $image = UploadedFile::fake()->image('product.jpg', 640, 480);//uploadFile sirve para crear una imagen falsa
        $imagePath = $image->store('products', 'public'); // Guarda la imagen en storage/app/public/products

        return [
            'sku' => $this->faker->unique()->numberBetween(10000, 99999),
            'name' => $name = $this->faker->words(3, true),
            'description' => $this->faker->text(200),
            'image_path' => 'products/' . basename($imagePath), // Solo guardamos el nombre de la imagen
            'price' => $this->faker->randomFloat(2, 1, 100),
            'subcategory_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
