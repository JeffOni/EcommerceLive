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
            'name' => 'Jefferson Alexis',
            'email' => 'Admin@example.com',
            'password' => bcrypt('secreto123'),
        ]);

        $this->call([
            FamilySeeder::class,
            OptionSeeder::class,
        ]);

        Product::factory(50)->create();
    }
}
