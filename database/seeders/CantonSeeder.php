<?php

namespace Database\Seeders;

use App\Models\Canton;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CantonSeeder extends Seeder
{
    /**
     * Sembrar los cantones principales del Ecuador
     * Se incluyen los cantones más importantes de cada provincia
     */
    public function run(): void
    {
        $cantones = [
            // AZUAY (01)
            ['name' => 'Cuenca', 'code' => '0101', 'province_code' => '01'],
            ['name' => 'Girón', 'code' => '0102', 'province_code' => '01'],
            ['name' => 'Gualaceo', 'code' => '0103', 'province_code' => '01'],
            ['name' => 'Nabón', 'code' => '0104', 'province_code' => '01'],
            ['name' => 'Paute', 'code' => '0105', 'province_code' => '01'],
            ['name' => 'Pucará', 'code' => '0106', 'province_code' => '01'],
            ['name' => 'San Fernando', 'code' => '0107', 'province_code' => '01'],
            ['name' => 'Santa Isabel', 'code' => '0108', 'province_code' => '01'],
            ['name' => 'Sigsig', 'code' => '0109', 'province_code' => '01'],
            ['name' => 'Oña', 'code' => '0110', 'province_code' => '01'],
            ['name' => 'Chordeleg', 'code' => '0111', 'province_code' => '01'],
            ['name' => 'El Pan', 'code' => '0112', 'province_code' => '01'],
            ['name' => 'Sevilla de Oro', 'code' => '0113', 'province_code' => '01'],
            ['name' => 'Guachapala', 'code' => '0114', 'province_code' => '01'],
            ['name' => 'Camilo Ponce Enríquez', 'code' => '0115', 'province_code' => '01'],

            // BOLÍVAR (02)
            ['name' => 'Guaranda', 'code' => '0201', 'province_code' => '02'],
            ['name' => 'Chillanes', 'code' => '0202', 'province_code' => '02'],
            ['name' => 'Chimbo', 'code' => '0203', 'province_code' => '02'],
            ['name' => 'Echeandía', 'code' => '0204', 'province_code' => '02'],
            ['name' => 'San Miguel', 'code' => '0205', 'province_code' => '02'],
            ['name' => 'Caluma', 'code' => '0206', 'province_code' => '02'],
            ['name' => 'Las Naves', 'code' => '0207', 'province_code' => '02'],

            // CAÑAR (03)
            ['name' => 'Azogues', 'code' => '0301', 'province_code' => '03'],
            ['name' => 'Biblián', 'code' => '0302', 'province_code' => '03'],
            ['name' => 'Cañar', 'code' => '0303', 'province_code' => '03'],
            ['name' => 'La Troncal', 'code' => '0304', 'province_code' => '03'],
            ['name' => 'El Tambo', 'code' => '0305', 'province_code' => '03'],
            ['name' => 'Déleg', 'code' => '0306', 'province_code' => '03'],
            ['name' => 'Suscal', 'code' => '0307', 'province_code' => '03'],

            // CARCHI (04)
            ['name' => 'Tulcán', 'code' => '0401', 'province_code' => '04'],
            ['name' => 'Bolívar', 'code' => '0402', 'province_code' => '04'],
            ['name' => 'Espejo', 'code' => '0403', 'province_code' => '04'],
            ['name' => 'Mira', 'code' => '0404', 'province_code' => '04'],
            ['name' => 'Montúfar', 'code' => '0405', 'province_code' => '04'],
            ['name' => 'San Pedro de Huaca', 'code' => '0406', 'province_code' => '04'],

            // COTOPAXI (05)
            ['name' => 'Latacunga', 'code' => '0501', 'province_code' => '05'],
            ['name' => 'La Maná', 'code' => '0502', 'province_code' => '05'],
            ['name' => 'Pangua', 'code' => '0503', 'province_code' => '05'],
            ['name' => 'Pujilí', 'code' => '0504', 'province_code' => '05'],
            ['name' => 'Salcedo', 'code' => '0505', 'province_code' => '05'],
            ['name' => 'Saquisilí', 'code' => '0506', 'province_code' => '05'],
            ['name' => 'Sigchos', 'code' => '0507', 'province_code' => '05'],

            // CHIMBORAZO (06)
            ['name' => 'Riobamba', 'code' => '0601', 'province_code' => '06'],
            ['name' => 'Alausí', 'code' => '0602', 'province_code' => '06'],
            ['name' => 'Colta', 'code' => '0603', 'province_code' => '06'],
            ['name' => 'Chambo', 'code' => '0604', 'province_code' => '06'],
            ['name' => 'Chunchi', 'code' => '0605', 'province_code' => '06'],
            ['name' => 'Guamote', 'code' => '0606', 'province_code' => '06'],
            ['name' => 'Guano', 'code' => '0607', 'province_code' => '06'],
            ['name' => 'Pallatanga', 'code' => '0608', 'province_code' => '06'],
            ['name' => 'Penipe', 'code' => '0609', 'province_code' => '06'],
            ['name' => 'Cumandá', 'code' => '0610', 'province_code' => '06'],

            // EL ORO (07)
            ['name' => 'Machala', 'code' => '0701', 'province_code' => '07'],
            ['name' => 'Arenillas', 'code' => '0702', 'province_code' => '07'],
            ['name' => 'Atahualpa', 'code' => '0703', 'province_code' => '07'],
            ['name' => 'Balsas', 'code' => '0704', 'province_code' => '07'],
            ['name' => 'Chilla', 'code' => '0705', 'province_code' => '07'],
            ['name' => 'El Guabo', 'code' => '0706', 'province_code' => '07'],
            ['name' => 'Huaquillas', 'code' => '0707', 'province_code' => '07'],
            ['name' => 'Marcabelí', 'code' => '0708', 'province_code' => '07'],
            ['name' => 'Pasaje', 'code' => '0709', 'province_code' => '07'],
            ['name' => 'Piñas', 'code' => '0710', 'province_code' => '07'],
            ['name' => 'Portovelo', 'code' => '0711', 'province_code' => '07'],
            ['name' => 'Santa Rosa', 'code' => '0712', 'province_code' => '07'],
            ['name' => 'Zaruma', 'code' => '0713', 'province_code' => '07'],
            ['name' => 'Las Lajas', 'code' => '0714', 'province_code' => '07'],

            // ESMERALDAS (08)
            ['name' => 'Esmeraldas', 'code' => '0801', 'province_code' => '08'],
            ['name' => 'Eloy Alfaro', 'code' => '0802', 'province_code' => '08'],
            ['name' => 'Muisne', 'code' => '0803', 'province_code' => '08'],
            ['name' => 'Quinindé', 'code' => '0804', 'province_code' => '08'],
            ['name' => 'San Lorenzo', 'code' => '0805', 'province_code' => '08'],
            ['name' => 'Atacames', 'code' => '0806', 'province_code' => '08'],
            ['name' => 'Rioverde', 'code' => '0807', 'province_code' => '08'],
            ['name' => 'La Concordia', 'code' => '0808', 'province_code' => '08'],

            // GUAYAS (09)
            ['name' => 'Guayaquil', 'code' => '0901', 'province_code' => '09'],
            ['name' => 'Alfredo Baquerizo Moreno', 'code' => '0902', 'province_code' => '09'],
            ['name' => 'Balao', 'code' => '0903', 'province_code' => '09'],
            ['name' => 'Balzar', 'code' => '0904', 'province_code' => '09'],
            ['name' => 'Colimes', 'code' => '0905', 'province_code' => '09'],
            ['name' => 'Daule', 'code' => '0906', 'province_code' => '09'],
            ['name' => 'Durán', 'code' => '0907', 'province_code' => '09'],
            ['name' => 'El Empalme', 'code' => '0908', 'province_code' => '09'],
            ['name' => 'El Triunfo', 'code' => '0909', 'province_code' => '09'],
            ['name' => 'Milagro', 'code' => '0910', 'province_code' => '09'],
            ['name' => 'Naranjal', 'code' => '0911', 'province_code' => '09'],
            ['name' => 'Naranjito', 'code' => '0912', 'province_code' => '09'],
            ['name' => 'Palestina', 'code' => '0913', 'province_code' => '09'],
            ['name' => 'Pedro Carbo', 'code' => '0914', 'province_code' => '09'],
            ['name' => 'Samborondón', 'code' => '0915', 'province_code' => '09'],
            ['name' => 'Santa Lucía', 'code' => '0916', 'province_code' => '09'],
            ['name' => 'Salitre', 'code' => '0917', 'province_code' => '09'],
            ['name' => 'San Jacinto de Yaguachi', 'code' => '0918', 'province_code' => '09'],
            ['name' => 'Playas', 'code' => '0919', 'province_code' => '09'],
            ['name' => 'Simón Bolívar', 'code' => '0920', 'province_code' => '09'],
            ['name' => 'Coronel Marcelino Maridueña', 'code' => '0921', 'province_code' => '09'],
            ['name' => 'Lomas de Sargentillo', 'code' => '0922', 'province_code' => '09'],
            ['name' => 'Nobol', 'code' => '0923', 'province_code' => '09'],
            ['name' => 'General Antonio Elizalde', 'code' => '0924', 'province_code' => '09'],
            ['name' => 'Isidro Ayora', 'code' => '0925', 'province_code' => '09'],

            // IMBABURA (10)
            ['name' => 'Ibarra', 'code' => '1001', 'province_code' => '10'],
            ['name' => 'Antonio Ante', 'code' => '1002', 'province_code' => '10'],
            ['name' => 'Cotacachi', 'code' => '1003', 'province_code' => '10'],
            ['name' => 'Otavalo', 'code' => '1004', 'province_code' => '10'],
            ['name' => 'Pimampiro', 'code' => '1005', 'province_code' => '10'],
            ['name' => 'San Miguel de Urcuquí', 'code' => '1006', 'province_code' => '10'],

            // LOJA (11)
            ['name' => 'Loja', 'code' => '1101', 'province_code' => '11'],
            ['name' => 'Calvas', 'code' => '1102', 'province_code' => '11'],
            ['name' => 'Catamayo', 'code' => '1103', 'province_code' => '11'],
            ['name' => 'Celica', 'code' => '1104', 'province_code' => '11'],
            ['name' => 'Chaguarpamba', 'code' => '1105', 'province_code' => '11'],
            ['name' => 'Espíndola', 'code' => '1106', 'province_code' => '11'],
            ['name' => 'Gonzanamá', 'code' => '1107', 'province_code' => '11'],
            ['name' => 'Macará', 'code' => '1108', 'province_code' => '11'],
            ['name' => 'Paltas', 'code' => '1109', 'province_code' => '11'],
            ['name' => 'Puyango', 'code' => '1110', 'province_code' => '11'],
            ['name' => 'Saraguro', 'code' => '1111', 'province_code' => '11'],
            ['name' => 'Sozoranga', 'code' => '1112', 'province_code' => '11'],
            ['name' => 'Zapotillo', 'code' => '1113', 'province_code' => '11'],
            ['name' => 'Pindal', 'code' => '1114', 'province_code' => '11'],
            ['name' => 'Quilanga', 'code' => '1115', 'province_code' => '11'],
            ['name' => 'Olmedo', 'code' => '1116', 'province_code' => '11'],

            // LOS RÍOS (12)
            ['name' => 'Babahoyo', 'code' => '1201', 'province_code' => '12'],
            ['name' => 'Baba', 'code' => '1202', 'province_code' => '12'],
            ['name' => 'Montalvo', 'code' => '1203', 'province_code' => '12'],
            ['name' => 'Puebloviejo', 'code' => '1204', 'province_code' => '12'],
            ['name' => 'Quevedo', 'code' => '1205', 'province_code' => '12'],
            ['name' => 'Urdaneta', 'code' => '1206', 'province_code' => '12'],
            ['name' => 'Ventanas', 'code' => '1207', 'province_code' => '12'],
            ['name' => 'Vinces', 'code' => '1208', 'province_code' => '12'],
            ['name' => 'Palenque', 'code' => '1209', 'province_code' => '12'],
            ['name' => 'Buena Fé', 'code' => '1210', 'province_code' => '12'],
            ['name' => 'Valencia', 'code' => '1211', 'province_code' => '12'],
            ['name' => 'Mocache', 'code' => '1212', 'province_code' => '12'],
            ['name' => 'Quinsaloma', 'code' => '1213', 'province_code' => '12'],

            // MANABÍ (13)
            ['name' => 'Portoviejo', 'code' => '1301', 'province_code' => '13'],
            ['name' => 'Bolívar', 'code' => '1302', 'province_code' => '13'],
            ['name' => 'Chone', 'code' => '1303', 'province_code' => '13'],
            ['name' => 'El Carmen', 'code' => '1304', 'province_code' => '13'],
            ['name' => 'Flavio Alfaro', 'code' => '1305', 'province_code' => '13'],
            ['name' => 'Jipijapa', 'code' => '1306', 'province_code' => '13'],
            ['name' => 'Junín', 'code' => '1307', 'province_code' => '13'],
            ['name' => 'Manta', 'code' => '1308', 'province_code' => '13'],
            ['name' => 'Montecristi', 'code' => '1309', 'province_code' => '13'],
            ['name' => 'Paján', 'code' => '1310', 'province_code' => '13'],
            ['name' => 'Pichincha', 'code' => '1311', 'province_code' => '13'],
            ['name' => 'Rocafuerte', 'code' => '1312', 'province_code' => '13'],
            ['name' => 'Santa Ana', 'code' => '1313', 'province_code' => '13'],
            ['name' => 'Sucre', 'code' => '1314', 'province_code' => '13'],
            ['name' => 'Tosagua', 'code' => '1315', 'province_code' => '13'],
            ['name' => 'Veinticuatro de Mayo', 'code' => '1316', 'province_code' => '13'],
            ['name' => 'Pedernales', 'code' => '1317', 'province_code' => '13'],
            ['name' => 'Olmedo', 'code' => '1318', 'province_code' => '13'],
            ['name' => 'Puerto López', 'code' => '1319', 'province_code' => '13'],
            ['name' => 'Jama', 'code' => '1320', 'province_code' => '13'],
            ['name' => 'Jaramijó', 'code' => '1321', 'province_code' => '13'],
            ['name' => 'San Vicente', 'code' => '1322', 'province_code' => '13'],

            // MORONA SANTIAGO (14)
            ['name' => 'Morona', 'code' => '1401', 'province_code' => '14'],
            ['name' => 'Gualaquiza', 'code' => '1402', 'province_code' => '14'],
            ['name' => 'Limón Indanza', 'code' => '1403', 'province_code' => '14'],
            ['name' => 'Palora', 'code' => '1404', 'province_code' => '14'],
            ['name' => 'Santiago', 'code' => '1405', 'province_code' => '14'],
            ['name' => 'Sucúa', 'code' => '1406', 'province_code' => '14'],
            ['name' => 'Huamboya', 'code' => '1407', 'province_code' => '14'],
            ['name' => 'San Juan Bosco', 'code' => '1408', 'province_code' => '14'],
            ['name' => 'Taisha', 'code' => '1409', 'province_code' => '14'],
            ['name' => 'Logroño', 'code' => '1410', 'province_code' => '14'],
            ['name' => 'Pablo Sexto', 'code' => '1411', 'province_code' => '14'],
            ['name' => 'Tiwintza', 'code' => '1412', 'province_code' => '14'],

            // NAPO (15)
            ['name' => 'Tena', 'code' => '1501', 'province_code' => '15'],
            ['name' => 'Archidona', 'code' => '1502', 'province_code' => '15'],
            ['name' => 'El Chaco', 'code' => '1503', 'province_code' => '15'],
            ['name' => 'Quijos', 'code' => '1504', 'province_code' => '15'],
            ['name' => 'Carlos Julio Arosemena Tola', 'code' => '1505', 'province_code' => '15'],

            // PASTAZA (16)
            ['name' => 'Pastaza', 'code' => '1601', 'province_code' => '16'],
            ['name' => 'Mera', 'code' => '1602', 'province_code' => '16'],
            ['name' => 'Santa Clara', 'code' => '1603', 'province_code' => '16'],
            ['name' => 'Arajuno', 'code' => '1604', 'province_code' => '16'],

            // PICHINCHA (17)
            ['name' => 'Quito', 'code' => '1701', 'province_code' => '17'],
            ['name' => 'Cayambe', 'code' => '1702', 'province_code' => '17'],
            ['name' => 'Mejía', 'code' => '1703', 'province_code' => '17'],
            ['name' => 'Pedro Moncayo', 'code' => '1704', 'province_code' => '17'],
            ['name' => 'Rumiñahui', 'code' => '1705', 'province_code' => '17'],
            ['name' => 'San Miguel de los Bancos', 'code' => '1706', 'province_code' => '17'],
            ['name' => 'Pedro Vicente Maldonado', 'code' => '1707', 'province_code' => '17'],
            ['name' => 'Puerto Quito', 'code' => '1708', 'province_code' => '17'],

            // TUNGURAHUA (18)
            ['name' => 'Ambato', 'code' => '1801', 'province_code' => '18'],
            ['name' => 'Baños de Agua Santa', 'code' => '1802', 'province_code' => '18'],
            ['name' => 'Cevallos', 'code' => '1803', 'province_code' => '18'],
            ['name' => 'Mocha', 'code' => '1804', 'province_code' => '18'],
            ['name' => 'Patate', 'code' => '1805', 'province_code' => '18'],
            ['name' => 'Quero', 'code' => '1806', 'province_code' => '18'],
            ['name' => 'San Pedro de Pelileo', 'code' => '1807', 'province_code' => '18'],
            ['name' => 'Santiago de Píllaro', 'code' => '1808', 'province_code' => '18'],
            ['name' => 'Tisaleo', 'code' => '1809', 'province_code' => '18'],

            // ZAMORA CHINCHIPE (19)
            ['name' => 'Zamora', 'code' => '1901', 'province_code' => '19'],
            ['name' => 'Chinchipe', 'code' => '1902', 'province_code' => '19'],
            ['name' => 'Nangaritza', 'code' => '1903', 'province_code' => '19'],
            ['name' => 'Yacuambi', 'code' => '1904', 'province_code' => '19'],
            ['name' => 'Yantzaza', 'code' => '1905', 'province_code' => '19'],
            ['name' => 'El Pangui', 'code' => '1906', 'province_code' => '19'],
            ['name' => 'Centinela del Cóndor', 'code' => '1907', 'province_code' => '19'],
            ['name' => 'Palanda', 'code' => '1908', 'province_code' => '19'],
            ['name' => 'Paquisha', 'code' => '1909', 'province_code' => '19'],

            // GALÁPAGOS (20)
            ['name' => 'San Cristóbal', 'code' => '2001', 'province_code' => '20'],
            ['name' => 'Isabela', 'code' => '2002', 'province_code' => '20'],
            ['name' => 'Santa Cruz', 'code' => '2003', 'province_code' => '20'],

            // SUCUMBÍOS (21)
            ['name' => 'Lago Agrio', 'code' => '2101', 'province_code' => '21'],
            ['name' => 'Gonzalo Pizarro', 'code' => '2102', 'province_code' => '21'],
            ['name' => 'Putumayo', 'code' => '2103', 'province_code' => '21'],
            ['name' => 'Shushufindi', 'code' => '2104', 'province_code' => '21'],
            ['name' => 'Sucumbíos', 'code' => '2105', 'province_code' => '21'],
            ['name' => 'Cascales', 'code' => '2106', 'province_code' => '21'],
            ['name' => 'Cuyabeno', 'code' => '2107', 'province_code' => '21'],

            // ORELLANA (22)
            ['name' => 'Orellana', 'code' => '2201', 'province_code' => '22'],
            ['name' => 'Aguarico', 'code' => '2202', 'province_code' => '22'],
            ['name' => 'La Joya de los Sachas', 'code' => '2203', 'province_code' => '22'],
            ['name' => 'Loreto', 'code' => '2204', 'province_code' => '22'],

            // SANTO DOMINGO DE LOS TSÁCHILAS (23)
            ['name' => 'Santo Domingo', 'code' => '2301', 'province_code' => '23'],

            // SANTA ELENA (24)
            ['name' => 'Santa Elena', 'code' => '2401', 'province_code' => '24'],
            ['name' => 'La Libertad', 'code' => '2402', 'province_code' => '24'],
            ['name' => 'Salinas', 'code' => '2403', 'province_code' => '24'],
        ];

        foreach ($cantones as $canton) {
            $province = Province::where('code', $canton['province_code'])->first();

            if ($province) {
                Canton::create([
                    'name' => $canton['name'],
                    'code' => $canton['code'],
                    'province_id' => $province->id,
                ]);
            }
        }
    }
}
