<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::deleteDirectory('products');//Borra el directorio de productos
        Storage::makeDirectory('products');//Crea el directorio de productos
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Usuario',
            'last_name' => 'Prueba',
            'document_type' => 1, // 1 = Cédula, 2 = Pasaporte, 3 = RUC
            'document_number' => '1234567890',
            'phone' => '0987654321',
            'email' => 'Admin@example.com',
            'password' => bcrypt('secreto123'),
        ]);

        $this->call([
                // Seeders geográficos (deben ir en orden por las relaciones)
            ProvinceSeeder::class,
            CantonSeeder::class,
            ParishSeeder::class,

                // Otros seeders
            FamilySeeder::class,
            OptionSeeder::class,
        ]);

        // Product::factory(50)->create();
    }
}
