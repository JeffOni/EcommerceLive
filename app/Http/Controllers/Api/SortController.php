<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cover;
use Illuminate\Http\Request;

class SortController extends Controller
{
    //
    public function sortCoversList(Request $request)
    {
        // Recupera el array de objetos {id, order} enviado desde el frontend
        $sorts = $request->get('sorts');

        // Recorre cada objeto recibido y actualiza el campo 'order' en la base de datos
        foreach ($sorts as $sort) {
            // Busca la portada por su ID
            $cover = Cover::find($sort['id']);
            if ($cover) {
                // Asigna el nuevo valor de orden
                $cover->order = $sort['order'];
                // Guarda los cambios en la base de datos
                $cover->save();
            }
        }
    }
}
