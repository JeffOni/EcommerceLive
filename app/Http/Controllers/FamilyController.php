<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Option;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    // Muestra la información de una familia específica y sus opciones relacionadas
    public function show(Family $family)
    {
        // Obtiene todas las opciones que tienen productos cuya subcategoría pertenece a una categoría de la familia dada
        // $options = Option::whereHas('products.subcategory.category', function ($query) use ($family) {
        //     $query->where('family_id', $family->id);
        // })
        // Carga las características (features) de cada opción, filtrando solo aquellas que tengan variantes
        // cuyos productos también pertenezcan a la familia dada
        // ->with([
        //     'features'=> function ($query) use ($family) {
        //         $query->whereHas('variants.product.subcategory.category' , function ($query) use ($family) {
        //             $query->where('family_id', $family->id);
        //         });
        //     },
        // ])->get();

        // Devuelve las opciones encontradas (esto es solo para depuración, normalmente se eliminaría en producción)


        // Renderiza la vista families.show pasando la familia seleccionada
        return view('families.show', compact('family'));
    }
}
