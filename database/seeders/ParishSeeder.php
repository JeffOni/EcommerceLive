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

        // Datos legacy (mantener temporalmente para compatibilidad)
        $this->seedLegacyParishes();
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
                'canton_id' => $canton->id
            ]);
        }
    }

    /**
     * Datos legacy - migrar gradualmente al JSON
     */
    private function seedLegacyParishes()
    {
        $parroquias = [
            // PROVINCIA DE BOLÍVAR

            // CANTÓN GUARANDA (0201)
            ['name' => 'Ángel Polibio Chaves', 'code' => '020101', 'canton_code' => '0201'],
            ['name' => 'Gabriel Ignacio Veintimilla', 'code' => '020102', 'canton_code' => '0201'],
            ['name' => 'Guanujo', 'code' => '020103', 'canton_code' => '0201'],
            // Parroquias rurales de Guaranda
            ['name' => 'Facundo Vela', 'code' => '020150', 'canton_code' => '0201'],
            ['name' => 'Julio E. Moreno (Catanahuazo Grande)', 'code' => '020151', 'canton_code' => '0201'],
            ['name' => 'Las Naves', 'code' => '020152', 'canton_code' => '0201'],
            ['name' => 'Salinas', 'code' => '020153', 'canton_code' => '0201'],
            ['name' => 'San Lorenzo', 'code' => '020154', 'canton_code' => '0201'],
            ['name' => 'San Luis de Pambil', 'code' => '020155', 'canton_code' => '0201'],
            ['name' => 'San Simón (Yacoto)', 'code' => '020156', 'canton_code' => '0201'],
            ['name' => 'Santa Fé (Santa Fé)', 'code' => '020157', 'canton_code' => '0201'],
            ['name' => 'Simiatug', 'code' => '020158', 'canton_code' => '0201'],

            // CANTÓN CHILLANES (0202)
            ['name' => 'Chillanes', 'code' => '020201', 'canton_code' => '0202'],
            ['name' => 'San José del Tambo (Tambopamba)', 'code' => '020250', 'canton_code' => '0202'],

            // CANTÓN CHIMBO (0203)
            ['name' => 'Chimbo', 'code' => '020301', 'canton_code' => '0203'],
            ['name' => 'Asunción (Asancoto)', 'code' => '020350', 'canton_code' => '0203'],
            ['name' => 'Caluma', 'code' => '020351', 'canton_code' => '0203'],
            ['name' => 'Magdalena (Chapacoto)', 'code' => '020352', 'canton_code' => '0203'],
            ['name' => 'San José de Chimbo', 'code' => '020353', 'canton_code' => '0203'],
            ['name' => 'Telimbela', 'code' => '020354', 'canton_code' => '0203'],

            // CANTÓN ECHEANDÍA (0204)
            ['name' => 'Echeandía', 'code' => '020401', 'canton_code' => '0204'],

            // CANTÓN SAN MIGUEL (0205)
            ['name' => 'San Miguel', 'code' => '020501', 'canton_code' => '0205'],
            ['name' => 'Balsapamba', 'code' => '020550', 'canton_code' => '0205'],
            ['name' => 'Bilován', 'code' => '020551', 'canton_code' => '0205'],
            ['name' => 'Régulo de Mora', 'code' => '020552', 'canton_code' => '0205'],
            ['name' => 'San Pablo (San Pablo de Atenas)', 'code' => '020553', 'canton_code' => '0205'],
            ['name' => 'Santiago', 'code' => '020554', 'canton_code' => '0205'],
            ['name' => 'San Vicente', 'code' => '020555', 'canton_code' => '0205'],

            // CANTÓN CALUMA (0206)
            ['name' => 'Caluma', 'code' => '020601', 'canton_code' => '0206'],

            // CANTÓN LAS NAVES (0207)
            ['name' => 'Las Naves', 'code' => '020701', 'canton_code' => '0207'],

            // PROVINCIA DE CAÑAR

            // CANTÓN AZOGUES (0301)
            ['name' => 'Aurelio Bayas Martínez (Parroquia Azogues)', 'code' => '030101', 'canton_code' => '0301'],
            ['name' => 'Borrero', 'code' => '030102', 'canton_code' => '0301'],
            ['name' => 'San Francisco', 'code' => '030103', 'canton_code' => '0301'],
            // Parroquias rurales de Azogues
            ['name' => 'Bayas', 'code' => '030150', 'canton_code' => '0301'],
            ['name' => 'Cojitambo', 'code' => '030151', 'canton_code' => '0301'],
            ['name' => 'Déleg', 'code' => '030152', 'canton_code' => '0301'],
            ['name' => 'Guapán', 'code' => '030153', 'canton_code' => '0301'],
            ['name' => 'Javier Loyola (Chuquipata)', 'code' => '030154', 'canton_code' => '0301'],
            ['name' => 'Luis Cordero', 'code' => '030155', 'canton_code' => '0301'],
            ['name' => 'Pindilig', 'code' => '030156', 'canton_code' => '0301'],
            ['name' => 'Rivera', 'code' => '030157', 'canton_code' => '0301'],
            ['name' => 'San Miguel', 'code' => '030158', 'canton_code' => '0301'],
            ['name' => 'Solano', 'code' => '030159', 'canton_code' => '0301'],
            ['name' => 'Taday', 'code' => '030160', 'canton_code' => '0301'],

            // CANTÓN BIBLIÁN (0302)
            ['name' => 'Biblián', 'code' => '030201', 'canton_code' => '0302'],
            ['name' => 'Nazón (Cab. en Pampa de Domínguez)', 'code' => '030250', 'canton_code' => '0302'],
            ['name' => 'San Francisco de Sageo', 'code' => '030251', 'canton_code' => '0302'],
            ['name' => 'Turupamba', 'code' => '030252', 'canton_code' => '0302'],
            ['name' => 'Jerusalem', 'code' => '030253', 'canton_code' => '0302'],

            // CANTÓN CAÑAR (0303)
            ['name' => 'Cañar', 'code' => '030301', 'canton_code' => '0303'],
            ['name' => 'Chorocopte', 'code' => '030350', 'canton_code' => '0303'],
            ['name' => 'General Morales (Socarte)', 'code' => '030351', 'canton_code' => '0303'],
            ['name' => 'Gualleturo', 'code' => '030352', 'canton_code' => '0303'],
            ['name' => 'Honorato Vásquez (Tambo Viejo)', 'code' => '030353', 'canton_code' => '0303'],
            ['name' => 'Ingapirca', 'code' => '030354', 'canton_code' => '0303'],
            ['name' => 'Juncal', 'code' => '030355', 'canton_code' => '0303'],
            ['name' => 'San Antonio', 'code' => '030356', 'canton_code' => '0303'],
            ['name' => 'Suscal', 'code' => '030357', 'canton_code' => '0303'],
            ['name' => 'Tambo', 'code' => '030358', 'canton_code' => '0303'],
            ['name' => 'Zhud', 'code' => '030359', 'canton_code' => '0303'],
            ['name' => 'Ventura', 'code' => '030360', 'canton_code' => '0303'],
            ['name' => 'Ducur', 'code' => '030361', 'canton_code' => '0303'],

            // CANTÓN LA TRONCAL (0304)
            ['name' => 'La Troncal', 'code' => '030401', 'canton_code' => '0304'],
            ['name' => 'Manuel J. Calle', 'code' => '030450', 'canton_code' => '0304'],
            ['name' => 'Pancho Negro', 'code' => '030451', 'canton_code' => '0304'],

            // CANTÓN EL TAMBO (0305)
            ['name' => 'El Tambo', 'code' => '030501', 'canton_code' => '0305'],

            // CANTÓN SUSCAL (0306)
            ['name' => 'Suscal', 'code' => '030601', 'canton_code' => '0306'],

            // CANTÓN DÉLEG (0307)
            ['name' => 'Déleg', 'code' => '030701', 'canton_code' => '0307'],

            // PROVINCIA DE CARCHI

            // CANTÓN TULCÁN (0401)
            ['name' => 'González Suárez', 'code' => '040101', 'canton_code' => '0401'],
            ['name' => 'Tulcán', 'code' => '040102', 'canton_code' => '0401'],
            // Parroquias rurales de Tulcán
            ['name' => 'El Carmelo (El Pun)', 'code' => '040150', 'canton_code' => '0401'],
            ['name' => 'Huaca', 'code' => '040151', 'canton_code' => '0401'],
            ['name' => 'Julio Andrade (Orejuela)', 'code' => '040152', 'canton_code' => '0401'],
            ['name' => 'Maldonado', 'code' => '040153', 'canton_code' => '0401'],
            ['name' => 'Pioter', 'code' => '040154', 'canton_code' => '0401'],
            ['name' => 'Tobar Donoso (La Bocana de Camumbi)', 'code' => '040155', 'canton_code' => '0401'],
            ['name' => 'Tufiño', 'code' => '040156', 'canton_code' => '0401'],
            ['name' => 'Urbina (Taya)', 'code' => '040157', 'canton_code' => '0401'],
            ['name' => 'El Chical', 'code' => '040158', 'canton_code' => '0401'],
            ['name' => 'Mariscal Sucre', 'code' => '040159', 'canton_code' => '0401'],
            ['name' => 'Santa Martha de Cuba', 'code' => '040160', 'canton_code' => '0401'],

            // CANTÓN BOLÍVAR (0402)
            ['name' => 'Bolívar', 'code' => '040201', 'canton_code' => '0402'],
            ['name' => 'García Moreno', 'code' => '040250', 'canton_code' => '0402'],
            ['name' => 'Los Andes', 'code' => '040251', 'canton_code' => '0402'],
            ['name' => 'Monte Olivo', 'code' => '040252', 'canton_code' => '0402'],
            ['name' => 'San Rafael', 'code' => '040253', 'canton_code' => '0402'],
            ['name' => 'San Vicente de Pusir', 'code' => '040254', 'canton_code' => '0402'],

            // CANTÓN ESPEJO (0403)
            ['name' => 'El Ángel', 'code' => '040301', 'canton_code' => '0403'],
            ['name' => '27 de Septiembre', 'code' => '040350', 'canton_code' => '0403'],
            ['name' => 'El Goaltal', 'code' => '040351', 'canton_code' => '0403'],
            ['name' => 'La Libertad (Alizo)', 'code' => '040352', 'canton_code' => '0403'],
            ['name' => 'San Isidro', 'code' => '040353', 'canton_code' => '0403'],

            // CANTÓN MIRA (0404)
            ['name' => 'Mira (Chontahuasi)', 'code' => '040401', 'canton_code' => '0404'],
            ['name' => 'Concepción', 'code' => '040450', 'canton_code' => '0404'],
            ['name' => 'Jijón y Caamaño (Cab. en Río Blanco)', 'code' => '040451', 'canton_code' => '0404'],
            ['name' => 'Juan Montalvo (San Ignacio de Quil)', 'code' => '040452', 'canton_code' => '0404'],

            // CANTÓN MONTÚFAR (0405)
            ['name' => 'San Gabriel', 'code' => '040501', 'canton_code' => '0405'],
            ['name' => 'Cristóbal Colón', 'code' => '040550', 'canton_code' => '0405'],
            ['name' => 'Chitán de Navarrete', 'code' => '040551', 'canton_code' => '0405'],
            ['name' => 'Fernández Salvador', 'code' => '040552', 'canton_code' => '0405'],
            ['name' => 'La Paz', 'code' => '040553', 'canton_code' => '0405'],
            ['name' => 'Piartal', 'code' => '040554', 'canton_code' => '0405'],

            // CANTÓN SAN PEDRO DE HUACA (0406)
            ['name' => 'Huaca', 'code' => '040601', 'canton_code' => '0406'],
            ['name' => 'Mariscal Sucre', 'code' => '040650', 'canton_code' => '0406'],

            // PROVINCIA DE COTOPAXI

            // CANTÓN LATACUNGA (0501)
            ['name' => 'Eloy Alfaro (San Felipe)', 'code' => '050101', 'canton_code' => '0501'],
            ['name' => 'Ignacio Flores (Parque Flores)', 'code' => '050102', 'canton_code' => '0501'],
            ['name' => 'Juan Montalvo (San Sebastián)', 'code' => '050103', 'canton_code' => '0501'],
            ['name' => 'La Matriz', 'code' => '050104', 'canton_code' => '0501'],
            ['name' => 'San Buenaventura', 'code' => '050105', 'canton_code' => '0501'],
            // Parroquias rurales de Latacunga
            ['name' => 'Alaques (Aláquez)', 'code' => '050150', 'canton_code' => '0501'],
            ['name' => 'Belisario Quevedo (Guaytacama)', 'code' => '050151', 'canton_code' => '0501'],
            ['name' => 'Joseguango Bajo', 'code' => '050152', 'canton_code' => '0501'],
            ['name' => 'Mulaló', 'code' => '050153', 'canton_code' => '0501'],
            ['name' => '11 de Noviembre (Ilinchisi)', 'code' => '050154', 'canton_code' => '0501'],
            ['name' => 'Poaló', 'code' => '050155', 'canton_code' => '0501'],
            ['name' => 'San Juan de Pastocalle', 'code' => '050156', 'canton_code' => '0501'],
            ['name' => 'Sigchos', 'code' => '050157', 'canton_code' => '0501'],
            ['name' => 'Tanicuchí', 'code' => '050158', 'canton_code' => '0501'],
            ['name' => 'Toacaso', 'code' => '050159', 'canton_code' => '0501'],

            // CANTÓN LA MANÁ (0502)
            ['name' => 'La Maná', 'code' => '050201', 'canton_code' => '0502'],
            ['name' => 'Guasaganda (Cab. en Guasaganda Centro)', 'code' => '050250', 'canton_code' => '0502'],
            ['name' => 'Pucayacu', 'code' => '050251', 'canton_code' => '0502'],

            // CANTÓN PANGUA (0503)
            ['name' => 'El Corazón', 'code' => '050301', 'canton_code' => '0503'],
            ['name' => 'Moraspungo', 'code' => '050350', 'canton_code' => '0503'],
            ['name' => 'Pinllopata', 'code' => '050351', 'canton_code' => '0503'],
            ['name' => 'Ramón Campaña', 'code' => '050352', 'canton_code' => '0503'],

            // CANTÓN PUJILÍ (0504)
            ['name' => 'Pujilí', 'code' => '050401', 'canton_code' => '0504'],
            ['name' => 'Angamarca', 'code' => '050450', 'canton_code' => '0504'],
            ['name' => 'Chucchilán (Chugchilán)', 'code' => '050451', 'canton_code' => '0504'],
            ['name' => 'Guangaje', 'code' => '050452', 'canton_code' => '0504'],
            ['name' => 'Isinliví (Isinlibí)', 'code' => '050453', 'canton_code' => '0504'],
            ['name' => 'La Victoria', 'code' => '050454', 'canton_code' => '0504'],
            ['name' => 'Pilaló', 'code' => '050455', 'canton_code' => '0504'],
            ['name' => 'Tingo', 'code' => '050456', 'canton_code' => '0504'],
            ['name' => 'Zumbahua', 'code' => '050457', 'canton_code' => '0504'],

            // CANTÓN SALCEDO (0505)
            ['name' => 'San Miguel', 'code' => '050501', 'canton_code' => '0505'],
            ['name' => 'Antonio José Holguín (Santa Lucía)', 'code' => '050550', 'canton_code' => '0505'],
            ['name' => 'Cusubamba', 'code' => '050551', 'canton_code' => '0505'],
            ['name' => 'Mulalillo', 'code' => '050552', 'canton_code' => '0505'],
            ['name' => 'Mulliquindil (Santa Ana)', 'code' => '050553', 'canton_code' => '0505'],
            ['name' => 'Panzaleo', 'code' => '050554', 'canton_code' => '0505'],

            // CANTÓN SAQUISILÍ (0506)
            ['name' => 'Saquisilí', 'code' => '050601', 'canton_code' => '0506'],
            ['name' => 'Canchagua', 'code' => '050650', 'canton_code' => '0506'],
            ['name' => 'Chantilín', 'code' => '050651', 'canton_code' => '0506'],
            ['name' => 'Cochapamba', 'code' => '050652', 'canton_code' => '0506'],

            // CANTÓN SIGCHOS (0507)
            ['name' => 'Sigchos', 'code' => '050701', 'canton_code' => '0507'],
            ['name' => 'Chugchilán', 'code' => '050750', 'canton_code' => '0507'],
            ['name' => 'Isinliví', 'code' => '050751', 'canton_code' => '0507'],
            ['name' => 'Las Pampas', 'code' => '050752', 'canton_code' => '0507'],
            ['name' => 'Palo Quemado', 'code' => '050753', 'canton_code' => '0507'],

            // PROVINCIA DE CHIMBORAZO

            // CANTÓN RIOBAMBA (0601)
            ['name' => 'Lizarzaburu', 'code' => '060101', 'canton_code' => '0601'],
            ['name' => 'Maldonado', 'code' => '060102', 'canton_code' => '0601'],
            ['name' => 'Velasco', 'code' => '060103', 'canton_code' => '0601'],
            ['name' => 'Veloz', 'code' => '060104', 'canton_code' => '0601'],
            ['name' => 'Yaruquíes', 'code' => '060105', 'canton_code' => '0601'],
            // Parroquias rurales de Riobamba
            ['name' => 'Cacha (Cab. en Machángara)', 'code' => '060150', 'canton_code' => '0601'],
            ['name' => 'Calpi', 'code' => '060151', 'canton_code' => '0601'],
            ['name' => 'Cubijíes', 'code' => '060152', 'canton_code' => '0601'],
            ['name' => 'Flores', 'code' => '060153', 'canton_code' => '0601'],
            ['name' => 'Licán', 'code' => '060154', 'canton_code' => '0601'],
            ['name' => 'Licto', 'code' => '060155', 'canton_code' => '0601'],
            ['name' => 'Pungalá', 'code' => '060156', 'canton_code' => '0601'],
            ['name' => 'Punín', 'code' => '060157', 'canton_code' => '0601'],
            ['name' => 'Quimiag', 'code' => '060158', 'canton_code' => '0601'],
            ['name' => 'San Juan', 'code' => '060159', 'canton_code' => '0601'],
            ['name' => 'San Luis', 'code' => '060160', 'canton_code' => '0601'],

            // CANTÓN ALAUSÍ (0602)
            ['name' => 'Alausí', 'code' => '060201', 'canton_code' => '0602'],
            ['name' => 'Achupallas', 'code' => '060250', 'canton_code' => '0602'],
            ['name' => 'Cumandá', 'code' => '060251', 'canton_code' => '0602'],
            ['name' => 'Chunchi', 'code' => '060252', 'canton_code' => '0602'],
            ['name' => 'Gonzol', 'code' => '060253', 'canton_code' => '0602'],
            ['name' => 'Guasuntos', 'code' => '060254', 'canton_code' => '0602'],
            ['name' => 'Huigra', 'code' => '060255', 'canton_code' => '0602'],
            ['name' => 'Multitud', 'code' => '060256', 'canton_code' => '0602'],
            ['name' => 'Pistishi', 'code' => '060257', 'canton_code' => '0602'],
            ['name' => 'Pumallacta', 'code' => '060258', 'canton_code' => '0602'],
            ['name' => 'Sevilla', 'code' => '060259', 'canton_code' => '0602'],
            ['name' => 'Sibambe', 'code' => '060260', 'canton_code' => '0602'],
            ['name' => 'Tixán', 'code' => '060261', 'canton_code' => '0602'],

            // CANTÓN COLTA (0603)
            ['name' => 'Villa La Unión (Cajabamba)', 'code' => '060301', 'canton_code' => '0603'],
            ['name' => 'Cañi', 'code' => '060350', 'canton_code' => '0603'],
            ['name' => 'Columbe', 'code' => '060351', 'canton_code' => '0603'],
            ['name' => 'Juan de Velasco (Pangor)', 'code' => '060352', 'canton_code' => '0603'],
            ['name' => 'Santiago de Quito (Cab. en San Antonio de Quito)', 'code' => '060353', 'canton_code' => '0603'],
            ['name' => 'Sicalpa', 'code' => '060354', 'canton_code' => '0603'],

            // CANTÓN CHAMBO (0604)
            ['name' => 'Chambo', 'code' => '060401', 'canton_code' => '0604'],
            ['name' => 'Valparaíso', 'code' => '060450', 'canton_code' => '0604'],

            // CANTÓN CHUNCHI (0605)
            ['name' => 'Chunchi', 'code' => '060501', 'canton_code' => '0605'],
            ['name' => 'Capzol', 'code' => '060550', 'canton_code' => '0605'],
            ['name' => 'Compud', 'code' => '060551', 'canton_code' => '0605'],
            ['name' => 'Gonzol', 'code' => '060552', 'canton_code' => '0605'],
            ['name' => 'Llagos', 'code' => '060553', 'canton_code' => '0605'],

            // CANTÓN GUAMOTE (0606)
            ['name' => 'Guamote', 'code' => '060601', 'canton_code' => '0606'],
            ['name' => 'Cebadas', 'code' => '060650', 'canton_code' => '0606'],
            ['name' => 'Palmira', 'code' => '060651', 'canton_code' => '0606'],

            // CANTÓN GUANO (0607)
            ['name' => 'Guano', 'code' => '060701', 'canton_code' => '0607'],
            ['name' => 'El Rosario', 'code' => '060750', 'canton_code' => '0607'],
            ['name' => 'Ilapo', 'code' => '060751', 'canton_code' => '0607'],
            ['name' => 'La Providencia', 'code' => '060752', 'canton_code' => '0607'],
            ['name' => 'San Andrés', 'code' => '060753', 'canton_code' => '0607'],
            ['name' => 'San Gerardo', 'code' => '060754', 'canton_code' => '0607'],
            ['name' => 'San Isidro de Patulú', 'code' => '060755', 'canton_code' => '0607'],
            ['name' => 'San José del Chazo', 'code' => '060756', 'canton_code' => '0607'],
            ['name' => 'Santa Fé de Galán', 'code' => '060757', 'canton_code' => '0607'],
            ['name' => 'Valparaíso', 'code' => '060758', 'canton_code' => '0607'],

            // CANTÓN PALLATANGA (0608)
            ['name' => 'Pallatanga', 'code' => '060801', 'canton_code' => '0608'],

            // CANTÓN PENIPE (0609)
            ['name' => 'Penipe', 'code' => '060901', 'canton_code' => '0609'],
            ['name' => 'Bilbao (Cab. en Bilbao)', 'code' => '060950', 'canton_code' => '0609'],
            ['name' => 'El Altar', 'code' => '060951', 'canton_code' => '0609'],
            ['name' => 'Matus', 'code' => '060952', 'canton_code' => '0609'],
            ['name' => 'Puela', 'code' => '060953', 'canton_code' => '0609'],
            ['name' => 'San Antonio de Bayushig', 'code' => '060954', 'canton_code' => '0609'],

            // CANTÓN CUMANDÁ (0610)
            ['name' => 'Cumandá', 'code' => '061001', 'canton_code' => '0610'],
        ];

        // Crear las parroquias legacy en la base de datos
        foreach ($parroquias as $parroquia) {
            // Buscar el cantón correspondiente por su código
            $canton = Canton::where('code', $parroquia['canton_code'])->first();

            if ($canton) {
                Parish::firstOrCreate([
                    'code' => $parroquia['code']
                ], [
                    'name' => $parroquia['name'],
                    'code' => $parroquia['code'],
                    'canton_id' => $canton->id
                ]);
            }
        }
    }
}