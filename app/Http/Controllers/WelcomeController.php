<?php

namespace App\Http\Controllers;

use App\Models\Cover;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    //
    public function index()
    {
        // Obtiene las portadas activas cuya fecha de inicio ya pasó y la fecha de fin es hoy o en el futuro, o no tiene fecha de fin
        $covers = Cover::where('is_active', true)
            // Compara solo la fecha (ignora la hora) para start_at
            ->whereDate('start_at', '<=', now())
            // El closure function($query) permite agrupar condiciones OR/AND
            ->where(function ($query) {
                // Compara solo la fecha para end_at o permite que sea nulo (sin fecha de fin)
                $query->whereDate('end_at', '>=', now())
                    ->orWhereNull('end_at');
            })
            ->orderBy('order')
            ->get();
        // Aquí puedes agregar la lógica para mostrar la vista de bienvenida
        // Por ejemplo, podrías cargar productos destacados o cualquier otra información que desees mostrar.
        return view('welcome', compact('covers'));
    }
}
