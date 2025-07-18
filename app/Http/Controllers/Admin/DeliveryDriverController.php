<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeliveryDriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $status = $request->get('status');
        $vehicleType = $request->get('vehicle_type');

        $query = DeliveryDriver::orderBy('created_at', 'desc');

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('license_number', 'like', '%' . $search . '%')
                    ->orWhere('identification_number', 'like', '%' . $search . '%');
            });
        }

        // Aplicar filtro de estado si existe
        if ($status !== null && $status !== '') {
            $query->where('is_active', (bool) $status);
        }

        // Aplicar filtro de tipo de vehículo si existe
        if ($vehicleType) {
            $query->where('vehicle_type', $vehicleType);
        }

        $drivers = $query->paginate($perPage);

        // Mantener parámetros de búsqueda y paginación en los enlaces
        $drivers->appends($request->only(['search', 'status', 'vehicle_type', 'per_page']));

        // Si es una petición AJAX, devolver solo el contenido
        if ($request->ajax()) {
            return view('admin.delivery-drivers.partials.drivers-content', compact('drivers'))->render();
        }

        return view('admin.delivery-drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.delivery-drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:delivery_drivers,email',
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|unique:delivery_drivers,license_number',
            'vehicle_type' => 'required|string|in:moto,auto,bicicleta,camion,furgoneta',
            'vehicle_plate' => 'nullable|string|max:10',
            'identification_number' => 'required|string|unique:delivery_drivers,identification_number',
            'address' => 'nullable|string',
            'delivery_fee' => 'required|numeric|min:0',
            'intra_province_rate' => 'required|numeric|min:0',
            'inter_province_rate' => 'required|numeric|min:0',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'emergency_contact.name' => 'nullable|string|max:255',
            'emergency_contact.phone' => 'nullable|string|max:20',
        ]);

        $data = $request->all();

        // Manejar la subida de foto de perfil
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('delivery_drivers/photos', $filename, 'public');
            $data['profile_photo'] = $path;
        }

        // Preparar contacto de emergencia
        if ($request->filled('emergency_contact.name') || $request->filled('emergency_contact.phone')) {
            $data['emergency_contact'] = [
                'name' => $request->input('emergency_contact.name'),
                'phone' => $request->input('emergency_contact.phone'),
            ];
        }

        DeliveryDriver::create($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Repartidor creado correctamente',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.delivery-drivers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryDriver $deliveryDriver)
    {
        $deliveryDriver->load(['shipments.order.user']);
        return view('admin.delivery-drivers.show', compact('deliveryDriver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeliveryDriver $deliveryDriver)
    {
        return view('admin.delivery-drivers.edit', compact('deliveryDriver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeliveryDriver $deliveryDriver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:delivery_drivers,email,' . $deliveryDriver->id,
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|unique:delivery_drivers,license_number,' . $deliveryDriver->id,
            'vehicle_type' => 'required|string|in:moto,auto,bicicleta,camion,furgoneta',
            'vehicle_plate' => 'nullable|string|max:10',
            'identification_number' => 'required|string|unique:delivery_drivers,identification_number,' . $deliveryDriver->id,
            'address' => 'nullable|string',
            'delivery_fee' => 'required|numeric|min:0',
            'intra_province_rate' => 'required|numeric|min:0',
            'inter_province_rate' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'emergency_contact.name' => 'nullable|string|max:255',
            'emergency_contact.phone' => 'nullable|string|max:20',
        ]);

        $data = $request->except(['profile_photo', 'emergency_contact']);

        // Manejar la subida de foto de perfil
        if ($request->hasFile('profile_photo')) {
            // Eliminar foto anterior si existe
            if ($deliveryDriver->profile_photo) {
                Storage::delete('public/' . $deliveryDriver->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('delivery_drivers/photos', $filename, 'public');
            $data['profile_photo'] = $path;
        }

        // Preparar contacto de emergencia
        if ($request->filled('emergency_contact.name') || $request->filled('emergency_contact.phone')) {
            $data['emergency_contact'] = [
                'name' => $request->input('emergency_contact.name'),
                'phone' => $request->input('emergency_contact.phone'),
            ];
        } else {
            $data['emergency_contact'] = null;
        }

        $deliveryDriver->update($data);

        return redirect()->route('admin.delivery-drivers.index')
            ->with('swal', [
                'title' => '¡Éxito!',
                'text' => 'Repartidor actualizado correctamente.',
                'icon' => 'success'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryDriver $deliveryDriver)
    {
        $deliveryDriver->delete();

        return redirect()->route('admin.delivery-drivers.index')->with('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'El repartidor se ha eliminado correctamente.',
            'timeout' => 3000
        ]);
    }

    /**
     * Toggle driver status
     */
    public function toggleStatus(DeliveryDriver $deliveryDriver)
    {
        $deliveryDriver->update(['is_active' => !$deliveryDriver->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'new_status' => $deliveryDriver->is_active
        ]);
    }

    /**
     * Get active drivers for AJAX requests
     */
    public function getActiveDrivers()
    {
        $drivers = DeliveryDriver::active()
            ->select('id', 'name', 'phone', 'email', 'vehicle_type', 'profile_photo')
            ->orderBy('name')
            ->get()
            ->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'phone' => $driver->phone,
                    'email' => $driver->email,
                    'vehicle_type' => $driver->vehicle_type,
                    'profile_photo_url' => $driver->profile_photo_url
                ];
            });

        return response()->json($drivers);
    }
}
