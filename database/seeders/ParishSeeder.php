<?php

namespace Database\Seeders;

use App\Models\Parish;
use App\Models\Canton;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParishSeeder extends Seeder
{
    /**
     * Sembrar las parroquias del Ecuador
     * Datos cargados desde archivo JSON externo para mejor organización
     */
    public function run(): void
    {
        // Cargar datos desde JSON
        $jsonPath = database_path('seeders/data/parishes_data.json');

        if (!file_exists($jsonPath)) {
            $this->command->error("Archivo de datos no encontrado: {$jsonPath}");
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        if (!$data) {
            $this->command->error("Error al cargar datos JSON");
            return;
        }

        // Procesar cada provincia
        foreach ($data['provinces'] as $provinceCode => $province) {
            $this->command->info("Procesando provincia: {$province['name']}");

            foreach ($province['cantons'] as $cantonCode => $canton) {
                $this->seedParishesForCanton($cantonCode, $canton['parishes']);
            }
        }

        $this->command->info("Parroquias sembradas exitosamente desde archivo JSON.");
    }

    /**
     * Procesar parroquias para un cantón específico
     */
    private function seedParishesForCanton($cantonCode, $parishes)
    {
        $canton = Canton::where('code', $cantonCode)->first();

        if (!$canton) {
            $this->command->warn("Cantón no encontrado: {$cantonCode}");
            return;
        }

        foreach ($parishes as $parish) {
            Parish::firstOrCreate([
                'code' => $parish['code']
            ], [
                'name' => $parish['name'],
                'code' => $parish['code'],
                'canton_id' => $canton->id,
                'default_postal_code' => $parish['default_postal_code'] ?? null
            ]);
        }

        $this->command->info("✓ Cantón {$canton->name}: " . count($parishes) . " parroquias procesadas");
    }
}