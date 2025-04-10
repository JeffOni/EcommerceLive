<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //seeder para crear las opciones de los productos con features osea variantes
        $options = [
            [
                'name' => 'Tamaño',
                'type' => 1,
                'features' => [
                    [
                        'value' => 'Pequeño',
                        'description' => 'Ideal para una persona',
                    ],
                    [
                        'value' => 'Mediano',
                        'description' => 'Ideal para dos personas',
                    ],
                    [
                        'value' => 'Grande',
                        'description' => 'Ideal para tres o más personas',
                    ],
                ],
            ],
            [
                'name' => 'Pelado',
                'type' => 2,
                'features' => [
                    [
                        'value' => '#FFFFFF',
                        'description' => 'Con pelado',
                    ],
                    [
                        'value' => '#000000',
                        'description' => 'Sin pelado',
                    ],
                ],
            ],
            [
                'name' => 'Cortado',
                'type' => 1,
                'features' => [
                    [
                        'value' => 'Cortado',
                        'description' => 'Cortado',
                    ],
                    [
                        'value' => 'Entero',
                        'description' => 'Entero',
                    ],
                ],
            ],
        ];

        // iterar sobre las opciones y crear cada una de ellas
        // y luego iterar sobre las características de cada opción
        // y crear cada una de ellas
        // y asociarlas a la opción correspondiente
        // y guardar en la base de datos
        foreach ($options as $option) {
            $optionModel = Option::create([
                'name' => $option['name'],
                'type' => $option['type'],
            ]);

            // iterar sobre las características de cada opción
            // y crear cada una de ellas
            // y asociarlas a la opción correspondiente
            // y guardar en la base de datos
            //utiliza las realciones de Eloquent para crear las características
            //tambien se puede crear sin realciones eloquent solo con el modelo con el ejemplo de arriba
            //pierde eficiencia al solo crear con el modelo y no con las relaciones.
            foreach ($option['features'] as $feature) {
                $optionModel->features()->create([
                    'option_id' => $optionModel->id,
                    'value' => $feature['value'],
                    'description' => $feature['description'],

                ]);
            }
        }

    }
}
