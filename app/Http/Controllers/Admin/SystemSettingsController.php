<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    /**
     * Display the system settings form.
     */
    public function index()
    {
        $settings = SystemSetting::orderBy('key')->get()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the system settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'pickup_address.name' => 'required|string|max:255',
            'pickup_address.address' => 'required|string|max:500',
            'pickup_address.city' => 'required|string|max:255',
            'pickup_address.phone' => 'required|string|max:20',
            'pickup_address.hours' => 'required|string|max:500',
            'delivery_fee' => 'required|numeric|min:0',
            'min_order_delivery' => 'required|numeric|min:0',
            'free_delivery_threshold' => 'required|numeric|min:0',
        ]);

        try {
            // Actualizar dirección de retiro
            $pickupAddress = [
                'name' => $request->input('pickup_address.name'),
                'address' => $request->input('pickup_address.address'),
                'city' => $request->input('pickup_address.city'),
                'phone' => $request->input('pickup_address.phone'),
                'hours' => $request->input('pickup_address.hours'),
                'coordinates' => [
                    'lat' => (float) $request->input('pickup_address.lat', -0.1807),
                    'lng' => (float) $request->input('pickup_address.lng', -78.4678)
                ]
            ];

            SystemSetting::set('pickup_address', $pickupAddress, 'json');

            // Actualizar configuraciones de entrega
            SystemSetting::set('delivery_enabled', $request->boolean('delivery_enabled'), 'boolean');
            SystemSetting::set('pickup_enabled', $request->boolean('pickup_enabled'), 'boolean');
            SystemSetting::set('delivery_fee', $request->input('delivery_fee'), 'decimal');
            SystemSetting::set('min_order_delivery', $request->input('min_order_delivery'), 'decimal');
            SystemSetting::set('free_delivery_threshold', $request->input('free_delivery_threshold'), 'decimal');

            return redirect()->back()->with('success', 'Configuraciones actualizadas correctamente.');

        } catch (\Exception $e) {
            \Log::error('Error actualizando configuraciones: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar las configuraciones.');
        }
    }

    /**
     * Get public settings for frontend use
     */
    public function getPublicSettings()
    {
        $settings = SystemSetting::getPublicSettings();

        return response()->json($settings);
    }
}
