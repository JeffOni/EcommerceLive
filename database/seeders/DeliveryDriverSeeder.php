<?php

namespace Database\Seeders;

use App\Models\DeliveryDriver;
use Illuminate\Database\Seeder;

class DeliveryDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@delivery.com',
                'phone' => '+593 99 123 4567',
                'license_number' => 'LIC001234',
                'vehicle_type' => 'moto',
                'vehicle_plate' => 'ABC-1234',
                'identification_number' => '1234567890',
                'address' => 'Av. Amazonas 123, Quito',
                'delivery_fee' => 5.00,
                'is_active' => true,
                'total_deliveries' => 45,
                'rating' => 4.8,
                'emergency_contact' => [
                    'name' => 'María Rodríguez',
                    'phone' => '+593 99 765 4321'
                ]
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@delivery.com',
                'phone' => '+593 98 234 5678',
                'license_number' => 'LIC005678',
                'vehicle_type' => 'bicicleta',
                'vehicle_plate' => null,
                'identification_number' => '0987654321',
                'address' => 'Calle 10 de Agosto 456, Quito',
                'delivery_fee' => 3.50,
                'is_active' => true,
                'total_deliveries' => 32,
                'rating' => 4.9,
                'emergency_contact' => [
                    'name' => 'Luis Martínez',
                    'phone' => '+593 98 876 5432'
                ]
            ],
            [
                'name' => 'Miguel Torres',
                'email' => 'miguel.torres@delivery.com',
                'phone' => '+593 97 345 6789',
                'license_number' => 'LIC009012',
                'vehicle_type' => 'auto',
                'vehicle_plate' => 'XYZ-5678',
                'identification_number' => '1122334455',
                'address' => 'Av. Patria 789, Quito',
                'delivery_fee' => 7.50,
                'is_active' => true,
                'total_deliveries' => 67,
                'rating' => 4.7,
                'emergency_contact' => [
                    'name' => 'Carmen Torres',
                    'phone' => '+593 97 987 6543'
                ]
            ],
            [
                'name' => 'Sofia Vega',
                'email' => 'sofia.vega@delivery.com',
                'phone' => '+593 96 456 7890',
                'license_number' => 'LIC003456',
                'vehicle_type' => 'moto',
                'vehicle_plate' => 'DEF-9012',
                'identification_number' => '5566778899',
                'address' => 'Av. Eloy Alfaro 321, Quito',
                'delivery_fee' => 5.50,
                'is_active' => false,
                'total_deliveries' => 23,
                'rating' => 4.5,
                'emergency_contact' => [
                    'name' => 'Juan Vega',
                    'phone' => '+593 96 654 3210'
                ]
            ],
            [
                'name' => 'Roberto Mendoza',
                'email' => 'roberto.mendoza@delivery.com',
                'phone' => '+593 95 567 8901',
                'license_number' => 'LIC007890',
                'vehicle_type' => 'furgoneta',
                'vehicle_plate' => 'GHI-3456',
                'identification_number' => '9988776655',
                'address' => 'Av. 6 de Diciembre 654, Quito',
                'delivery_fee' => 12.00,
                'is_active' => true,
                'total_deliveries' => 89,
                'rating' => 4.6,
                'emergency_contact' => [
                    'name' => 'Elena Mendoza',
                    'phone' => '+593 95 321 0987'
                ]
            ]
        ];

        foreach ($drivers as $driver) {
            DeliveryDriver::create($driver);
        }
    }
}
