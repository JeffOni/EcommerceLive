<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Family;
use App\Models\Category;
use App\Models\Subcategory;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Array Crear familias, categorias y subcategorias en base de datos

        $families = [
            'Peces' => [
                'Peces de Agua Dulce' => [
                    'Importados',
                    'Nacionales',
                ],
                'Peces de Agua Salada' => [
                    'Importados',
                    'Nacionales',
                ],
            ],
            'Mariscos' => [
                'Crustaceos' => [
                    'Importados',
                    'Nacionales',
                    'Mar',
                    'Rio',
                ],
                'Moluscos' => [
                    'Importados',
                    'Nacionales',
                    'Mar',
                    'Rio',
                ],
            ],
        ];

        // Crear familias, categorias y subcategorias eb base de datos llamas el seeder en database/seeders/DatabaseSeeder.php

        foreach ($families as $family => $categories) {
            $family = Family::create([
                'name' => $family,
            ]);
            //logica simple para crear familias, categorias y subcategorias en base de datos
            //sin mucha personalizacion o control sobre las relaciones es decir lo usa automaticamente con las relaciones de eloquent
            // foreach ($categories as $category => $subcategories) {
            //     $category = $family->categories()->create([
            //         'name' => $category,
            //     ]);

            //     foreach ($subcategories as $subcategory) {
            //         $category->subcategories()->create([
            //             'name' => $subcategory,
            //         ]);
            //     }
            // }

            //logica mas personalizada para crear familias, categorias y subcategorias en base de datos
            foreach ($categories as $category => $subcategories) {
                $category = Category::create([
                    'name' => $category,
                    'family_id' => $family->id,
                ]);

                foreach ($subcategories as $subcategory) {
                    Subcategory::create([
                        'name' => $subcategory,
                        'category_id' => $category->id,
                    ]);
                }
            }
        }
    }
}
