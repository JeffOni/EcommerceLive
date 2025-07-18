<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'pickup_address',
                'value' => json_encode([
                    'name' => 'Tienda Principal',
                    'address' => 'Av. Principal #123, Sector Centro',
                    'city' => 'Quito',
                    'phone' => '+593 99 999 9999',
                    'hours' => 'Lunes a Viernes: 9:00 AM - 6:00 PM, Sábados: 9:00 AM - 1:00 PM',
                    'coordinates' => [
                        'lat' => -0.1807,
                        'lng' => -78.4678
                    ]
                ]),
                'type' => 'json',
                'description' => 'Dirección física para retiro en tienda',
                'is_public' => true
            ],
            [
                'key' => 'delivery_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Habilitar servicio de entrega a domicilio',
                'is_public' => true
            ],
            [
                'key' => 'pickup_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Habilitar retiro en tienda',
                'is_public' => true
            ],
            [
                'key' => 'min_order_delivery',
                'value' => '25.00',
                'type' => 'decimal',
                'description' => 'Monto mínimo para entrega a domicilio',
                'is_public' => true
            ],
            [
                'key' => 'delivery_fee',
                'value' => '3.00',
                'type' => 'decimal',
                'description' => 'Costo de envío estándar',
                'is_public' => true
            ],
            [
                'key' => 'free_delivery_threshold',
                'value' => '50.00',
                'type' => 'decimal',
                'description' => 'Monto para envío gratis',
                'is_public' => true
            ]
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
