<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeAddress;
use Illuminate\Http\Request;

class OfficeAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = OfficeAddress::orderBy('is_main', 'desc')
            ->orderBy('name', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('province', 'like', '%' . $search . '%')
                    ->orWhere('canton', 'like', '%' . $search . '%');
            });
        }

        $offices = $query->paginate($perPage);

        return view('admin.office-addresses.index', compact('offices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.office-addresses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'province' => 'required|string|max:100',
            'canton' => 'required|string|max:100',
            'parish' => 'required|string|max:100',
            'reference' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'working_hours' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_main' => 'boolean',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        $data = $request->except(['latitude', 'longitude']);

        // Manejar coordenadas
        if ($request->filled(['latitude', 'longitude'])) {
            $data['coordinates'] = [
                'lat' => $request->latitude,
                'lng' => $request->longitude
            ];
        }

        // Si se marca como principal, remover flag de otras oficinas
        if ($request->is_main) {
            OfficeAddress::where('is_main', true)->update(['is_main' => false]);
        }

        OfficeAddress::create($data);

        return redirect()->route('admin.office-addresses.index')
            ->with('swal', [
                'title' => '¡Éxito!',
                'text' => 'Oficina creada correctamente.',
                'icon' => 'success'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(OfficeAddress $officeAddress)
    {
        return view('admin.office-addresses.show', compact('officeAddress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfficeAddress $officeAddress)
    {
        return view('admin.office-addresses.edit', compact('officeAddress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfficeAddress $officeAddress)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'province' => 'required|string|max:100',
            'canton' => 'required|string|max:100',
            'parish' => 'required|string|max:100',
            'reference' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'working_hours' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_main' => 'boolean',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        $data = $request->except(['latitude', 'longitude']);

        // Manejar coordenadas
        if ($request->filled(['latitude', 'longitude'])) {
            $data['coordinates'] = [
                'lat' => $request->latitude,
                'lng' => $request->longitude
            ];
        } elseif ($request->input('latitude') === '' && $request->input('longitude') === '') {
            $data['coordinates'] = null;
        }

        // Si se marca como principal, remover flag de otras oficinas
        if ($request->is_main && !$officeAddress->is_main) {
            OfficeAddress::where('is_main', true)->update(['is_main' => false]);
        }

        $officeAddress->update($data);

        return redirect()->route('admin.office-addresses.index')
            ->with('swal', [
                'title' => '¡Éxito!',
                'text' => 'Oficina actualizada correctamente.',
                'icon' => 'success'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficeAddress $officeAddress)
    {
        // No permitir eliminar la oficina principal si es la única
        if ($officeAddress->is_main && OfficeAddress::count() === 1) {
            return redirect()->back()
                ->with('swal', [
                    'title' => 'Error',
                    'text' => 'No se puede eliminar la única oficina del sistema.',
                    'icon' => 'error'
                ]);
        }

        $officeAddress->delete();

        return redirect()->route('admin.office-addresses.index')
            ->with('swal', [
                'title' => '¡Éxito!',
                'text' => 'Oficina eliminada correctamente.',
                'icon' => 'success'
            ]);
    }

    /**
     * Cambiar estado activo/inactivo
     */
    public function toggleStatus(OfficeAddress $officeAddress)
    {
        $officeAddress->update(['is_active' => !$officeAddress->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'is_active' => $officeAddress->is_active
        ]);
    }
}
