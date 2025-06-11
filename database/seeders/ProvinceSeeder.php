<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Sembrar las 24 provincias oficiales del Ecuador
     * Datos basados en la división política territorial oficial del INEC
     */
    public function run(): void
    {
        $provinces = [
            ['name' => 'Azuay', 'code' => '01'],
            ['name' => 'Bolívar', 'code' => '02'],
            ['name' => 'Cañar', 'code' => '03'],
            ['name' => 'Carchi', 'code' => '04'],
            ['name' => 'Cotopaxi', 'code' => '05'],
            ['name' => 'Chimborazo', 'code' => '06'],
            ['name' => 'El Oro', 'code' => '07'],
            ['name' => 'Esmeraldas', 'code' => '08'],
            ['name' => 'Guayas', 'code' => '09'],
            ['name' => 'Imbabura', 'code' => '10'],
            ['name' => 'Loja', 'code' => '11'],
            ['name' => 'Los Ríos', 'code' => '12'],
            ['name' => 'Manabí', 'code' => '13'],
            ['name' => 'Morona Santiago', 'code' => '14'],
            ['name' => 'Napo', 'code' => '15'],
            ['name' => 'Pastaza', 'code' => '16'],
            ['name' => 'Pichincha', 'code' => '17'],
            ['name' => 'Tungurahua', 'code' => '18'],
            ['name' => 'Zamora Chinchipe', 'code' => '19'],
            ['name' => 'Galápagos', 'code' => '20'],
            ['name' => 'Sucumbíos', 'code' => '21'],
            ['name' => 'Orellana', 'code' => '22'],
            ['name' => 'Santo Domingo de los Tsáchilas', 'code' => '23'],
            ['name' => 'Santa Elena', 'code' => '24'],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }
    }
}
