<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Muestra la página de envío
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Verificar autenticación (protegido por middleware)
        abort_unless(auth()->check(), 401);

        // Aquí puedes implementar la lógica para mostrar la página de envío
        // Por ejemplo, podrías obtener información del carrito, calcular costos de envío, etc.

        return view('shipping.index'); // Asegúrate de tener una vista llamada shipping.index
    }

    /**
     * Elimina una dirección
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Address $address)
    {
        // Si usas policies puedes agregar: $this->authorize('delete', $address);
        $address->delete();
        return redirect()->back()->with('swal', [
            'title' => '¡Eliminada!',
            'text' => 'La dirección ha sido eliminada.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false
        ]);
    }
}
