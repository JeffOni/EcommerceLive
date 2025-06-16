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
     * =================================================================
     * MÉTODO DESTROY - ELIMINACIÓN DE DIRECCIONES CON PATRÓN ADMIN
     * =================================================================
     * 
     * Este método implementa el mismo patrón usado en ProductController del admin:
     * 1. Recibe el model binding automático de Laravel (Address $address)
     * 2. Valida que el usuario autenticado sea el propietario de la dirección
     * 3. Elimina la dirección de la base de datos
     * 4. Redirige de vuelta con mensaje SweetAlert de éxito
     * 
     * Flujo completo:
     * Vista Livewire → Formulario oculto → Este método → SweetAlert de éxito
     * 
     * Seguridad:
     * - Protegido por middleware de autenticación en las rutas
     * - Verificación adicional de propiedad del recurso
     * - Model binding automático previene inyección SQL
     *
     * @param  \App\Models\Address  $address - Dirección a eliminar (model binding)
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje SweetAlert
     */
    public function destroy(Address $address)
    {
        // Verificar que la dirección pertenezca al usuario autenticado
        // Esta validación adicional previene que usuarios maliciosos eliminen direcciones ajenas
        if ($address->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar esta dirección');
        }

        // Eliminar la dirección de la base de datos
        $address->delete();

        // Redirigir de vuelta con mensaje SweetAlert de éxito
        // El array 'swal' se pasa a la sesión y se muestra automáticamente en el frontend
        return redirect()->back()->with('swal', [
            'title' => '¡Eliminada!',
            'text' => 'La dirección ha sido eliminada correctamente.',
            'icon' => 'success',
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
    }
}
